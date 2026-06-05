<?php
if (!isset($_SESSION["user"])) {
    header("Location: index.php?page=login");
    exit;
}

$panierDAO = new PanierDAO($db);
$commandeDAO = new CommandeDAO($db);

$idClient = $_SESSION["user"]["id_client"];
$produits = $panierDAO->getProduitsPanier($idClient);

if (empty($produits)) {
    header("Location: index.php?page=panier");
    exit;
}

try {
    $idCommande = $commandeDAO->createFromPanier($idClient, $produits);
} catch (Exception $e) {
    ?>
    <main class="container">
        <h1>Commande impossible</h1>
        <p class="error"><?= htmlspecialchars($e->getMessage()) ?></p>
        <p><a href="index.php?page=panier">Retour au panier</a></p>
    </main>
    <?php
    return;
}
?>

<main class="container">
    <h1>Commande confirmée</h1>

    <p>Merci, votre commande #<?= htmlspecialchars($idCommande) ?> a bien été enregistrée.</p>
    <p><a href="index.php?page=profil">Voir mes commandes</a></p>
    <p><a href="index.php?page=home">Retour aux produits</a></p>
</main>
