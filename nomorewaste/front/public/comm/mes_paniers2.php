<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté et s'il est un commerçant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Commerçant') {
    die('Accès refusé. Vous devez être commerçant pour accéder à cette page.');
}

// Récupérer les paniers déposés par le commerçant
$id_commercant = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM collectes WHERE id_commercant = :id_commercant");
$stmt->bindParam(':id_commercant', $id_commercant);
$stmt->execute();
$paniers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Paniers Déposés</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        .panier {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .panier h3 {
            margin-top: 0;
        }

        .panier p {
            margin: 10px 0;
        }

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
    <div class="container">
        <h1>Mes Paniers Déposés</h1>

        <!-- Boucle sur les paniers déposés -->
        <?php foreach ($paniers as $panier): ?>
            <div class="panier">
                <h3>Panier #<?php echo $panier['id_collecte']; ?></h3>
                <p>Description : <?php echo htmlspecialchars($panier['description']); ?></p>
                <p>Quantité : <?php echo htmlspecialchars($panier['quantite']); ?></p>
                <p>Poids : <?php echo htmlspecialchars($panier['poids']); ?> kg</p>
                <p>Valeur Estimée : <?php echo htmlspecialchars($panier['valeur_estimee']); ?> €</p>
                <p>État : <?php echo htmlspecialchars($panier['etat']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="back-arrow" onclick="history.back();">
        &#x2190; Retour
    </div>
</body>
</html>
