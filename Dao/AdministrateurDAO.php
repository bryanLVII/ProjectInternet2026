<?php

class AdministrateurDAO
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT * 
            FROM administrateur 
            WHERE email = :email
        ");

        $stmt->execute([
            "email" => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
        SELECT id_admin, nom, email, mot_de_passe, role
        FROM administrateur
        WHERE id_admin = :id
    ");

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $nom, $email, $password = "")
    {
        if ($password !== "") {
            $stmt = $this->db->prepare("
            UPDATE administrateur
            SET nom = :nom,
                email = :email,
                mot_de_passe = :password
            WHERE id_admin = :id
        ");

            $stmt->execute([
                "nom" => $nom,
                "email" => $email,
                "password" => $password,
                "id" => $id
            ]);

        } else {
            $stmt = $this->db->prepare("
            UPDATE administrateur
            SET nom = :nom,
                email = :email
            WHERE id_admin = :id
        ");

            $stmt->execute([
                "nom" => $nom,
                "email" => $email,
                "id" => $id
            ]);
        }
    }
}