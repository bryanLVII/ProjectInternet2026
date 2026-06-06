<?php
session_start();

require_once __DIR__ . "/Configuration/database.php";

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/Dao/$class.php",
        __DIR__ . "/Classes/$class.php",
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$page = $_GET["page"] ?? "home";
$routes = [
    "home" => "Home.php",
    "produit" => "produit.php",
    "panier" => "panier.php",
    "profil" => "profil.php",
    "login" => "login.php",
    "logout" => "logout.php",
    "admin" => "admin.php",
    "confirmer_commande" => "confirmer_commande.php",
    "add_panier" => "add_panier.php",
    "update_panier" => "update_panier.php",
    "remove_panier" => "remove_panier.php",
    "clear_panier" => "clear_panier.php",
    "Ajout_product" => "Ajout_product.php",
    "Modification_product" => "Modification_product.php",
    "Supprimer_product" => "Supprimer_product.php",
    "register" => "register.php",
    "edit_avis" => "edit_avis.php",
];

include __DIR__ . "/includes/header.php";

if (isset($routes[$page])) {
    include __DIR__ . "/pages/" . $routes[$page];
} else {
    echo "Page introuvable";
}

include __DIR__ . "/includes/footer.php";
