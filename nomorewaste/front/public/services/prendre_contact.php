<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Vérification si l'ID du service est défini
if (!isset($_GET['id_service'])) {
    die('Erreur : Aucun service sélectionné.');
}

$id_service = $_GET['id_service'];

// Requête pour récupérer les informations du service et de l'utilisateur qui l'a proposé
$sql = "SELECT s.*, u.prenom, u.nom, u.email, u.telephone FROM services s
        JOIN utilisateurs u ON s.id_utilisateur = u.id_utilisateur
        WHERE s.id_service = :id_service";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
$stmt->execute();

$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    die('Erreur : Service non trouvé.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Prendre contact</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }

        .service-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .service-details h3 {
            margin-top: 0;
        }

        .service-details p {
            margin: 10px 0;
        }

        .contact-info {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .contact-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Détails du service</h1>

        <div class="service-details">
            <h3><?php echo $service['nom_service']; ?></h3>
            <p>Description : <?php echo $service['description']; ?></p>
            <p>Ville : <?php echo $service['ville']; ?></p>
            <p>Date : <?php echo $service['date_debut'] . ' - ' . $service['date_fin']; ?></p>
        </div>

        <h2>Informations de contact</h2>
        <div class="contact-info">
            <p><strong>Nom :</strong> <?php echo $service['prenom'] . ' ' . $service['nom']; ?></p>
            <p><strong>Email :</strong> <a href="mailto:<?php echo $service['email']; ?>"><?php echo $service['email']; ?></a></p>
            <p><strong>Téléphone :</strong> <?php echo $service['telephone']; ?></p>
        </div>
    </div>
</body>
</html>
