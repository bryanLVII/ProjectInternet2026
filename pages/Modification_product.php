<?php

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    exit("Accès refusé");
}

require_once "dao/ProduitDAO.php";

$dao = new ProduitDAO($db);

$id = $_GET["id"] ?? null;

if (!$id) {
    exit("Produit introuvable");
}

$produit = $dao->getProduitById($id);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $sql = "UPDATE produit 
            SET nom_produit = :nom,
                description = :desc,
                prix = :prix,
                stock = :stock
            WHERE id_produit = :id";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        "nom" => $_POST["nom"],
        "desc" => $_POST["desc"],
        "prix" => $_POST["prix"],
        "stock" => $_POST["stock"],
        "id" => $id
    ]);

    header("Location: index.php?page=admin");
    exit;
}

?>

<h1>Modifier produit</h1>

<form method="POST">

    <input name="nom" value="<?= $produit["nom_produit"] ?>"><br>
    <input name="desc" value="<?= $produit["description"] ?>"><br>
    <input name="prix" value="<?= $produit["prix"] ?>"><br>
    <input name="stock" value="<?= $produit["stock"] ?>"><br>

    <button>Modifier</button>
</form>
