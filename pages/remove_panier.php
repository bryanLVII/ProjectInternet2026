<?php
if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$panierDAO = new PanierDAO($db);

$idClient = $_SESSION["user"]["id_client"];
$idProduit = $_GET["id"] ?? null;

if ($idProduit) {
    $panierDAO->removeProduct($idClient, $idProduit);
}

header("Location: index.php?page=panier");
exit;
