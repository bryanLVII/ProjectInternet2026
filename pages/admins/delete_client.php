<?php
require_once __DIR__ . "/../../Configuration/database.php";

if (!isset($_SESSION["user"]) || ($_SESSION["role"] ?? "") !== "admin") {
    http_response_code(403);
    exit("interdit");
}

$id = $_GET["id"] ?? null;

if (!$id) {
    http_response_code(400);
    exit("NO_ID");
}

$clientDAO = new ClientDAO($db);

try {
    $clientDAO->delete($id);
    echo "OK";
} catch (Exception $e) {
    http_response_code(500);
    echo "erreur";
}