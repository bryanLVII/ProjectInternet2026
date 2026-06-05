<?php
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo "Accès refusé";
    exit;
}

$dao = new ProduitDAO($db);
$produits = $dao->getAllProduits();
?>

<main class="container">
    <h1>Admin Panel</h1>

    <a href="index.php?page=Ajout_product">Ajouter produit</a>

    <?php foreach ($produits as $p): ?>
        <div class="admin-line">
            <h3><?= htmlspecialchars($p["nom_produit"]) ?></h3>

            <a href="index.php?page=Modification_product&id=<?= $p["id_produit"] ?>">Modifier</a>
            <a href="index.php?page=Supprimer_product&id=<?= $p["id_produit"] ?>">Supprimer</a>
        </div>
    <?php endforeach; ?>
</main>
