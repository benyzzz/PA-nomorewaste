<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Récupérer les services de l'utilisateur connecté
$id_utilisateur = $_SESSION['user_id'];
$stmt_user_services = $conn->prepare("SELECT * FROM services WHERE id_utilisateur = :id_utilisateur AND type_service = 'echange'");
$stmt_user_services->bindParam(':id_utilisateur', $id_utilisateur);
$stmt_user_services->execute();
$user_services = $stmt_user_services->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les services d'autres utilisateurs disponibles pour échange
$stmt_other_services = $conn->prepare("SELECT * FROM services WHERE id_utilisateur != :id_utilisateur AND type_service = 'echange'");
$stmt_other_services->bindParam(':id_utilisateur', $id_utilisateur);
$stmt_other_services->execute();
$other_services = $stmt_other_services->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Échanger un service</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        .service {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .service h3 {
            margin-top: 0;
        }

        .service p {
            margin: 10px 0;
        }

        .filters {
            margin-bottom: 20px;
        }

        .filters input, .filters select {
            padding: 10px;
            margin-right: 10px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #6f42c1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #563d7c;
        }

        .btn-container {
            margin-top: 20px;
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
        <h1>Échanger un service</h1>

        <form method="POST" action="process_echange.php">
            <div>
                <h2>Vos services</h2>
                <select name="id_service_user" required>
                    <option value="">Sélectionnez votre service</option>
                    <?php foreach ($user_services as $service): ?>
                        <option value="<?php echo $service['id_service']; ?>">
                            <?php echo $service['nom_service']; ?> (<?php echo $service['ville']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <h2>Services disponibles à l'échange</h2>
                <select name="id_service_other" required>
                    <option value="">Sélectionnez un service à échanger</option>
                    <?php foreach ($other_services as $service): ?>
                        <option value="<?php echo $service['id_service']; ?>">
                            <?php echo $service['nom_service']; ?> (<?php echo $service['ville']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" class="btn">Proposer l'échange</button>
            </div>
        </form>

        <!-- Bouton pour accéder à l'espace d'échange -->
        <div class="btn-container">
            <button class="btn" onclick="window.location.href='historique_echanges.php'">Voir mes échanges</button>
        </div>
    </div>
    <div class="back-arrow" onclick="history.back();">
        &#x2190; Retour
    </div>
</body>
</html>
