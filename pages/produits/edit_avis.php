<?php

if (
    !isset($_SESSION["user"])
    || ($_SESSION["role"] ?? "") !== "client"
) {
    header("Location: index.php?page=login");
    exit;
}

$avisDAO = new AvisDAO($db);

$idAvis = $_GET["id"] ?? null;

if (!$idAvis) {
    header("Location: index.php?page=home");
    exit;
}

$avis = $avisDAO->getAvisById($idAvis);

if (!$avis) {
    echo "Avis introuvable";
    exit;
}

/*
    Sécurité :
    Le client ne peut modifier que SON avis
*/
if ($avis["id_client"] != $_SESSION["user"]["id_client"]) {
    echo "Accès refusé";
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $note = (int)($_POST["note"] ?? 0);
    $commentaire = trim($_POST["commentaire"] ?? "");

    if (
        $note < 1
        || $note > 5
        || $commentaire === ""
    ) {
        $error = "Veuillez remplir correctement tous les champs.";
    } else {

        $avisDAO->updateAvis(
            $idAvis,
            $note,
            $commentaire
        );

        header(
            "Location: index.php?page=produit&id=" .
            $avis["id_produit"]
        );
        exit;
    }
}

?>

<main class="container">

    <h1>Modifier mon avis</h1>

    <?php if ($error): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>
            Note
        </label>

        <br>

        <select name="note">

            <option value="1" <?= $avis["note"] == 1 ? "selected" : "" ?>>
                1/5
            </option>

            <option value="2" <?= $avis["note"] == 2 ? "selected" : "" ?>>
                2/5
            </option>

            <option value="3" <?= $avis["note"] == 3 ? "selected" : "" ?>>
                3/5
            </option>

            <option value="4" <?= $avis["note"] == 4 ? "selected" : "" ?>>
                4/5
            </option>

            <option value="5" <?= $avis["note"] == 5 ? "selected" : "" ?>>
                5/5
            </option>

        </select>

        <br><br>

        <label>
            Commentaire
        </label>

        <br>

        <textarea
            name="commentaire"
            rows="6"
            cols="60"
            required
        ><?= htmlspecialchars($avis["commentaire"]) ?></textarea>

        <br><br>

        <button type="submit">
            Enregistrer les modifications
        </button>

    </form>

</main>