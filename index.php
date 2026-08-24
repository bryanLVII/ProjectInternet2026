<?php
session_start();

require_once __DIR__ . "/Configuration/database.php";

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/Admin/Dao/$class.php",
        __DIR__ . "/Client/Dao/$class.php",
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
    "panier" => "../Client/Pages/panier.php",
    "profil" => "../Client/Pages/profil.php",
    "login" => "../Client/Pages/login.php",
    "logout" => "logout.php",
    "admin" => "../Admin/Pages/dashboard.php",
    "admin_clients" => "../Admin/Pages/clients.php",
    "delete_client" => "../Admin/Pages/delete_client.php",
    "confirmer_commande" => "../Client/Pages/confirmer_commande.php",
    "commande" => "../Client/Pages/commande.php",
    "annuler_commande" => "../Client/Pages/annuler_commande.php",
    "add_panier" => "../Client/Pages/add_panier.php",
    "update_panier" => "../Client/Pages/update_panier.php",
    "remove_panier" => "../Client/Pages/remove_panier.php",
    "clear_panier" => "../Client/Pages/clear_panier.php",
    "Ajout_product" => "../Admin/Pages/ajout_product.php",
    "Modification_product" => "../Admin/Pages/modification_product.php",
    "Supprimer_product" => "../Admin/Pages/supprimer_product.php",
    "register" => "../Client/Pages/register.php",
    "edit_avis" => "../Client/Pages/edit_avis.php",
    "supprimer_avis" => "../Admin/Pages/supprimer_avis.php",
];

include __DIR__ . "/includes/header.php";

if (isset($routes[$page])) {
    include __DIR__ . "/pages/" . $routes[$page];
} else {
    echo "Page introuvable";
}

include __DIR__ . "/includes/footer.php";
