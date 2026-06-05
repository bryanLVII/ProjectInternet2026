<?php

if (!isset($_SESSION["user"])) {

    header("Location: index.php?page=login");
    exit;
}

$idProduit = $_GET["id"];

echo "Produit ajouté au panier";