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
}