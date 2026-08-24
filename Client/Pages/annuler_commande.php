<?php
if (!isset($_SESSION["user"]) || ($_SESSION["role"] ?? "") !== "client") {
    header("Location: index.php?page=login");
    exit;
}

$idCommande = $_GET["id"] ?? null;

if ($idCommande) {
    $commandeDAO = new CommandeDAO($db);
    $commandeDAO->cancelForClient($idCommande, $_SESSION["user"]["id_client"]);
}

header("Location: index.php?page=commande&id=" . urlencode((string)$idCommande));
exit;
