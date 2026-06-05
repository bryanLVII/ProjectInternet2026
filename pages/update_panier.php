<?php

if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$idClient = $_SESSION["user"]["id_client"];
$idProduit = $_GET["id"];
$action = $_GET["action"]; // plus ou moins

$sql = "
    SELECT id_panier FROM panier WHERE id_client = :client
";

$stmt = $db->prepare($sql);
$stmt->execute(["client" => $idClient]);
$panier = $stmt->fetch(PDO::FETCH_ASSOC);

$idPanier = $panier["id_panier"];

if ($action == "plus") {

    $sql = "
        UPDATE panier_produit
        SET quantite = quantite + 1
        WHERE id_panier = :panier
        AND id_produit = :produit
    ";

} else {

    $sql = "
        UPDATE panier_produit
        SET quantite = GREATEST(quantite - 1, 1)
        WHERE id_panier = :panier
        AND id_produit = :produit
    ";
}

$stmt = $db->prepare($sql);

$stmt->execute([
    "panier" => $idPanier,
    "produit" => $idProduit
]);

header("Location: index.php?page=panier");
exit;