<?php
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($nom === "" || $email === "" || $password === "") {
        $error = "Tous les champs sont obligatoires.";
    } else {

        $clientDAO = new ClientDAO($db);

        // vérifier si email existe déjà
        $existing = $clientDAO->getByEmail($email);

        if ($existing) {
            $error = "Email déjà utilisé.";
        } else {

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $clientDAO->createClient($nom, $email, $passwordHash);

            $success = "Compte créé ! Vous pouvez vous connecter.";

            header("Location: index.php?page=login");
            exit;
        }
    }
}
?>

<main class="container">

    <h1>Créer un compte</h1>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="nom" placeholder="Nom" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>

        <button type="submit">Créer mon compte</button>

    </form>

</main>