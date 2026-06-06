<?php

if (($_SESSION["role"] ?? "") !== "admin") {
    header("Location: index.php?page=home");
    exit;
}

$avisDAO = new AvisDAO($db);

$id = $_GET["id"] ?? null;

if ($id) {
    $avisDAO->deleteAvis($id);
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
exit;