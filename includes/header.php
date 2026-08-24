<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Shop</title>
    <link rel="stylesheet" href="Assets/css/style.css">
</head>
<body>

<header style="background:#222; padding:15px; color:white;">
    <a href="index.php?page=home">Accueil</a>

    <?php if (!isset($_SESSION["user"])): ?>
        <a href="index.php?page=login">Connexion</a>
    <?php else: ?>
        Bonjour <?= htmlspecialchars($_SESSION["user"]["nom"]) ?>

        <?php if (($_SESSION["role"] ?? "") === "client"): ?>
            <a href="index.php?page=panier">Panier</a>
        <?php endif; ?>

        <?php if (($_SESSION["role"] ?? "") === "admin"): ?>
            <a href="index.php?page=admin">Admin</a>
        <?php endif; ?>

        <a href="index.php?page=profil">Profil</a>
        <a href="index.php?page=logout">Deconnexion</a>
    <?php endif; ?>
</header>
