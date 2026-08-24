<?php
$categorieDAO = new CategorieDAO($db);
$produitDAO = new ProduitDAO($db);

$categories = $categorieDAO->getAllCategories();
$categorieChoisie = $_GET["categorie"] ?? null;
$search = trim($_GET["search"] ?? "");

if ($categorieChoisie) {
    $produits = $produitDAO->getProduitsByCategorie($categorieChoisie);
} elseif ($search !== "") {
    $produits = $produitDAO->searchProduits($search);
} else {
    $produits = $produitDAO->getAllProduits();
}
?>

<main class="container">
    <h1>Mon Shop</h1>

    <form method="GET">
        <input type="hidden" name="page" value="home">
        <input type="text" name="search" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Rechercher</button>
    </form>

    <hr>

    <h2>Categories</h2>
    <a href="index.php?page=home">Tous</a>

    <?php foreach ($categories as $categorie): ?>
        <a href="index.php?page=home&categorie=<?= $categorie["id_categorie"] ?>">
            <?= htmlspecialchars($categorie["nom_categorie"]) ?>
        </a>
    <?php endforeach; ?>

    <hr>

    <div class="product-grid">
        <?php foreach ($produits as $p): ?>
            <div class="product-card">
                <?php if (!empty($p["image"])): ?>
                    <img src="Assets/images/<?= htmlspecialchars($p["image"]) ?>" alt="<?= htmlspecialchars($p["nom_produit"]) ?>">
                <?php else: ?>
                    <div class="product-placeholder"></div>
                <?php endif; ?>

                <h3>
                    <a href="index.php?page=produit&id=<?= $p["id_produit"] ?>">
                        <?= htmlspecialchars($p["nom_produit"]) ?>
                    </a>
                </h3>

                <p><b><?= htmlspecialchars($p["prix"]) ?> EUR</b></p>
                <small>Stock : <?= htmlspecialchars($p["stock"]) ?></small>

                <?php if (($_SESSION["role"] ?? "") !== "admin"): ?>
                    <p>
                        <a href="index.php?page=add_panier&id=<?= $p["id_produit"] ?>">
                            Ajouter au panier
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>
