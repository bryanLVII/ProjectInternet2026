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

    // Test
}