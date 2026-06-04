<!DOCTYPE html>
<html>
<head>
    <title>Mon Shop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<header style="background:#222; padding:15px; color:white;">

    <a href="index.php?page=home" style="color:white; margin-right:10px;">Accueil</a>
    <a href="index.php?page=home" style="color:white; margin-right:10px;">Produits</a>

    <?php if (!isset($_SESSION["user"])): ?>
        <a href="index.php?page=login" style="color:lightgreen;">Connexion</a>
    <?php else: ?>
        <span style="margin-left:10px;">
            Bonjour <?= $_SESSION["user"]["nom"] ?>
        </span>

        <a href="index.php?page=logout" style="color:red; margin-left:10px;">Logout</a>

        <?php if ($_SESSION["role"] === "admin"): ?>
            <a href="index.php?page=admin" style="color:orange; margin-left:10px;">Admin</a>
        <?php endif; ?>
    <?php endif; ?>

</header>

<hr>
