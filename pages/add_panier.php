<?php

if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

require_once "dao/PanierDAO.php";

$dao = new PanierDAO($db);

$idClient = $_SESSION["user"]["id_client"];
$idProduit = $_GET["id"];

$panier = $dao->getPanierClient($idClient);

if (!$panier) {

    $idPanier = $dao->creerPanier($idClient);

} else {

    $idPanier = $panier["id_panier"];
}

$dao->ajouterProduit(
    $idPanier,
    $idProduit
);

header("Location: index.php?page=panier");
exit;