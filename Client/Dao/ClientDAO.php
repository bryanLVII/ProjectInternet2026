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
        $stmt = $this->db->prepare("
            SELECT id_client, nom, email, mot_de_passe, type_client, credits_fidelite
            FROM client
            WHERE email = :email
        ");
        $stmt->execute(["email" => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT id_client, nom, email, mot_de_passe, type_client, credits_fidelite
            FROM client
            WHERE id_client = :id
        ");
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

    public function createClient($nom, $email, $password)
    {
        $stmt = $this->db->prepare("
            INSERT INTO client (nom, email, mot_de_passe, type_client, credits_fidelite)
            VALUES (:nom, :email, :password, 'Particulier', 0)
        ");

        $stmt->execute([
            "nom" => $nom,
            "email" => $email,
            "password" => $password,
        ]);
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
                "password" => password_hash($password, PASSWORD_DEFAULT),
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
        $this->db->beginTransaction();

        try {
            $commandes = $this->db->prepare("
                SELECT id_commande
                FROM commande
                WHERE id_client = :id
            ");
            $commandes->execute(["id" => $id]);
            $ids = $commandes->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($ids)) {
                $in = implode(",", array_fill(0, count($ids), "?"));
                $this->db->prepare("DELETE FROM commande_produit WHERE id_commande IN ($in)")
                    ->execute($ids);
            }

            $this->db->prepare("DELETE FROM avis WHERE id_client = :id")
                ->execute(["id" => $id]);
            $this->db->prepare("DELETE FROM panier_produit WHERE id_panier IN (SELECT id_panier FROM panier WHERE id_client = :id)")
                ->execute(["id" => $id]);
            $this->db->prepare("DELETE FROM panier WHERE id_client = :id")
                ->execute(["id" => $id]);
            $this->db->prepare("DELETE FROM commande WHERE id_client = :id")
                ->execute(["id" => $id]);
            $this->db->prepare("DELETE FROM client WHERE id_client = :id")
                ->execute(["id" => $id]);

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
