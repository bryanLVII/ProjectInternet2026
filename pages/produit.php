<?php
$dao = new ProduitDAO($db);
$id = $_GET["id"] ?? null;

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

<main class="container">
    <h1><?= htmlspecialchars($produit["nom_produit"]) ?></h1>

    <p><?= htmlspecialchars($produit["description"] ?? "") ?></p>
    <p><b><?= htmlspecialchars($produit["prix"]) ?> €</b></p>
    <p>Stock : <?= htmlspecialchars($produit["stock"]) ?></p>
    <p>Marque : <?= htmlspecialchars($produit["marque"] ?? "") ?></p>
    <?php if (($_SESSION["role"] ?? "") === "client"): ?>
        <p>
            <a href="index.php?page=add_panier&id=<?= $produit["id_produit"] ?>">
                Ajouter au panier
            </a>
        </p>
    <?php endif; ?>
</main>