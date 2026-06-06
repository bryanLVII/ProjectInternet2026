<?php

if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$role = $_SESSION["role"];

$clientDAO = new ClientDAO($db);
$adminDAO = new AdministrateurDAO($db);

$message = "";
$error = "";

/* =========================
   ID SELON ROLE
========================= */
if ($role === "client") {
    $id = $_SESSION["user"]["id_client"];
    $user = $clientDAO->getById($id);

} elseif ($role === "admin") {
    $id = $_SESSION["user"]["id_admin"];
    $user = $adminDAO->getById($id);

} else {
    header("Location: index.php?page=home");
    exit;
}

/* =========================
   UPDATE PROFIL (COMMUN)
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($nom === "" || $email === "") {
        $error = "Nom et email obligatoires.";
    } else {

        if ($role === "client") {
            $clientDAO->updateProfile($id, $nom, $email, $password);
            $_SESSION["user"] = $clientDAO->getById($id);
            $user = $_SESSION["user"];

        } elseif ($role === "admin") {
            $adminDAO->updateProfile($id, $nom, $email, $password);
            $_SESSION["user"] = $adminDAO->getById($id);
            $user = $_SESSION["user"];
        }

        $message = "Profil mis à jour.";
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

    <!-- =========================
         FORMULAIRE COMMUN
    ========================= -->
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
            <p>Crédits fidélité : <?= htmlspecialchars($user["credits_fidelite"]) ?></p>
        <?php endif; ?>

        <?php if ($role === "admin"): ?>
            <p>Rôle : <?= htmlspecialchars($user["role"]) ?></p>
        <?php endif; ?>

        <button type="submit">Enregistrer</button>

    </form>

    <!-- =========================
         CLIENT : COMMANDES
    ========================= -->
    <?php if ($role === "client"): ?>

        <?php
        $commandeDAO = new CommandeDAO($db);
        $commandes = $commandeDAO->getByClient($id);
        ?>

        <h2>Mes commandes</h2>

        <?php if (empty($commandes)): ?>
            <p>Aucune commande pour le moment.</p>
        <?php endif; ?>

        <?php foreach ($commandes as $commande): ?>
            <div class="admin-line">
                <strong>Commande #<?= $commande["id_commande"] ?></strong><br>
                Date : <?= $commande["date_commande"] ?><br>
                Statut : <?= $commande["statut"] ?><br>
                Total : <?= number_format($commande["total"], 2, ",", " ") ?> €
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

    <!-- =========================
         ADMIN : CLIENTS
    ========================= -->
    <?php if ($role === "admin"): ?>

        <?php
        $clients = $clientDAO->getAll();
        ?>

        <h2>Gestion des clients</h2>

        <div id="client-list">

            <?php foreach ($clients as $c): ?>

                <div class="admin-line" id="client-<?= $c["id_client"] ?>">

                    <strong><?= htmlspecialchars($c["nom"]) ?></strong><br>
                    Email : <?= htmlspecialchars($c["email"]) ?><br>
                    Type : <?= htmlspecialchars($c["type_client"]) ?><br>
                    Crédits : <?= htmlspecialchars($c["credits_fidelite"]) ?><br>

                    <button class="delete-client" data-id="<?= $c["id_client"] ?>">
                        Supprimer
                    </button>

                </div>

            <?php endforeach; ?>

        </div>

        <script src="assets/js/admin_clients.js"></script>

    <?php endif; ?>

</main>