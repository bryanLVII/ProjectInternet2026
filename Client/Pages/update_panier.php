<?php
if (!isset($_SESSION["user"]) || ($_SESSION["role"] ?? "") !== "client") {
    header("Location: index.php?page=login");
    exit;
}

$idProduit = $_GET["id"] ?? null;
$action = $_GET["action"] ?? "";

if ($idProduit && in_array($action, ["plus", "moins"], true)) {
    $panierDAO = new PanierDAO($db);
    $panierDAO->updateQuantity($_SESSION["user"]["id_client"], $idProduit, $action);
}

header("Location: index.php?page=panier");
exit;
