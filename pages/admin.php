<?php
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo "Accès refusé";
    exit;
}

$produitDAO = new ProduitDAO($db);
$clientDAO = new ClientDAO($db);
$commandeDAO = new CommandeDAO($db);

$produits = $produitDAO->getAllProduits();
$clients = $clientDAO->getAll();
$commandes = $commandeDAO->getAll();
?>

<main class="container">
    <h1>Administration</h1>

    <section class="admin-section">
        <h2>Produits</h2>
        <a class="button-link" href="index.php?page=Ajout_product">Ajouter produit</a>

        <?php foreach ($produits as $p): ?>
            <div class="admin-line">
                <h3><?= htmlspecialchars($p["nom_produit"]) ?></h3>
                <p>
                    Prix : <?= htmlspecialchars($p["prix"]) ?> € |
                    Stock : <?= htmlspecialchars($p["stock"]) ?>
                </p>
                <a href="index.php?page=Modification_product&id=<?= $p["id_produit"] ?>">Modifier</a>
                <a href="index.php?page=Supprimer_product&id=<?= $p["id_produit"] ?>">Supprimer</a>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="admin-section">
        <h2>Clients</h2>

        <?php foreach ($clients as $client): ?>
            <div class="admin-line">
                <strong><?= htmlspecialchars($client["nom"]) ?></strong><br>
                <?= htmlspecialchars($client["email"]) ?><br>
                Type : <?= htmlspecialchars($client["type_client"]) ?> |
                Crédits : <?= htmlspecialchars($client["credits_fidelite"]) ?>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="admin-section">
        <h2>Commandes</h2>

        <?php if (empty($commandes)): ?>
            <p>Aucune commande enregistrée.</p>
        <?php endif; ?>

        <?php foreach ($commandes as $commande): ?>
            <div class="admin-line">
                <strong>Commande #<?= htmlspecialchars($commande["id_commande"]) ?></strong><br>
                Client : <?= htmlspecialchars($commande["nom"]) ?> (<?= htmlspecialchars($commande["email"]) ?>)<br>
                Date : <?= htmlspecialchars($commande["date_commande"]) ?><br>
                Statut : <?= htmlspecialchars($commande["statut"]) ?><br>
                Total : <?= number_format($commande["total"], 2, ",", " ") ?> €

                <?php $lignes = $commandeDAO->getLines($commande["id_commande"]); ?>
                <?php if (!empty($lignes)): ?>
                    <ul>
                        <?php foreach ($lignes as $ligne): ?>
                            <li>
                                <?= htmlspecialchars($ligne["nom_produit"]) ?> -
                                <?= htmlspecialchars($ligne["quantite"]) ?> x
                                <?= htmlspecialchars($ligne["prix_unitaire"]) ?> €
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>
</main>
