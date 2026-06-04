<?php

require_once "dao/ProduitDAO.php";

$dao = new ProduitDAO($db);

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Produit introuvable";
    exit;
}

$produit = $dao->getProduitById($id);

if (!$produit) {
    echo "Produit inexistant";
    exit;
}
?>

<h1><?= $produit['nom_produit'] ?></h1>

<p><?= $produit['description'] ?></p>
<p><b><?= $produit['prix'] ?> €</b></p>
<p>Stock : <?= $produit['stock'] ?></p>
<p>Marque : <?= $produit['marque'] ?></p>