<?php
if (!isset($_SESSION["user"]) || ($_SESSION["role"] ?? "") !== "client") {
    header("Location: index.php?page=login");
    exit;
}

$idProduit = $_GET["id"] ?? null;

if ($idProduit) {
    $panierDAO = new PanierDAO($db);
    $panierDAO->removeProduct($_SESSION["user"]["id_client"], $idProduit);
}

header("Location: index.php?page=panier");
exit;
