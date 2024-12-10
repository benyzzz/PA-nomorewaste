<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté et s'il est un bénévole
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Bénévole') {
    die('Accès refusé. Vous devez être bénévole pour accéder à cette page.');
}

// Récupérer la liste des commerçants pour le filtre
$commercantsStmt = $conn->prepare("SELECT DISTINCT m.nom_entreprise, m.id_commercant FROM commercants m JOIN collectes c ON m.id_commercant = c.id_commercant WHERE c.etat = 'en cours'");
$commercantsStmt->execute();
$commercants = $commercantsStmt->fetchAll(PDO::FETCH_ASSOC);

// Appliquer le filtre si un commerçant est sélectionné
if (isset($_POST['commercant']) && $_POST['commercant'] != '') {
    $commercantId = $_POST['commercant'];
    $stmt = $conn->prepare("SELECT c.*, m.nom_entreprise, m.ville FROM collectes c JOIN commercants m ON c.id_commercant = m.id_commercant WHERE c.etat = 'en cours' AND c.id_commercant = :id_commercant");
    $stmt->bindParam(':id_commercant', $commercantId);
} else {
    // Si "Tous les commerçants" est sélectionné
    $stmt = $conn->prepare("SELECT c.*, m.nom_entreprise, m.ville FROM collectes c JOIN commercants m ON c.id_commercant = m.id_commercant WHERE c.etat = 'en cours'");
}

$stmt->execute();
$paniers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bénévole</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }

        .panier {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .panier h3 {
            margin-top: 0;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #218838;
        }

        .btn-secondary {
            background-color: #007bff;
        }

        .btn-secondary:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #dc3545;
        }

        .btn-back:hover {
            background-color: #c82333;
        }

        .filter-form {
            margin-bottom: 20px;
        }
    </style>
    <div class="button-group">
        <a href="mes_commandes.php" class="btn btn-secondary">Mes Commandes</a>
        <a href="../index_bene.php" class="btn btn-back">Retour à l'accueil</a>
    </div>
</head>
<body>
    <div class="container">
        <h1>Paniers à récupérer</h1>

        <!-- Formulaire de filtre par commerçant -->
        <form method="POST" class="filter-form">
            <label for="commercant">Filtrer par commerçant :</label>
            <select name="commercant" id="commercant" onchange="this.form.submit()">
                <option value="">Tous les commerçants</option>
                <?php foreach ($commercants as $commercant): ?>
                    <option value="<?php echo $commercant['id_commercant']; ?>" <?php echo (isset($commercantId) && $commercantId == $commercant['id_commercant']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($commercant['nom_entreprise']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <form method="POST" action="planning_benevole.php">
            <h2>Choisissez les paniers et votre créneau horaire</h2>

            <?php if (empty($paniers)): ?>
                <p>Aucun panier à récupérer pour le moment.</p>
            <?php else: ?>
                <?php foreach ($paniers as $panier): ?>
                    <div class="panier">
                        <h3>Panier de <?php echo htmlspecialchars($panier['description']); ?> (<?php echo htmlspecialchars($panier['nom_entreprise']); ?>)</h3>
                        <p>Ville : <?php echo htmlspecialchars($panier['ville']); ?></p>
                        <p>Quantité : <?php echo htmlspecialchars($panier['quantite']); ?></p>
                        <p>Poids : <?php echo htmlspecialchars($panier['poids']); ?> kg</p>
                        <p>Valeur estimée : <?php echo htmlspecialchars($panier['valeur_estimée']); ?> €</p>
                        <p>Code barre : <?php echo htmlspecialchars($panier['code_barre']); ?></p>
                        <label>
                            <input type="checkbox" name="paniers[]" value="<?php echo $panier['id_collecte']; ?>">
                            Sélectionner ce panier
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="form-group">
                <label for="date_recuperation">Date de récupération</label>
                <input type="date" name="date_recuperation" id="date_recuperation" required>
            </div>

            <div class="form-group">
                <label for="creneau_horaire">Créneau horaire</label>
                <input type="text" name="creneau_horaire" id="creneau_horaire" placeholder="Ex : 10h - 12h" required>
            </div>

            <button type="submit" class="btn">Générer le planning</button>
        </form>
    </div>
</body>
</html>
