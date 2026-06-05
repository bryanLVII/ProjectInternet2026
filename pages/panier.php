<?php

if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

require_once "dao/PanierDAO.php";
require_once "dao/ProduitDAO.php";

$panierDAO = new PanierDAO($db);
$produitDAO = new ProduitDAO($db);
$reco = $produitDAO->getRandomProduits();

$idClient = $_SESSION["user"]["id_client"];

$produits = $panierDAO->getProduitsPanier($idClient);

$total = 0;
?>

<h1>🛒 Mon panier</h1>

<?php if (empty($produits)): ?>
    <p>Votre panier est vide</p>
<?php endif; ?>

<?php foreach ($produits as $p): ?>

    <?php
    $sousTotal = $p["prix"] * $p["quantite"];
    $total += $sousTotal;
    ?>

    <div style="border:1px solid #ccc; padding:10px; margin:10px;">

        <h3><?= $p["nom_produit"] ?></h3>

        <p>
            Prix : <?= $p["prix"] ?> € <br>
            Quantité : <?= $p["quantite"] ?> <br>
            Sous-total : <?= $sousTotal ?> €
        </p>

        <?php foreach ($reco as $r): ?>

            <div style="border:1px solid #ccc; padding:10px; margin:5px;">
                <h4><?= $r["nom_produit"] ?></h4>
                <p><?= $r["prix"] ?> €</p>
                <a href="index.php?page=add_panier&id=<?= $r["id_produit"] ?>">
                    Ajouter
                </a>
            </div>

        <?php endforeach; ?>

        <a href="index.php?page=remove_panier&id=<?= $p["id_produit"] ?>">
            ❌ Supprimer
        </a>

        <a href="index.php?page=update_panier&id=<?= $p['id_produit'] ?>&action=moins">
            ➖
        </a>

        <?= $p["quantite"] ?>

        <a href="index.php?page=update_panier&id=<?= $p['id_produit'] ?>&action=plus">
            ➕
        </a>

        <a href="index.php?page=clear_panier">
            🗑 Vider le panier
        </a>

    </div>

<?php endforeach; ?>

<hr>

<h2>Total : <?= $total ?> €</h2>