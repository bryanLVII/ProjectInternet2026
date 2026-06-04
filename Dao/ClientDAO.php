<?php

class ClientDAO {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM client WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}