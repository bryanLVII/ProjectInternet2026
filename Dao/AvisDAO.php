<?php

class AvisDAO
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAvisByProduit($idProduit)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, c.nom
            FROM avis a
            JOIN client c ON c.id_client = a.id_client
            WHERE a.id_produit = :id
            ORDER BY a.id_avis DESC
        ");

        $stmt->execute([
            "id" => $idProduit
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addAvis($idClient, $idProduit, $note, $commentaire)
    {
        $stmt = $this->db->prepare("
            INSERT INTO avis
            (id_client, id_produit, note, commentaire)
            VALUES
            (:client, :produit, :note, :commentaire)
        ");

        $stmt->execute([
            "client" => $idClient,
            "produit" => $idProduit,
            "note" => $note,
            "commentaire" => $commentaire
        ]);
    }

    public function clientADejaAvis($idClient, $idProduit)
    {
        $stmt = $this->db->prepare("
            SELECT id_avis
            FROM avis
            WHERE id_client = :client
            AND id_produit = :produit
        ");

        $stmt->execute([
            "client" => $idClient,
            "produit" => $idProduit
        ]);

        return $stmt->fetch();
    }

    public function updateAvis($idAvis, $note, $commentaire)
    {
        $stmt = $this->db->prepare("
        UPDATE avis
        SET note = :note,
            commentaire = :commentaire
        WHERE id_avis = :id
    ");

        $stmt->execute([
            "note" => $note,
            "commentaire" => $commentaire,
            "id" => $idAvis
        ]);
    }

    public function deleteAvis($idAvis)
    {
        $stmt = $this->db->prepare("
        DELETE FROM avis
        WHERE id_avis = :id
    ");

        $stmt->execute([
            "id" => $idAvis
        ]);
    }

    public function getAvisById($idAvis)
    {
        $stmt = $this->db->prepare("
        SELECT *
        FROM avis
        WHERE id_avis = :id
    ");

        $stmt->execute([
            "id" => $idAvis
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}