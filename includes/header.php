<!DOCTYPE html>
<html>
<head>
    <title>Mon Shop</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<header style="background:#222; padding:15px; color:white; display:flex; gap:15px; align-items:center;">

    <a href="index.php?page=home" style="color:white;">Accueil</a>

    <a href="index.php?page=home" style="color:white;">Produits</a>

    <?php if (!isset($_SESSION["user"])): ?>

        <a href="index.php?page=login" style="color:lightgreen;">Connexion</a>

    <?php else: ?>

        <span>
            Bonjour <?= htmlspecialchars($_SESSION["user"]["nom"]) ?>
        </span>

        <?php if ($_SESSION["role"] === "admin"): ?>
            <a href="index.php?page=admin" style="color:orange;">Admin</a>
        <?php endif; ?>

        <a href="index.php?page=logout" style="color:red;">Logout</a>

    <?php endif; ?>

</header>

<hr>
