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
    "produit" => "produits/produit.php",
    "panier" => "produits/panier.php",
    "profil" => "profil.php",
    "login" => "login.php",
    "logout" => "logout.php",
    "admin" => "admins/admin.php",
    "confirmer_commande" => "produits/confirmer_commande.php",
    "add_panier" => "produits/add_panier.php",
    "update_panier" => "update_panier.php",
    "remove_panier" => "produits/remove_panier.php",
    "clear_panier" => "produits/clear_panier.php",
    "Ajout_product" => "admins/Ajout_product.php",
    "Modification_product" => "admins/Modification_product.php",
    "Supprimer_product" => "admins/Supprimer_product.php",
    "register" => "register.php",
    "edit_avis" => "produits/edit_avis.php",
    "delete_client" => "admins/delete_client.php",
];

include __DIR__ . "/includes/header.php";

if (isset($routes[$page])) {
    include __DIR__ . "/pages/" . $routes[$page];
} else {
    echo "Page introuvable";
}

include __DIR__ . "/includes/footer.php";
