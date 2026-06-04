<?php
session_start();

spl_autoload_register(function($class){
    require_once "classes/" . $class . ".php";
});

require_once "configuration/database.php";

$page = $_GET['page'] ?? 'home';

include "includes/header.php";

$file = "pages/" . $page . ".php";

if (file_exists($file)) {
    include $file;
} else {
    echo "Page introuvable";
}

include "includes/footer.php";