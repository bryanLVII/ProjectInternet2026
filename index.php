<?php
session_start();

/* AUTOLOAD DES CLASSES */
spl_autoload_register(function($class){
    require_once "classes/" . $class . ".php";
});

/* CONNEXION DB */
require_once "configuration/database.php";

/* ROUTING SIMPLE */
$page = $_GET['page'] ?? 'home';

$file = "Pages/" . $page . ".php";
// echo $file;  //debug pour trouver la page

if (file_exists($file)) {
    require $file;
} else {
    echo "Page introuvable";
}