<?php
if (!isset($_SESSION["user"]) || ($_SESSION["role"] ?? "") !== "client") {
    header("Location: index.php?page=login");
    exit;
}

$idProduit = $_GET["id"] ?? null;

if ($idProduit) {
    $panierDAO = new PanierDAO($db);
    $idPanier = $panierDAO->getOrCreatePanier($_SESSION["user"]["id_client"]);
    $panierDAO->addProduct($idPanier, $idProduit);
}

header("Location: index.php?page=panier");
exit;
