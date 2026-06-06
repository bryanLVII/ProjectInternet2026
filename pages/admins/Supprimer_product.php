<?php
if (($_SESSION["role"] ?? "") !== "admin") {
    exit("Accès refusé");
}

$id = $_GET["id"] ?? null;

if ($id) {
    $produitDAO = new ProduitDAO($db);
    $produitDAO->delete($id);
}

header("Location: index.php?page=admin");
exit;
