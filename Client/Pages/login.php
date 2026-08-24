<?php
$error = "";
$clientDAO = new ClientDAO($db);
$adminDAO = new AdministrateurDAO($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $client = $clientDAO->getByEmail($email);

    if ($client && password_verify($password, $client["mot_de_passe"])) {
        $_SESSION["user"] = $client;
        $_SESSION["role"] = "client";

        header("Location: index.php?page=home");
        exit;
    }

    $admin = $adminDAO->getByEmail($email);

    if ($admin && password_verify($password, $admin["mot_de_passe"])) {
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

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="form-panel">
        <label>
            Email<br>
            <input type="email" name="email" required>
        </label><br>

        <label>
            Mot de passe<br>
            <input type="password" name="password" required>
        </label><br>

        <button type="submit">Se connecter</button>
    </form>

    <p>
        Pas de compte ?
        <a href="index.php?page=register">Creer un compte</a>
    </p>
</main>
