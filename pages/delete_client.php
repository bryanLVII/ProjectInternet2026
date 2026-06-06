<?php

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    exit("Unauthorized");
}

$clientDAO = new ClientDAO($db);

$id = $_GET["id"] ?? null;

if ($id) {
    $clientDAO->delete($id);
}

exit;