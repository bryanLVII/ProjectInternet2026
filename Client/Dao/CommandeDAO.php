<?php

class CommandeDAO
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function createFromPanier($idClient, array $produits)
    {
        if (empty($produits)) {
            return null;
        }

        $total = 0;

        foreach ($produits as $produit) {
            if ($produit["quantite"] > $produit["stock"]) {
                throw new Exception("Stock insuffisant pour " . $produit["nom_produit"]);
            }

            $total += $produit["prix"] * $produit["quantite"];
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("
                INSERT INTO commande (id_client, total, statut)
                VALUES (:client, :total, 'confirmee')
                RETURNING id_commande
            ");
            $stmt->execute([
                "client" => $idClient,
                "total" => $total,
            ]);
            $idCommande = $stmt->fetchColumn();

            $lineStmt = $this->db->prepare("
                INSERT INTO commande_produit (id_commande, id_produit, quantite, prix_unitaire)
                VALUES (:commande, :produit, :quantite, :prix)
            ");

            $stockStmt = $this->db->prepare("
                UPDATE produit
                SET stock = stock - :quantite
                WHERE id_produit = :produit
                  AND stock >= :quantite
            ");

            foreach ($produits as $produit) {
                $lineStmt->execute([
                    "commande" => $idCommande,
                    "produit" => $produit["id_produit"],
                    "quantite" => $produit["quantite"],
                    "prix" => $produit["prix"],
                ]);

                $stockStmt->execute([
                    "quantite" => $produit["quantite"],
                    "produit" => $produit["id_produit"],
                ]);

                if ($stockStmt->rowCount() !== 1) {
                    throw new Exception("Stock insuffisant pour " . $produit["nom_produit"]);
                }
            }

            $clearStmt = $this->db->prepare("
                DELETE FROM panier_produit pp
                USING panier pa
                WHERE pa.id_panier = pp.id_panier
                  AND pa.id_client = :client
            ");
            $clearStmt->execute(["client" => $idClient]);

            $this->db->commit();
            return $idCommande;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getByClient($idClient)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM commande
            WHERE id_client = :client
            ORDER BY id_commande DESC
        ");
        $stmt->execute(["client" => $idClient]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByIdForClient($idCommande, $idClient)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM commande
            WHERE id_commande = :commande
              AND id_client = :client
        ");
        $stmt->execute([
            "commande" => $idCommande,
            "client" => $idClient,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        return $this->db->query("
            SELECT c.*, cl.nom, cl.email
            FROM commande c
            JOIN client cl ON cl.id_client = c.id_client
            ORDER BY c.id_commande DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLines($idCommande)
    {
        $stmt = $this->db->prepare("
            SELECT cp.*, p.nom_produit
            FROM commande_produit cp
            JOIN produit p ON p.id_produit = cp.id_produit
            WHERE cp.id_commande = :commande
            ORDER BY p.nom_produit
        ");
        $stmt->execute(["commande" => $idCommande]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancelForClient($idCommande, $idClient)
    {
        $commande = $this->getByIdForClient($idCommande, $idClient);

        if (!$commande || $commande["statut"] === "annulee") {
            return false;
        }

        $this->db->beginTransaction();

        try {
            $lignes = $this->getLines($idCommande);
            $stockStmt = $this->db->prepare("
                UPDATE produit
                SET stock = stock + :quantite
                WHERE id_produit = :produit
            ");

            foreach ($lignes as $ligne) {
                $stockStmt->execute([
                    "quantite" => $ligne["quantite"],
                    "produit" => $ligne["id_produit"],
                ]);
            }

            $stmt = $this->db->prepare("
                UPDATE commande
                SET statut = 'annulee'
                WHERE id_commande = :commande
                  AND id_client = :client
            ");
            $stmt->execute([
                "commande" => $idCommande,
                "client" => $idClient,
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
