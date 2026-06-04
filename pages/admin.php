<?php

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo "Accès refusé";
    exit;
}

require_once "dao/ProduitDAO.php";

$dao = new ProduitDAO($db);
$produits = $dao->getAllProduits();
?>

    <h1>Admin Panel</h1>

    <a href="index.php?page=Ajout_product">Ajouter produit</a>

<?php foreach ($produits as $p): ?>

    <div style="border:1px solid black; margin:10px; padding:10px;">
        <h3><?= $p["nom_produit"] ?></h3>

        <a href="index.php?page=Modification_product&id=<?= $p["id_produit"] ?>">Modifier</a>
        <a href="index.php?page=Supprimer_product&id=<?= $p["id_produit"] ?>">Supprimer</a>
    </div>

<?php endforeach; ?>