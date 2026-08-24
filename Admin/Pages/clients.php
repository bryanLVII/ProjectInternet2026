<?php
if (($_SESSION["role"] ?? "") !== "admin") {
    header("Location: index.php?page=home");
    exit;
}

$clientDAO = new ClientDAO($db);
$clients = $clientDAO->getAll();
?>

<main class="container">
    <h1>Gestion des clients</h1>

    <p><a href="index.php?page=admin">Retour administration</a></p>

    <?php if (empty($clients)): ?>
        <p>Aucun client inscrit.</p>
    <?php endif; ?>

    <?php foreach ($clients as $client): ?>
        <div class="admin-line">
            <strong><?= htmlspecialchars($client["nom"]) ?></strong><br>
            Email : <?= htmlspecialchars($client["email"]) ?><br>
            Type : <?= htmlspecialchars($client["type_client"]) ?><br>
            Credits : <?= htmlspecialchars($client["credits_fidelite"]) ?><br>

            <a class="button-link danger-link"
               href="index.php?page=delete_client&id=<?= $client["id_client"] ?>"
               onclick="return confirm('Supprimer ce client ?')">
                Supprimer
            </a>
        </div>
    <?php endforeach; ?>
</main>
