<?php

if ($_SESSION["role"] !== "admin") {
    exit("Accès refusé");
}

$id = $_GET["id"];

$sql = "DELETE FROM produit WHERE id_produit = :id";
$stmt = $db->prepare($sql);
$stmt->execute(["id" => $id]);

header("Location: index.php?page=admin");
exit;