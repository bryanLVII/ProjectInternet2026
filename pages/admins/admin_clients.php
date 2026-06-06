<?php

if (!isset($_SESSION["user"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php?page=home");
    exit;
}

$clientDAO = new ClientDAO($db);
$clients = $clientDAO->getAll();
?>

<main class="container">

    <h1>Gestion des clients</h1>

    <div id="client-list">

        <?php foreach ($clients as $c): ?>
            <div class="admin-line" id="client-<?= $c["id_client"] ?>">

                <strong><?= htmlspecialchars($c["nom"]) ?></strong><br>
                Email : <?= htmlspecialchars($c["email"]) ?><br>
                Type : <?= htmlspecialchars($c["type_client"]) ?><br>
                Crédits : <?= htmlspecialchars($c["credits_fidelite"]) ?><br>

                <button class="delete-client"
                        data-id="<?= $c["id_client"] ?>">
                    Supprimer
                </button>

            </div>
        <?php endforeach; ?>

    </div>

</main>

<script src="assets/js/admin_clients.js"></script>