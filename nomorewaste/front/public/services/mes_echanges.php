<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

$id_utilisateur = $_SESSION['user_id'];

// Récupérer les échanges envoyés par l'utilisateur
$stmt_envoyes = $conn->prepare("
    SELECT * FROM services
    WHERE id_utilisateur = :id_utilisateur AND type_service = 'echange'
");
$stmt_envoyes->bindParam(':id_utilisateur', $id_utilisateur);
$stmt_envoyes->execute();
$echanges_envoyes = $stmt_envoyes->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les échanges reçus par l'utilisateur
$stmt_recus = $conn->prepare("
    SELECT * FROM services
    WHERE id_utilisateur_beneficiaire = :id_utilisateur AND etat = 'en attente'
");
$stmt_recus->bindParam(':id_utilisateur', $id_utilisateur);
$stmt_recus->execute();
$echanges_recus = $stmt_recus->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Échanges</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Mes Échanges</h1>

        <h2>Échanges envoyés</h2>
        <?php if (count($echanges_envoyes) > 0): ?>
            <?php foreach ($echanges_envoyes as $echange): ?>
                <div class="echange">
                    <h3>Service échangé : <?php echo htmlspecialchars($echange['nom_service']); ?></h3>
                    <p>État : <?php echo htmlspecialchars($echange['etat']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez envoyé aucune demande d'échange.</p>
        <?php endif; ?>

        <h2>Échanges reçus</h2>
        <?php if (count($echanges_recus) > 0): ?>
            <?php foreach ($echanges_recus as $echange): ?>
                <div class="echange">
                    <h3>Service proposé : <?php echo htmlspecialchars($echange['nom_service']); ?></h3>
                    <form action="reponse_echange.php" method="POST">
                        <input type="hidden" name="id_service" value="<?php echo $echange['id_service']; ?>">
                        <button type="submit" name="action" value="accepter">Accepter</button>
                        <button type="submit" name="action" value="refuser">Refuser</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez reçu aucune demande d'échange.</p>
        <?php endif; ?>
    </div>
</body>
</html>
