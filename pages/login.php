<?php

$error = "";

// DAO
$clientDAO = new ClientDAO($db);
$adminDAO = new AdministrateurDAO($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    // 1. Cherche client
    $user = $clientDAO->getByEmail($email);

    if ($user && $user["mot_de_passe"] === $password) {

        $_SESSION["user"] = $user;
        $_SESSION["role"] = "client";

        header("Location: index.php?page=home");
        exit;
    }

    // 2. Cherche admin
    $admin = $adminDAO->getByEmail($email);

    if ($admin && $admin["mot_de_passe"] === $password) {

        $_SESSION["user"] = $admin;
        $_SESSION["role"] = "admin";

        header("Location: index.php?page=admin");
        exit;
    }

    $error = "Email ou mot de passe incorrect.";
}
?>

<main class="container">

    <h1>Connexion</h1>

    <form method="POST">

        <input type="email" name="email" placeholder="Email" required><br>

        <input type="password" name="password" placeholder="Mot de passe" required><br>

        <button type="submit">Se connecter</button>

    </form>

    <p>
        Pas de compte ?
        <a href="index.php?page=register">Créer un compte</a>
    </p>

    <p style="color:red;">
        <?= htmlspecialchars($error) ?>
    </p>

</main>
