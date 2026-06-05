<?php

if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$idClient = $_SESSION["user"]["id_client"];

$sql = "
    DELETE FROM panier_produit
    WHERE id_panier = (
        SELECT id_panier FROM panier WHERE id_client = :client
    )
";

$stmt = $db->prepare($sql);
$stmt->execute(["client" => $idClient]);

header("Location: index.php?page=panier");
exit;