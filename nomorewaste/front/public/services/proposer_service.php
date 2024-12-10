<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $id_utilisateur = $_SESSION['user_id'];
    $nom_service = $_POST['nom_service'];
    $description = $_POST['description'];
    $quantite = $_POST['quantite'];
    $ville = $_POST['ville'];
    $type_service = $_POST['type_service'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $echange_contre = isset($_POST['echange_contre']) ? $_POST['echange_contre'] : null;

    try {
        // Insertion dans la base de données
        $stmt = $conn->prepare("
            INSERT INTO services (nom_service, description, quantite, id_utilisateur, type_service, date_debut, date_fin, ville, echange_contre)
            VALUES (:nom_service, :description, :quantite, :id_utilisateur, :type_service, :date_debut, :date_fin, :ville, :echange_contre)
        ");
        $stmt->bindParam(':nom_service', $nom_service);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur);
        $stmt->bindParam(':type_service', $type_service);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':echange_contre', $echange_contre);

        if ($stmt->execute()) {
            echo "Service proposé avec succès !";
        } else {
            echo "Erreur lors de la proposition du service.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Proposer un Service</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input, select, textarea {
            margin: 10px 0;
            padding: 10px;
            font-size: 1rem;
        }

        button {
            background-color: #6f42c1;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
        }

        button:hover {
            background-color: #563d7c;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        #echange_contre_container {
            display: none;
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
        <h1>Proposer un Service</h1>

        <form method="POST">
            <label for="nom_service">Nom du Service :</label>
            <input type="text" name="nom_service" id="nom_service" required>

            <label for="description">Description du Service :</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="quantite">Quantité :</label>
            <input type="number" name="quantite" id="quantite" value="1" required min="1">

            <label for="ville">Ville :</label>
            <select name="ville" id="ville" required>
                <option value="">Sélectionnez une ville</option>
                <option value="Paris">Paris</option>
                <option value="Marseille">Marseille</option>
                <option value="Nantes">Nantes</option>
            </select>

            <label for="type_service">Type de Service :</label>
            <select name="type_service" id="type_service" required onchange="toggleEchangeField()">
                <option value="normal">Normal</option>
                <option value="echange">Échange</option>
            </select>

            <div id="echange_contre_container">
                <label for="echange_contre">Échange contre :</label>
                <input type="text" name="echange_contre" id="echange_contre">
            </div>

            <label for="date_debut">Date de début :</label>
            <input type="date" name="date_debut" id="date_debut" required>

            <label for="date_fin">Date de fin :</label>
            <input type="date" name="date_fin" id="date_fin" required>

            <button type="submit">Proposer le Service</button>
        </form>
    </div>
    <div class="back-arrow" onclick="history.back();">
        &#x2190; Retour
    </div>
    <script>
        function toggleEchangeField() {
            const typeService = document.getElementById('type_service').value;
            const echangeContainer = document.getElementById('echange_contre_container');
            if (typeService === 'echange') {
                echangeContainer.style.display = 'block';
            } else {
                echangeContainer.style.display = 'none';
            }
        }
    </script>
</body>
</html>

