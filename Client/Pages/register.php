<?php
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($nom === "" || $email === "" || $password === "") {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $clientDAO = new ClientDAO($db);
        $existing = $clientDAO->getByEmail($email);

        if ($existing) {
            $error = "Email deja utilise.";
        } else {
            $clientDAO->createClient($nom, $email, password_hash($password, PASSWORD_DEFAULT));

            header("Location: index.php?page=login");
            exit;
        }
    }
}
?>

<main class="container">
    <h1>Creer un compte</h1>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="form-panel">
        <input type="text" name="nom" placeholder="Nom" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>

        <button type="submit">Creer mon compte</button>
    </form>
</main>
