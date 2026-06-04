<?php

class Produit {

    public $id_produit;
    public $nom_produit;
    public $prix;

    public function __construct($data = []) {
        if ($data) {
            $this->id_produit = $data['id_produit'] ?? null;
            $this->nom_produit = $data['nom_produit'] ?? "";
            $this->prix = $data['prix'] ?? 0;
        }
    }
}