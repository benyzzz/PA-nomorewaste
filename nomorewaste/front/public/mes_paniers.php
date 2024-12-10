<?php
session_start();
include '../../back/includes/db.php'; // Connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    echo "Session utilisateur invalide.";
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

try {
    // Récupérer les paniers réservés de l'utilisateur
    $stmt = $conn->prepare("SELECT id_collecte, date_collecte, description, poids, quantite_reservee, valeur_estimée FROM collectes WHERE id_utilisateur = :id_utilisateur AND etat = 'en cours'");
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt->execute();
    $paniers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erreur SQL : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Paniers Réservés</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        .back-arrow {
            display: inline-block;
            margin: 10px;
            padding: 10px;
            font-size: 24px;
            color: #007bff;
            cursor: pointer;
            transition: color 0.3s;
        }

        .back-arrow:hover {
            color: #0056b3;
        }
    </style>

</head>
<body>
    <h1>Mes Paniers Réservés</h1>
    <div class="paniers-container">
        <?php if (count($paniers) > 0): ?>
            <ul>
                <?php foreach ($paniers as $panier): ?>
                    <li>
                        <h2>Panier #<?php echo htmlspecialchars($panier['id_collecte']); ?></h2>
                        <p>Date de collecte : <?php echo htmlspecialchars($panier['date_collecte']); ?></p>
                        <p>Description : <?php echo htmlspecialchars($panier['description']); ?></p>
                        <p>Poids : <?php echo htmlspecialchars($panier['poids']); ?> kg</p>
                        <p>Quantité réservée : <?php echo htmlspecialchars($panier['quantite_reservee']); ?></p>
                        <p>Valeur estimée : €<?php echo htmlspecialchars($panier['valeur_estimée']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Vous n'avez pas encore réservé de paniers.</p>
        <?php endif; ?>
    </div>
</body>
<div class="back-arrow" onclick="history.back();">
        &#x2190; Retour
    </div>
</html>
