<?php

$host = "localhost";
$dbname = "workshop_shop";
$user = "postgres";
$password = "admin";

try {
    $db = new PDO(
        "pgsql:host=$host;dbname=$dbname",
        $user,
        $password
    );

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    die("Erreur connexion DB : " . $e->getMessage());
}