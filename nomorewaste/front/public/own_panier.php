<?php
session_start();
include '../../back/includes/db.php'; // Connexion à la base de données

if (!isset($_SESSION['user_id'])) {
    echo "Session utilisateur invalide.";
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

try {
    // Récupérer les paniers vendus de l'utilisateur avec l'adresse du site de retrait
    $stmt = $conn->prepare("
        SELECT c.id_collecte, c.description, c.poids, c.quantite_reservee, c.valeur_estimée, s.nom_site, s.adresse
        FROM collectes c
        JOIN commercants co ON c.id_commercant = co.id_commercant
        JOIN sites s ON co.id_site = s.id_site
        WHERE c.id_utilisateur = :id_utilisateur
        AND c.etat = 'vendu'
    ");
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
    <title>Mes Paniers Vendus</title>
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
    <h1>Mes paniers vendus et disponibles au retrait</h1>
    <div class="paniers-container">
        <?php if (count($paniers) > 0): ?>
            <ul>
                <?php foreach ($paniers as $panier): ?>
                    <li>
                        <h2>Panier #<?php echo htmlspecialchars($panier['id_collecte']); ?></h2>
                        <p>Description : <?php echo htmlspecialchars($panier['description']); ?></p>
                        <p>Poids : <?php echo htmlspecialchars($panier['poids']); ?> kg</p>
                        <p>Quantité vendue : <?php echo htmlspecialchars($panier['quantite_reservee']); ?></p>
                        <p>Valeur estimée : €<?php echo htmlspecialchars($panier['valeur_estimée']); ?></p>
                        <p>Site de retrait : <?php echo htmlspecialchars($panier['nom_site']); ?></p>
                        <p>Adresse : <?php echo htmlspecialchars($panier['adresse']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Vous n'avez pas encore de paniers vendus.</p>
        <?php endif; ?>
    </div>
</body>
<div class="back-arrow" onclick="history.back();">
        &#x2190; Retour
    </div>
</html>

