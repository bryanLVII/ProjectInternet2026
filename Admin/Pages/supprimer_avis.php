<?php
if (($_SESSION["role"] ?? "") !== "admin") {
    header("Location: index.php?page=home");
    exit;
}

$idAvis = $_GET["id"] ?? null;
$idProduit = $_GET["produit"] ?? null;

if ($idAvis) {
    $avisDAO = new AvisDAO($db);
    $avis = $avisDAO->getAvisById($idAvis);
    $avisDAO->deleteAvis($idAvis);
    $idProduit = $idProduit ?: ($avis["id_produit"] ?? null);
}

header("Location: index.php?page=produit&id=" . urlencode((string)$idProduit));
exit;
