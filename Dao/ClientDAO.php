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
        return $this->db->query("
            SELECT id_client, nom, email, type_client, credits_fidelite
            FROM client
            ORDER BY id_client DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
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
}
