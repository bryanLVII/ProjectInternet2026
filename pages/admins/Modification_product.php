<?php
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    exit("Accès refusé");
}

$produitDAO = new ProduitDAO($db);
$categorieDAO = new CategorieDAO($db);
$categories = $categorieDAO->getAllCategories();

$id = $_GET["id"] ?? null;

if (!$id) {
    exit("Produit introuvable");
}

$produit = $produitDAO->getProduitById($id);

if (!$produit) {
    exit("Produit introuvable");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $produitDAO->update($id, [
        "nom" => $_POST["nom"] ?? "",
        "description" => $_POST["desc"] ?? "",
        "prix" => $_POST["prix"] ?? 0,
        "stock" => $_POST["stock"] ?? 0,
        "marque" => $_POST["marque"] ?? "",
        "categorie" => $_POST["categorie"] ?? "",
    ]);

    header("Location: index.php?page=admin");
    exit;
}
?>

<main class="container">
    <h1>Modifier produit</h1>

    <form method="POST">
        <input name="nom" value="<?= htmlspecialchars($produit["nom_produit"]) ?>" required><br>
        <input name="desc" value="<?= htmlspecialchars($produit["description"] ?? "") ?>"><br>
        <input name="prix" value="<?= htmlspecialchars($produit["prix"]) ?>" type="number" step="0.01" required><br>
        <input name="stock" value="<?= htmlspecialchars($produit["stock"]) ?>" type="number" required><br>
        <input name="marque" value="<?= htmlspecialchars($produit["marque"] ?? "") ?>" placeholder="Marque"><br>
        <select name="categorie">
            <option value="">Sans catégorie</option>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?= $categorie["id_categorie"] ?>" <?= (string)$categorie["id_categorie"] === (string)($produit["id_categorie"] ?? "") ? "selected" : "" ?>>
                    <?= htmlspecialchars($categorie["nom_categorie"]) ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <button>Modifier</button>
    </form>
</main>
