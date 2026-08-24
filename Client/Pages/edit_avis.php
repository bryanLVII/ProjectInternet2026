<?php
if (!isset($_SESSION["user"]) || ($_SESSION["role"] ?? "") !== "client") {
    header("Location: index.php?page=login");
    exit;
}

$avisDAO = new AvisDAO($db);
$idAvis = $_GET["id"] ?? null;
$avis = $idAvis ? $avisDAO->getAvisById($idAvis) : null;

if (!$avis) {
    echo "Avis introuvable.";
    exit;
}

if ($avis["id_client"] != $_SESSION["user"]["id_client"]) {
    echo "Acces refuse.";
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $note = (int)($_POST["note"] ?? 0);
    $commentaire = trim($_POST["commentaire"] ?? "");

    if ($note < 1 || $note > 5 || $commentaire === "") {
        $error = "Veuillez remplir correctement tous les champs.";
    } else {
        $avisDAO->updateAvis($idAvis, $note, $commentaire);

        header("Location: index.php?page=produit&id=" . $avis["id_produit"]);
        exit;
    }
}
?>

<main class="container">
    <h1>Modifier mon avis</h1>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="form-panel">
        <label>
            Note<br>
            <select name="note">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>" <?= $avis["note"] == $i ? "selected" : "" ?>>
                        <?= $i ?>/5
                    </option>
                <?php endfor; ?>
            </select>
        </label><br>

        <label>
            Commentaire<br>
            <textarea name="commentaire" rows="6" cols="60" required><?= htmlspecialchars($avis["commentaire"]) ?></textarea>
        </label><br>

        <button type="submit">Enregistrer</button>
    </form>
</main>
