<?php
if (($_SESSION["role"] ?? "") !== "admin") {
    echo "Acces refuse.";
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

    <nav class="admin-nav">
        <a href="index.php?page=admin">Tableau de bord</a>
        <a href="index.php?page=admin_clients">Clients</a>
        <a href="index.php?page=Ajout_product">Ajouter un produit</a>
    </nav>

    <section class="admin-section">
        <h2>Produits</h2>

        <?php foreach ($produits as $produit): ?>
            <div class="admin-line">
                <h3><?= htmlspecialchars($produit["nom_produit"]) ?></h3>
                <p>
                    Prix : <?= htmlspecialchars($produit["prix"]) ?> EUR |
                    Stock : <?= htmlspecialchars($produit["stock"]) ?>
                </p>
                <a href="index.php?page=Modification_product&id=<?= $produit["id_produit"] ?>">Modifier</a>
                <a href="index.php?page=Supprimer_product&id=<?= $produit["id_produit"] ?>">Supprimer</a>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="admin-section">
        <h2>Clients</h2>
        <p><?= count($clients) ?> client(s) inscrit(s).</p>
        <p><a href="index.php?page=admin_clients">Voir les clients</a></p>
    </section>

    <section class="admin-section">
        <h2>Commandes</h2>

        <?php if (empty($commandes)): ?>
            <p>Aucune commande enregistree.</p>
        <?php endif; ?>

        <?php foreach ($commandes as $commande): ?>
            <div class="admin-line">
                <strong>Commande #<?= htmlspecialchars($commande["id_commande"]) ?></strong><br>
                Client : <?= htmlspecialchars($commande["nom"]) ?> (<?= htmlspecialchars($commande["email"]) ?>)<br>
                Date : <?= htmlspecialchars($commande["date_commande"]) ?><br>
                Statut : <?= htmlspecialchars($commande["statut"]) ?><br>
                Total : <?= number_format($commande["total"], 2, ",", " ") ?> EUR

                <?php $lignes = $commandeDAO->getLines($commande["id_commande"]); ?>
                <?php if (!empty($lignes)): ?>
                    <ul>
                        <?php foreach ($lignes as $ligne): ?>
                            <li>
                                <?= htmlspecialchars($ligne["nom_produit"]) ?> :
                                <?= htmlspecialchars($ligne["quantite"]) ?> x
                                <?= htmlspecialchars($ligne["prix_unitaire"]) ?> EUR
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>
</main>
