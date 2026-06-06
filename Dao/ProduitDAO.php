<?php

class ProduitDAO
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllProduits()
    {
        return $this->db->query("SELECT * FROM produit ORDER BY id_produit DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM produit WHERE id_produit = :id");
        $stmt->execute(["id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($keyword)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM produit
            WHERE nom_produit ILIKE :k
               OR description ILIKE :k
               OR marque ILIKE :k
            ORDER BY nom_produit
        ");

        $stmt->execute(["k" => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProduits($keyword)
    {
        return $this->search($keyword);
    }

    public function getByCategorie($id)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM produit
            WHERE id_categorie = :id
            ORDER BY nom_produit
        ");

        $stmt->execute(["id" => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitsByCategorie($id)
    {
        return $this->getByCategorie($id);
    }

    public function getRandomProduits($limit = 3)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM produit
            ORDER BY RANDOM()
            LIMIT :lim
        ");
        $stmt->bindValue(":lim", $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO produit (nom_produit, description, prix, stock, marque, id_categorie)
            VALUES (:nom, :description, :prix, :stock, :marque, :categorie)
        ");

        $stmt->execute([
            "nom" => $data["nom"],
            "description" => $data["description"],
            "prix" => $data["prix"],
            "stock" => $data["stock"],
            "marque" => $data["marque"] ?: null,
            "categorie" => $data["categorie"] ?: null,
        ]);
    }

    public function update($id, array $data)
    {
        $stmt = $this->db->prepare("
            UPDATE produit
            SET nom_produit = :nom,
                description = :description,
                prix = :prix,
                stock = :stock,
                marque = :marque,
                id_categorie = :categorie
            WHERE id_produit = :id
        ");

        $stmt->execute([
            "nom" => $data["nom"],
            "description" => $data["description"],
            "prix" => $data["prix"],
            "stock" => $data["stock"],
            "marque" => $data["marque"] ?: null,
            "categorie" => $data["categorie"] ?: null,
            "id" => $id,
        ]);
    }

    public function delete($id)
    {
        // 1. supprimer les avis liés
        $stmt = $this->db->prepare("
        DELETE FROM avis
        WHERE id_produit = :id
    ");
        $stmt->execute(["id" => $id]);

        // 2. supprimer le produit
        $stmt = $this->db->prepare("
        DELETE FROM produit
        WHERE id_produit = :id
    ");
        $stmt->execute(["id" => $id]);
    }
}
