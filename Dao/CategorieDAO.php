<?php

class CategorieDAO
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM categorie ORDER BY nom_categorie";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
