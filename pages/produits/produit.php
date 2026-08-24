<?php
$produitDAO = new ProduitDAO($db);
$avisDAO = new AvisDAO($db);
$id = $_GET["id"] ?? null;

if (!$id) {
    echo "Produit introuvable.";
    exit;
}

$produit = $produitDAO->getProduitById($id);

if (!$produit) {
    echo "Produit inexistant.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_SESSION["role"] ?? "") === "client") {
    $note = (int)($_POST["note"] ?? 0);
    $commentaire = trim($_POST["commentaire"] ?? "");
    $idClient = $_SESSION["user"]["id_client"];

    if ($note >= 1 && $note <= 5 && $commentaire !== "" && !$avisDAO->clientADejaAvis($idClient, $id)) {
        $avisDAO->addAvis($idClient, $id, $note, $commentaire);

        header("Location: index.php?page=produit&id=" . $id);
        exit;
    }
}

$avis = $avisDAO->getAvisByProduit($id);
?>

<main class="container">
    <h1><?= htmlspecialchars($produit["nom_produit"]) ?></h1>

    <p><?= htmlspecialchars($produit["description"] ?? "") ?></p>
    <p><b><?= htmlspecialchars($produit["prix"]) ?> EUR</b></p>
    <p>Stock : <?= htmlspecialchars($produit["stock"]) ?></p>
    <p>Marque : <?= htmlspecialchars($produit["marque"] ?? "") ?></p>

    <?php if (($_SESSION["role"] ?? "") === "client"): ?>
        <p>
            <a href="index.php?page=add_panier&id=<?= $produit["id_produit"] ?>">
                Ajouter au panier
            </a>
        </p>
    <?php endif; ?>

    <hr>
    <h2>Avis clients</h2>

    <?php if (empty($avis)): ?>
        <p>Aucun avis pour le moment.</p>
    <?php endif; ?>

    <?php foreach ($avis as $a): ?>
        <div class="admin-line">
            <strong><?= htmlspecialchars($a["nom"]) ?></strong>
            - Note : <?= htmlspecialchars($a["note"]) ?>/5

            <p><?= nl2br(htmlspecialchars($a["commentaire"])) ?></p>

            <?php if (($_SESSION["role"] ?? "") === "client" && $_SESSION["user"]["id_client"] == $a["id_client"]): ?>
                <a href="index.php?page=edit_avis&id=<?= $a["id_avis"] ?>">Modifier</a>
            <?php endif; ?>

            <?php if (($_SESSION["role"] ?? "") === "admin"): ?>
                <a href="index.php?page=supprimer_avis&id=<?= $a["id_avis"] ?>&produit=<?= $produit["id_produit"] ?>"
                   onclick="return confirm('Supprimer cet avis ?')">
                    Supprimer
                </a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <?php if (($_SESSION["role"] ?? "") === "client"): ?>
        <hr>
        <h3>Laisser un avis</h3>

        <form method="POST" class="form-panel">
            <label>
                Note<br>
                <select name="note" required>
                    <option value="5">5/5</option>
                    <option value="4">4/5</option>
                    <option value="3">3/5</option>
                    <option value="2">2/5</option>
                    <option value="1">1/5</option>
                </select>
            </label><br>

            <label>
                Commentaire<br>
                <textarea name="commentaire" rows="4" cols="50" required></textarea>
            </label><br>

            <button type="submit">Publier mon avis</button>
        </form>
    <?php endif; ?>
</main>
