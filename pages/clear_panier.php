<?php
if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$panierDAO = new PanierDAO($db);
$panierDAO->clear($_SESSION["user"]["id_client"]);

header("Location: index.php?page=panier");
exit;
