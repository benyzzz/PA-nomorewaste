<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Supprimer les services dont la date de fin est dépassée
$delete_stmt = $conn->prepare("DELETE FROM services WHERE date_fin < CURDATE()");
$delete_stmt->execute();

// Récupérer les filtres sélectionnés
$besoin = $_GET['besoin'] ?? '';
$ville = $_GET['ville'] ?? '';
$date_service = $_GET['date_service'] ?? '';

// Requête de sélection avec filtres
$sql = "SELECT * FROM services WHERE type_service = 'normal'";
$params = [];

if ($besoin) {
    $sql .= " AND nom_service LIKE :besoin";
    $params[':besoin'] = "%$besoin%";
}
if ($ville) {
    $sql .= " AND ville = :ville";
    $params[':ville'] = $ville;
}
if ($date_service) {
    $sql .= " AND :date_service BETWEEN date_debut AND date_fin";
    $params[':date_service'] = $date_service;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Chercher un service</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
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
        <h1>Chercher un service</h1>

        <form method="GET" class="filters">
            <input type="text" name="besoin" placeholder="Service recherché..." value="<?php echo htmlspecialchars($besoin); ?>">
            <input type="date" name="date_service" value="<?php echo htmlspecialchars($date_service); ?>">
            <select name="ville">
                <option value="">Toutes les villes</option>
                <option value="Paris" <?php echo $ville == 'Paris' ? 'selected' : ''; ?>>Paris</option>
                <option value="Marseille" <?php echo $ville == 'Marseille' ? 'selected' : ''; ?>>Marseille</option>
                <option value="Nantes" <?php echo $ville == 'Nantes' ? 'selected' : ''; ?>>Nantes</option>
            </select>
            <button type="submit">Filtrer</button>
        </form>

        <!-- Liste des services -->
        <?php foreach ($services as $service): ?>
            <div class="service">
                <h3><?php echo $service['nom_service']; ?></h3>
                <p>Description : <?php echo $service['description']; ?></p>
                <p>Ville : <?php echo $service['ville']; ?></p>
                <p>Date : <?php echo $service['date_debut'] . ' - ' . $service['date_fin']; ?></p>
                <button onclick="window.location.href='prendre_contact.php?id_service=<?php echo $service['id_service']; ?>'">Prendre contact</button>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="back-arrow" onclick="history.back();">
        &#x2190; Retour
    </div>
</body>
</html>


