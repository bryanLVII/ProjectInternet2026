<?php
if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$panierDAO = new PanierDAO($db);

$idClient = $_SESSION["user"]["id_client"];
$idProduit = $_GET["id"] ?? null;
$action = $_GET["action"] ?? "";

if ($idProduit && in_array($action, ["plus", "moins"], true)) {
    $panierDAO->updateQuantity($idClient, $idProduit, $action);
}

header("Location: index.php?page=panier");
exit;
