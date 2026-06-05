<?php
require_once "dao/ProduitDAO.php";
require_once "dao/CategorieDAO.php";

$categorieDAO = new CategorieDAO($db);
$produitDAO = new ProduitDAO($db);

$categories = $categorieDAO->getAllCategories();

$categorieChoisie = $_GET["categorie"] ?? null;

$dao = new ProduitDAO($db);

// SEARCH
$search = $_GET["search"] ?? "";

if ($search) {
    $produits = $dao->searchProduits($search);
} else {
    $produits = $dao->getAllProduits();
}

if ($categorieChoisie) {
    $produits = $produitDAO->getProduitsByCategorie(
            $categorieChoisie
    );
} else {
    $produits = $produitDAO->getAllProduits();
}

?>

<h1>🛒 Mon Shop</h1>

<!-- 🔍 SEARCH BAR -->
<form method="GET">
    <input type="hidden" name="page" value="home">
    <input type="text" name="search" placeholder="Rechercher un produit...">
    <button>Rechercher</button>
</form>

<hr>

<h2>Catégories</h2>
<a href="index.php?page=add_panier&id=<?= $p['id_produit'] ?>">
    Ajouter au panier
</a>

<a href="index.php?page=home">
    Tous
</a>

<?php foreach ($categories as $categorie): ?>
    <a href="index.php?page=home&categorie=<?= $categorie["id_categorie"] ?>">
        <?= htmlspecialchars($categorie["nom_categorie"]) ?>
    </a>

<?php endforeach; ?>

<hr>

<!-- 📦 PRODUITS -->
<div style="display:flex; flex-wrap:wrap; gap:15px;">

    <?php foreach ($produits as $p): ?>

        <div style="
        width:200px;
        border:1px solid #ccc;
        padding:10px;
        background:white;
        border-radius:10px;
    ">

            <!-- IMAGE -->
            <?php if (!empty($p["image"])): ?>
                <img src="assets/images/<?= $p["image"] ?>"
                     style="width:100%; height:120px; object-fit:cover;">
            <?php else: ?>
                <div style="height:120px; background:#eee;"></div>
            <?php endif; ?>

            <!-- NOM -->
            <h3>
                <a href="index.php?page=produit&id=<?= $p["id_produit"] ?>">
                    <?= $p["nom_produit"] ?>
                </a>
            </h3>

            <!-- PRIX -->
            <p><b><?= $p["prix"] ?> €</b></p>

            <!-- STOCK -->
            <small>Stock : <?= $p["stock"] ?></small>

        </div>

    <?php endforeach; ?>

</div>