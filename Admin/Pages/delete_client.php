<?php
if (($_SESSION["role"] ?? "") !== "admin") {
    echo "Acces refuse.";
    exit;
}

$id = $_GET["id"] ?? null;

if ($id) {
    $clientDAO = new ClientDAO($db);
    $clientDAO->delete($id);
}

header("Location: index.php?page=admin_clients");
exit;
