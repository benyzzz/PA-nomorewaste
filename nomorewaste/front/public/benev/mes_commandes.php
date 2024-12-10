<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté et s'il est un bénévole
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Bénévole') {
    die('Accès refusé. Vous devez être bénévole pour accéder à cette page.');
}

$id_benevole = $_SESSION['user_id'];

// Récupérer toutes les collectes prises en charge par le bénévole
$stmt = $conn->prepare("
    SELECT c.id_collecte, c.description, c.quantite, c.poids, c.valeur_estimée, c.code_barre, c.date_collecte, c.etat, m.adresse
    FROM collectes c
    JOIN commercants m ON c.id_commercant = m.id_commercant
    WHERE c.id_utilisateur = :id_benevole
");
$stmt->bindParam(':id_benevole', $id_benevole);
$stmt->execute();
$collectes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }

        .commande {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .commande h3 {
            margin-top: 0;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
    <a href="../index_bene.php" class="btn btn-back">Retour à l'accueil</a>
</head>
<body>
    <div class="container">
        <h1>Mes Commandes</h1>

        <?php foreach ($collectes as $collecte): ?>
            <div class="commande">
                <h3>Commande de <?php echo htmlspecialchars($collecte['description']); ?> </h3>
                <p>Quantité : <?php echo htmlspecialchars($collecte['quantite']); ?></p>
                <p>Poids : <?php echo htmlspecialchars($collecte['poids']); ?> kg</p>
                <p>Valeur estimée : <?php echo htmlspecialchars($collecte['valeur_estimée']); ?> €</p>
                <p>Code barre : <?php echo htmlspecialchars($collecte['code_barre']); ?></p>
                <p>Date de collecte : <?php echo htmlspecialchars($collecte['date_collecte']); ?></p>
                <p>Adresse : <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($collecte['adresse']); ?>" target="_blank"><?php echo htmlspecialchars($collecte['adresse']); ?></a></p>

                <form action="generate_pdf.php" method="GET">
    <input type="hidden" name="id" value="<?php echo $collecte['id_collecte']; ?>">
    <button type="submit" class="btn">Télécharger le PDF</button>
</form>

            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
