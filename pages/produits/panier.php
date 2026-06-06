<?php

if ($_SESSION["role"] !== "client") {
    echo "Le panier est réservé aux clients.";
    exit;
}

$panierDAO = new PanierDAO($db);
$produitDAO = new ProduitDAO($db);

$idClient = $_SESSION["user"]["id_client"];
$produits = $panierDAO->getProduitsPanier($idClient);
$reco = $produitDAO->getRandomProduits();
$total = 0;
?>

<main class="container">
    <h1>Mon panier</h1>

    <?php if (empty($produits)): ?>
        <p>Votre panier est vide</p>
    <?php endif; ?>

    <?php foreach ($produits as $p): ?>
        <?php
        $sousTotal = $p["prix"] * $p["quantite"];
        $total += $sousTotal;
        ?>

        <div class="cart-line">
            <h3><?= htmlspecialchars($p["nom_produit"]) ?></h3>

            <p>
                Prix : <?= htmlspecialchars($p["prix"]) ?> €<br>
                Quantité : <?= htmlspecialchars($p["quantite"]) ?><br>
                Sous-total : <?= number_format($sousTotal, 2, ",", " ") ?> €
            </p>

            <a href="index.php?page=remove_panier&id=<?= $p["id_produit"] ?>">Supprimer</a>
            <a href="index.php?page=update_panier&id=<?= $p["id_produit"] ?>&action=moins">-</a>
            <?= htmlspecialchars($p["quantite"]) ?>
            <a href="index.php?page=update_panier&id=<?= $p["id_produit"] ?>&action=plus">+</a>
        </div>
    <?php endforeach; ?>

    <?php if (!empty($produits)): ?>
        <p>
            <a href="index.php?page=clear_panier">Vider le panier</a>
            <a class="button-link" href="index.php?page=confirmer_commande">Confirmer la commande</a>
        </p>
    <?php endif; ?>

    <hr>
    <h2>Total : <?= number_format($total, 2, ",", " ") ?> €</h2>

    <?php if (!empty($reco)): ?>
        <h2>Suggestions</h2>
        <div class="product-grid">
            <?php foreach ($reco as $r): ?>
                <div class="product-card">
                    <h4><?= htmlspecialchars($r["nom_produit"]) ?></h4>
                    <p><?= htmlspecialchars($r["prix"]) ?> €</p>
                    <a href="index.php?page=add_panier&id=<?= $r["id_produit"] ?>">Ajouter</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
