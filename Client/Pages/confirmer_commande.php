<?php
if (!isset($_SESSION["user"]) || ($_SESSION["role"] ?? "") !== "client") {
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
    header("Location: index.php?page=commande&id=" . $idCommande);
    exit;
} catch (Exception $e) {
    ?>
    <main class="container">
        <h1>Commande impossible</h1>
        <p class="error"><?= htmlspecialchars($e->getMessage()) ?></p>
        <p><a href="index.php?page=panier">Retour au panier</a></p>
    </main>
    <?php
}
