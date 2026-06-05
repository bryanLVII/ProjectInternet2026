<?php
if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$clientDAO = new ClientDAO($db);
$commandeDAO = new CommandeDAO($db);

$idClient = $_SESSION["user"]["id_client"];
$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($nom === "" || $email === "") {
        $error = "Le nom et l'email sont obligatoires.";
    } else {
        $clientDAO->updateProfile($idClient, $nom, $email, $password);
        $_SESSION["user"] = $clientDAO->getById($idClient);
        $message = "Profil mis à jour.";
    }
}

$client = $clientDAO->getById($idClient);
$commandes = $commandeDAO->getByClient($idClient);
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
            <input name="nom" value="<?= htmlspecialchars($client["nom"]) ?>" required>
        </label><br>

        <label>
            Email<br>
            <input type="email" name="email" value="<?= htmlspecialchars($client["email"]) ?>" required>
        </label><br>

        <label>
            Nouveau mot de passe<br>
            <input type="password" name="password" placeholder="Laisser vide pour ne pas changer">
        </label><br>

        <p>Type de compte : <?= htmlspecialchars($client["type_client"]) ?></p>
        <p>Crédits fidélité : <?= htmlspecialchars($client["credits_fidelite"]) ?></p>

        <button type="submit">Enregistrer</button>
    </form>

    <h2>Mes commandes</h2>

    <?php if (empty($commandes)): ?>
        <p>Aucune commande pour le moment.</p>
    <?php endif; ?>

    <?php foreach ($commandes as $commande): ?>
        <div class="admin-line">
            <strong>Commande #<?= htmlspecialchars($commande["id_commande"]) ?></strong><br>
            Date : <?= htmlspecialchars($commande["date_commande"]) ?><br>
            Statut : <?= htmlspecialchars($commande["statut"]) ?><br>
            Total : <?= number_format($commande["total"], 2, ",", " ") ?> €
        </div>
    <?php endforeach; ?>
</main>
