<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Initialisation des messages d'erreur
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'email stocké dans la session
    if (isset($_SESSION['reset_email'])) {
        $email = $_SESSION['reset_email'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Vérifier que les mots de passe correspondent
        if ($new_password === $confirm_password) {
            // Hacher le nouveau mot de passe
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Mise à jour du mot de passe dans la base de données
            $stmt = $conn->prepare("UPDATE Utilisateurs SET mot_de_passe = :mot_de_passe WHERE email = :email");
            $stmt->bindParam(':mot_de_passe', $hashed_password);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                $success = "Votre mot de passe a été mis à jour avec succès.";
                // Optionnel : supprimer les variables de session associées à la réinitialisation du mot de passe
                unset($_SESSION['reset_email']);
            } else {
                $error = "Une erreur s'est produite lors de la mise à jour du mot de passe.";
            }
        } else {
            $error = "Les mots de passe ne correspondent pas.";
        }
    } else {
        $error = "Session invalide ou expirée. Veuillez recommencer le processus.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="conn2.css">
</head>
<body>
    <div class="main">
        <h2 class="form_title title">Réinitialiser le mot de passe</h2>

        <!-- Affichage des messages d'erreur ou de succès -->
        <?php if ($error): ?>
            <div class="error-message" style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message" style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($success); ?></div>
            <a href="connexion.php">Se connecter</a>
        <?php else: ?>

        <form class="form" method="POST" action="reset_password.php">
            <input class="form__input" type="password" name="new_password" placeholder="Nouveau mot de passe" required>
            <input class="form__input" type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
            <button class="form__button button submit" type="submit">Réinitialiser le mot de passe</button>
        </form>

        <?php endif; ?>
    </div>
</body>
</html>
