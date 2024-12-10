<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Logique de vérification de l'email et du téléphone
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    // Requête pour vérifier que l'utilisateur existe
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE email = :email AND telephone = :telephone");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Stocker l'email dans la session pour l'utiliser dans reset_password.php
        $_SESSION['reset_email'] = $email;

        // Rediriger vers la page de réinitialisation du mot de passe
        header("Location: reset_password.php");
        exit;
    } else {
        echo "Informations incorrectes. Veuillez réessayer.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de Passe Oublié</title>
    <link rel="stylesheet" href="conn2.css">
</head>
<body>
    <div class="main">
        <h2>Réinitialiser le mot de passe</h2>
        <?php if ($error): ?>
            <div class="error-message" style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="forgot_password.php">
            <input class="form__input" type="email" name="email" placeholder="Email" required>
            <input class="form__input" type="text" name="telephone" placeholder="Téléphone" required>
            <button class="form__button button submit" type="submit">Envoyer</button>
        </form>
    </div>
</body>
</html>
