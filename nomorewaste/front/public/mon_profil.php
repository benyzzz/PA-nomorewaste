<?php
session_start();

include '../../back/includes/db.php'; // Connexion à la base de données

// Assurez-vous que l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: connexion.php");
    exit;
}

// Récupérer les informations de l'utilisateur ou du commerçant selon le rôle
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role === 'Commerçant') {
    $stmt = $conn->prepare("SELECT * FROM Commercants WHERE id_commercant = :id");
} else {
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE id_utilisateur = :id");
}

$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si aucune information n'est trouvée, afficher un message d'erreur
if (!$user) {
    die("Utilisateur ou commerçant non trouvé.");
}

// Si l'utilisateur soumet une demande de changement de rôle (non pertinent pour les commerçants)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['demande_role']) && $role !== 'Commerçant') {
    $nouveau_role = $_POST['demande_role'];
    $message = isset($_POST['message']) ? $_POST['message'] : null;

    // Mettre à jour les informations de la demande dans la table Utilisateurs
    $stmt = $conn->prepare("UPDATE Utilisateurs SET nouveau_role = :nouveau_role, message_demande = :message, statut_demande = 'en attente', date_demande = NOW() WHERE id_utilisateur = :id_utilisateur");
    $stmt->bindParam(':nouveau_role', $nouveau_role);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':id_utilisateur', $user_id);

    if ($stmt->execute()) {
        $success = "Votre demande a été envoyée avec succès.";
    } else {
        $error = "Erreur lors de l'envoi de la demande.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="styles_profils.css">
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
    <div class="main">
        <div class="profile-header">
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($role === 'Commerçant' ? $user['nom_entreprise'] : $user['prenom']); ?></h1>
                <p><?php echo ucfirst($role); ?></p>
            </div>
            <div class="logout">
                <a href="logout.php">
                    <button>Déconnexion</button>
                </a>
            </div>
        </div>

        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2>IDENTITÉ</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <table>
                        <tbody>
                            <tr>
                                <td><?php echo $role === 'Commerçant' ? 'Nom de l\'entreprise' : 'Nom'; ?></td>
                                <td>:</td>
                                <td><input type="text" name="username" value="<?php echo htmlspecialchars($role === 'Commerçant' ? $user['nom_entreprise'] : $user['prenom']); ?>"></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td><input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"></td>
                            </tr>
                            <tr>
                                <td>Adresse</td>
                                <td>:</td>
                                <td><input type="text" name="address" value="<?php echo htmlspecialchars($user['adresse']); ?>"></td>
                            </tr>
                            <tr>
                                <td>Téléphone</td>
                                <td>:</td>
                                <td><input type="text" name="telephone" value="<?php echo htmlspecialchars($user['telephone']); ?>"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="submit">Mettre à jour</button>
                </form>
            </div>
        </div>

        <!-- Boutons pour les demandes de changement de rôle (Non visible pour les commerçants) -->
        <div class="role-requests">
            <?php if ($role === 'Utilisateur' && !$user['nouveau_role']): ?>
                <form method="POST">
                    <input type="hidden" name="demande_role" value="Bénévole">
                    <button type="submit">Demander à devenir Bénévole</button>
                </form>
            <?php elseif ($role === 'Bénévole' && !$user['nouveau_role']): ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="demande_role" value="Salarié">
                    <label for="cv">Télécharger votre CV :</label>
                    <input type="file" name="cv" required>
                    <button type="submit">Demander à devenir Salarié</button>
                </form>
            <?php elseif ($role === 'Salarié' && !$user['nouveau_role']): ?>
                <form method="POST">
                    <input type="hidden" name="demande_role" value="Admin">
                    <label for="message">Pourquoi souhaitez-vous devenir Admin ?</label>
                    <textarea name="message" required></textarea>
                    <button type="submit">Demander à devenir Admin</button>
                </form>
            <?php elseif ($role === 'Admin'): ?>
                <a href="../../back/index.php"><button type="button">Accéder au Back Office</button></a>
            <?php elseif ($user['statut_demande'] === 'en attente'): ?>
                <p>Votre demande pour devenir <?php echo htmlspecialchars($user['nouveau_role']); ?> est en cours de traitement.</p>
            <?php endif; ?>
            <div class="back-arrow" onclick="history.back();">
                &#x2190; Retour
            </div>
        </div>
    </div>
</body>
</html>
