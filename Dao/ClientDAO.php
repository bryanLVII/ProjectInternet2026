<?php

class ClientDAO
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM client WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM client WHERE id_client = :id");
        $stmt->execute(["id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("
        SELECT id_client, nom, email, type_client, credits_fidelite
        FROM client
        WHERE type_client != 'admin'
        ORDER BY id_client DESC
    ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $nom, $email, $password = "")
    {
        if ($password !== "") {
            $stmt = $this->db->prepare("
                UPDATE client
                SET nom = :nom,
                    email = :email,
                    mot_de_passe = :password
                WHERE id_client = :id
            ");

            $stmt->execute([
                "nom" => $nom,
                "email" => $email,
                "password" => $password,
                "id" => $id,
            ]);

            return;
        }

        $stmt = $this->db->prepare("
            UPDATE client
            SET nom = :nom,
                email = :email
            WHERE id_client = :id
        ");

        $stmt->execute([
            "nom" => $nom,
            "email" => $email,
            "id" => $id,
        ]);
    }

    public function delete($id)
    {
        // supprimer dépendances AVANT client

        $this->db->prepare("DELETE FROM avis WHERE id_client = :id")
            ->execute(["id" => $id]);

        $this->db->prepare("DELETE FROM commande WHERE id_client = :id")
            ->execute(["id" => $id]);

        $stmt = $this->db->prepare("
        DELETE FROM client
        WHERE id_client = :id
    ");

        $stmt->execute(["id" => $id]);
    }

    public function createClient($nom, $email, $password)
    {
        $stmt = $this->db->prepare("
        INSERT INTO client (nom, email, mot_de_passe, type_client, credits_fidelite)
        VALUES (:nom, :email, :password, 'Particulier', 0)
    ");

        $stmt->execute([
            "nom" => $nom,
            "email" => $email,
            "password" => $password
        ]);
    }
}
