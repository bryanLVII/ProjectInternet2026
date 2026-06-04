<?php

if ($_SESSION["role"] !== "admin") {
    exit("Accès refusé");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $sql = "INSERT INTO produit (nom_produit, description, prix, stock)
            VALUES (:nom, :desc, :prix, :stock)";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        "nom" => $_POST["nom"],
        "desc" => $_POST["desc"],
        "prix" => $_POST["prix"],
        "stock" => $_POST["stock"]
    ]);

    header("Location: index.php?page=admin");
    exit;
}
?>

<h1>Ajouter produit</h1>

<form method="POST">
    <input name="nom" placeholder="Nom"><br>
    <input name="desc" placeholder="Description"><br>
    <input name="prix" placeholder="Prix"><br>
    <input name="stock" placeholder="Stock"><br>
    <button>Ajouter</button>
</form>