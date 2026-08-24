<?php
if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$role = $_SESSION["role"] ?? "";
$message = "";
$error = "";
$commandes = [];

if ($role === "admin") {
    $adminDAO = new AdministrateurDAO($db);
    $id = $_SESSION["user"]["id_admin"];
    $user = $adminDAO->getById($id);
} elseif ($role === "client") {
    $clientDAO = new ClientDAO($db);
    $commandeDAO = new CommandeDAO($db);
    $id = $_SESSION["user"]["id_client"];
    $user = $clientDAO->getById($id);
    $commandes = $commandeDAO->getByClient($id);
} else {
    header("Location: index.php?page=home");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($nom === "" || $email === "") {
        $error = "Le nom et l'email sont obligatoires.";
    } elseif ($role === "admin") {
        $adminDAO->updateProfile($id, $nom, $email, $password);
        $_SESSION["user"] = $adminDAO->getById($id);
        $user = $_SESSION["user"];
        $message = "Profil mis a jour.";
    } else {
        $clientDAO->updateProfile($id, $nom, $email, $password);
        $_SESSION["user"] = $clientDAO->getById($id);
        $user = $_SESSION["user"];
        $message = "Profil mis a jour.";
    }
}
?>

<main class="container">
    <h1>Mon profil</h1>

    <?php if ($message): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="form-panel">
        <label>
            Nom<br>
            <input name="nom" value="<?= htmlspecialchars($user["nom"]) ?>" required>
        </label><br>

        <label>
            Email<br>
            <input type="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required>
        </label><br>

        <label>
            Nouveau mot de passe<br>
            <input type="password" name="password" placeholder="Laisser vide">
        </label><br>

        <?php if ($role === "client"): ?>
            <p>Type de compte : <?= htmlspecialchars($user["type_client"]) ?></p>
            <p>Credits fidelite : <?= htmlspecialchars($user["credits_fidelite"]) ?></p>
        <?php else: ?>
            <p>Role : <?= htmlspecialchars($user["role"]) ?></p>
        <?php endif; ?>

        <button type="submit">Enregistrer</button>
    </form>

    <?php if ($role === "client"): ?>
        <h2>Mes commandes</h2>

        <?php if (empty($commandes)): ?>
            <p>Aucune commande pour le moment.</p>
        <?php endif; ?>

        <?php foreach ($commandes as $commande): ?>
            <div class="admin-line">
                <strong>
                    <a href="index.php?page=commande&id=<?= $commande["id_commande"] ?>">
                        Commande #<?= htmlspecialchars($commande["id_commande"]) ?>
                    </a>
                </strong><br>
                Date : <?= htmlspecialchars($commande["date_commande"]) ?><br>
                Statut : <?= htmlspecialchars($commande["statut"]) ?><br>
                Total : <?= number_format($commande["total"], 2, ",", " ") ?> EUR
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
