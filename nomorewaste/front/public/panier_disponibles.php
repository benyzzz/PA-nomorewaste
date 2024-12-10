<?php
session_start();
include '../../back/includes/db.php'; // Connexion à la base de données

// Récupération des villes distinctes des sites
$stmt_villes = $conn->prepare("SELECT DISTINCT s.nom_site FROM Sites s");
$stmt_villes->execute();
$villes = $stmt_villes->fetchAll(PDO::FETCH_ASSOC);

// Vérification si une ville a été choisie
$ville_choisie = isset($_GET['ville']) ? $_GET['ville'] : '';

// Préparation de la requête SQL avec jointure sur les tables collectes, commercants et sites
$query = "SELECT c.*, s.nom_site FROM collectes c
          JOIN Commercants com ON c.id_commercant = com.id_commercant
          JOIN Sites s ON com.id_site = s.id_site
          WHERE c.etat = 'à la vente'";

// Ajout d'une clause WHERE si une ville a été sélectionnée
if ($ville_choisie) {
    $query .= " AND s.nom_site = :ville";
}

$stmt = $conn->prepare($query);

if ($ville_choisie) {
    $stmt->bindParam(':ville', $ville_choisie);
}

$stmt->execute();
$collectes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Paniers Disponibles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
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
            font-size: 1.5rem;
        }

        .panier p {
            font-size: 1rem;
            margin: 10px 0;
        }

        .panier button {
            background-color: #6f42c1;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .panier button:hover {
            background-color: #563d7c;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
        }

        .actions {
            text-align: center;
            margin-top: 20px;
        }

        .actions button {
            background-color: #6f42c1;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .actions button:hover {
            background-color: #563d7c;
        }

        /* Style pour le filtre */
        .filter {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter select {
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .filter button {
            padding: 10px 20px;
            background-color: #6f42c1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .filter button:hover {
            background-color: #563d7c;
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
        <h1>Paniers Disponibles</h1>

        <!-- Formulaire de filtre par ville -->
        <div class="filter">
            <form method="GET" action="panier_disponibles.php">
                <label for="ville">Filtrer par ville :</label>
                <select name="ville" id="ville">
                    <option value="">Toutes les villes</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?php echo htmlspecialchars($ville['nom_site']); ?>"
                            <?php echo $ville['nom_site'] === $ville_choisie ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($ville['nom_site']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Filtrer</button>
            </form>
        </div>

        <!-- Boucle sur les paniers disponibles -->
        <?php if (!empty($collectes)): ?>
            <?php foreach ($collectes as $collecte): ?>
                <div class="panier">
                    <h3>Panier #<?php echo $collecte['id_collecte']; ?> - <?php echo htmlspecialchars($collecte['nom_site']); ?></h3>
                    <p>Description : <?php echo htmlspecialchars($collecte['description']); ?></p>
                    <p>Quantité disponible : <?php echo $collecte['quantite']; ?></p>
                    <button onclick="reserverPanier(<?php echo $collecte['id_collecte']; ?>, 1)">Réserver ce panier</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun panier disponible pour la ville sélectionnée.</p>
        <?php endif; ?>

        <!-- Bouton pour voir les paniers réservés -->
        <div class="actions">
            <button onclick="window.location.href='mes_paniers.php'">Mes Paniers Réservés</button>
            <button onclick="window.location.href='own_panier.php'">Mes Paniers disponible</button>
        </div>
    </div>
    <div class="back-arrow" onclick="history.back();">
        &#x2190; Retour
    </div>
    <script>
        function reserverPanier(idCollecte, quantite) {
            fetch('reserver_panier.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_collecte=${idCollecte}&quantite=${quantite}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Afficher un message de succès
                    window.location.reload(); // Rafraîchir la page
                } else {
                    alert(data.message); // Afficher un message d'erreur
                }
            })
            .catch(error => console.error('Erreur:', error));
        }
    </script>
</body>
</html>
