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
}