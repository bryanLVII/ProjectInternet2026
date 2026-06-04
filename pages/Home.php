<?php
require_once "dao/ProduitDAO.php";

$dao = new ProduitDAO($db);
$produits = $dao->getAllProduits();
?>

    <h1>Catalogue produits</h1>

<?php foreach ($produits as $p): ?>

    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <a href="index.php?page=login">Connexion</a>
        <a href="index.php?page=produit&id=<?= $p['id_produit'] ?>">
            <h3><?= $p['nom_produit'] ?></h3>
        </a>

        <p><?= $p['description'] ?></p>
        <p><b><?= $p['prix'] ?> €</b></p>
        <p>Stock : <?= $p['stock'] ?></p>

    </div>

<?php endforeach; ?>