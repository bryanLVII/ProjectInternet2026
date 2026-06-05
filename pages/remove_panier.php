<?php

if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$idClient = $_SESSION["user"]["id_client"];
$idProduit = $_GET["id"];

$sql = "
    DELETE FROM panier_produit
    WHERE id_produit = :prod
    AND id_panier = (
        SELECT id_panier FROM panier WHERE id_client = :client
    )
";

$stmt = $db->prepare($sql);

$stmt->execute([
    "prod" => $idProduit,
    "client" => $idClient
]);

header("Location: index.php?page=panier");
exit;