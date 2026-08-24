<?php
if (!isset($_SESSION["user"]) || ($_SESSION["role"] ?? "") !== "client") {
    header("Location: index.php?page=login");
    exit;
}

$idCommande = $_GET["id"] ?? null;
$commandeDAO = new CommandeDAO($db);
$commande = $idCommande
    ? $commandeDAO->getByIdForClient($idCommande, $_SESSION["user"]["id_client"])
    : null;

if (!$commande) {
    echo "Commande introuvable.";
    exit;
}

$lignes = $commandeDAO->getLines($commande["id_commande"]);
?>

<main class="container">
    <h1>Commande #<?= htmlspecialchars($commande["id_commande"]) ?></h1>

    <p>Date : <?= htmlspecialchars($commande["date_commande"]) ?></p>
    <p>Statut : <?= htmlspecialchars($commande["statut"]) ?></p>
    <p>Total : <?= number_format($commande["total"], 2, ",", " ") ?> EUR</p>

    <h2>Articles achetes</h2>

    <?php foreach ($lignes as $ligne): ?>
        <div class="cart-line">
            <strong><?= htmlspecialchars($ligne["nom_produit"]) ?></strong><br>
            Quantite : <?= htmlspecialchars($ligne["quantite"]) ?><br>
            Prix unitaire : <?= htmlspecialchars($ligne["prix_unitaire"]) ?> EUR<br>
            Total ligne : <?= number_format($ligne["prix_unitaire"] * $ligne["quantite"], 2, ",", " ") ?> EUR
        </div>
    <?php endforeach; ?>

    <?php if ($commande["statut"] !== "annulee"): ?>
        <p>
            <a class="button-link danger-link"
               href="index.php?page=annuler_commande&id=<?= $commande["id_commande"] ?>"
               onclick="return confirm('Annuler cette commande ?')">
                Annuler la commande
            </a>
        </p>
    <?php endif; ?>

    <p><a href="index.php?page=profil">Retour au profil</a></p>
</main>
