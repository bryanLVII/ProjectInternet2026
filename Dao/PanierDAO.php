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
        $sql = "
            SELECT *
            FROM panier
            WHERE id_client = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            "id" => $idClient
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function creerPanier($idClient)
    {
        $sql = "
            INSERT INTO panier(id_client)
            VALUES(:id)
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            "id" => $idClient
        ]);
    }

    public function ajouterProduit($idPanier, $idProduit)
    {
        $sql = "
        SELECT *
        FROM panier_produit
        WHERE id_panier = :panier
        AND id_produit = :produit
    ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            "panier" => $idPanier,
            "produit" => $idProduit
        ]);

        $ligne = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ligne) {

            $sql = "
            UPDATE panier_produit
            SET quantite = quantite + 1
            WHERE id_panier = :panier
            AND id_produit = :produit
        ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                "panier" => $idPanier,
                "produit" => $idProduit
            ]);

        } else {

            $sql = "
            INSERT INTO panier_produit
            (id_panier, id_produit, quantite)
            VALUES
            (:panier, :produit, 1)
        ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                "panier" => $idPanier,
                "produit" => $idProduit
            ]);
        }
    }

    public function getProduitsPanier($idClient)
    {
        $sql = "
        SELECT 
            p.id_produit,
            p.nom_produit,
            p.prix,
            pp.quantite
        FROM panier pa
        JOIN panier_produit pp ON pa.id_panier = pp.id_panier
        JOIN produit p ON p.id_produit = pp.id_produit
        WHERE pa.id_client = :id
    ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            "id" => $idClient
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}