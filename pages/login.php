<?php
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    $dao = new ClientDAO($db);
    $user = $dao->getByEmail($email);

    if ($user && $user["mot_de_passe"] === $password) {
        $_SESSION["user"] = $user;
        $_SESSION["role"] = strtolower($user["type_client"]) === "admin" ? "admin" : "client";

        header("Location: index.php?page=home");
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

    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
</main>
