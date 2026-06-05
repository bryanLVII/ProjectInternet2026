<?php

class ProduitDAO {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // recuperation de produits
    public function getAllProduits() {
        $sql = "SELECT * FROM produit ORDER BY id_produit DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitById($id) {
        $sql = "SELECT * FROM produit WHERE id_produit = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchProduits($keyword) {

        $sql = "SELECT * FROM produit 
            WHERE nom_produit ILIKE :k";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            "k" => "%$keyword%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitsByCategorie($idCategorie)
    {
        $sql = "
        SELECT *
        FROM produit
        WHERE id_categorie = :id
    ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            "id" => $idCategorie
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRandomProduits($limit = 3)
    {
        $sql = "
        SELECT * FROM produit
        ORDER BY RANDOM()
        LIMIT :lim
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}