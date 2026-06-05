<?php

class PanierDAO
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getPanierClient($idClient)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM panier
            WHERE id_client = :id
        ");

        $stmt->execute(["id" => $idClient]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function creerPanier($idClient)
    {
        $stmt = $this->db->prepare("
            INSERT INTO panier(id_client)
            VALUES(:id)
            RETURNING id_panier
        ");

        $stmt->execute(["id" => $idClient]);
        return $stmt->fetchColumn();
    }

    public function getOrCreate($idClient)
    {
        $panier = $this->getPanierClient($idClient);

        if ($panier) {
            return $panier["id_panier"];
        }

        return $this->creerPanier($idClient);
    }

    public function getOrCreatePanier($idClient)
    {
        return $this->getOrCreate($idClient);
    }

    public function add($idPanier, $idProduit)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM panier_produit
            WHERE id_panier = :panier
              AND id_produit = :produit
        ");

        $stmt->execute([
            "panier" => $idPanier,
            "produit" => $idProduit,
        ]);

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $stmt = $this->db->prepare("
                UPDATE panier_produit
                SET quantite = quantite + 1
                WHERE id_panier = :panier
                  AND id_produit = :produit
            ");
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO panier_produit (id_panier, id_produit, quantite)
                VALUES (:panier, :produit, 1)
            ");
        }

        $stmt->execute([
            "panier" => $idPanier,
            "produit" => $idProduit,
        ]);
    }

    public function addProduct($idPanier, $idProduit)
    {
        $this->add($idPanier, $idProduit);
    }

    public function ajouterProduit($idPanier, $idProduit)
    {
        $this->add($idPanier, $idProduit);
    }

    public function getProduitsPanier($idClient)
    {
        $stmt = $this->db->prepare("
            SELECT p.id_produit, p.nom_produit, p.prix, p.stock, pp.quantite
            FROM panier pa
            JOIN panier_produit pp ON pa.id_panier = pp.id_panier
            JOIN produit p ON p.id_produit = pp.id_produit
            WHERE pa.id_client = :id
            ORDER BY p.nom_produit
        ");

        $stmt->execute(["id" => $idClient]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateQuantity($idClient, $idProduit, $action)
    {
        $operator = $action === "plus" ? "+" : "-";
        $stmt = $this->db->prepare("
            UPDATE panier_produit pp
            SET quantite = GREATEST(pp.quantite $operator 1, 1)
            FROM panier pa
            WHERE pa.id_panier = pp.id_panier
              AND pa.id_client = :client
              AND pp.id_produit = :produit
        ");

        $stmt->execute([
            "client" => $idClient,
            "produit" => $idProduit,
        ]);
    }

    public function removeProduct($idClient, $idProduit)
    {
        $stmt = $this->db->prepare("
            DELETE FROM panier_produit pp
            USING panier pa
            WHERE pa.id_panier = pp.id_panier
              AND pa.id_client = :client
              AND pp.id_produit = :produit
        ");

        $stmt->execute([
            "client" => $idClient,
            "produit" => $idProduit,
        ]);
    }

    public function clear($idClient)
    {
        $stmt = $this->db->prepare("
            DELETE FROM panier_produit pp
            USING panier pa
            WHERE pa.id_panier = pp.id_panier
              AND pa.id_client = :client
        ");

        $stmt->execute(["client" => $idClient]);
    }
}
