<?php
if (($_SESSION["role"] ?? "") !== "admin") {
    exit("Acces refuse.");
}

$produitDAO = new ProduitDAO($db);
$categorieDAO = new CategorieDAO($db);
$categories = $categorieDAO->getAllCategories();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $produitDAO->create([
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
    <h1>Ajouter un produit</h1>

    <form method="POST" class="form-panel">
        <input name="nom" placeholder="Nom" required><br>
        <input name="desc" placeholder="Description"><br>
        <input name="prix" placeholder="Prix" type="number" step="0.01" required><br>
        <input name="stock" placeholder="Stock" type="number" required><br>
        <input name="marque" placeholder="Marque"><br>
        <select name="categorie">
            <option value="">Sans categorie</option>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?= $categorie["id_categorie"] ?>">
                    <?= htmlspecialchars($categorie["nom_categorie"]) ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit">Ajouter</button>
    </form>
</main>
