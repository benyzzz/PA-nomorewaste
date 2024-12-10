<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Initialisation des messages d'erreur
$error = '';

// Vérification des données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier d'abord si l'email existe dans la table des utilisateurs
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Si l'utilisateur est trouvé, vérifier le mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            // Connexion réussie, démarrer la session utilisateur
            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['username'] = $user['prenom']; // Stocker le prénom
            $_SESSION['role'] = $user['role'];

            // Redirection en fonction du rôle
            switch ($user['role']) {
                case 'Utilisateur':
                    header("Location: ../index_user.php");
                    break;
                case 'Bénévole':
                    header("Location: ../index_bene.php");
                    break;
                default:
                    header("Location: ../index_bene.php");
            }
            exit;
        } else {
            $error = 'Mot de passe incorrect.';
        }
    } else {
        // Si l'utilisateur n'est pas trouvé, vérifier dans la table des commerçants
        $stmt = $conn->prepare("SELECT * FROM Commercants WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $commercant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($commercant) {
            // Si le commerçant est trouvé, vérifier le mot de passe
            if (password_verify($password, $commercant['mot_de_passe'])) {
                // Connexion réussie, démarrer la session commerçant
                $_SESSION['user_id'] = $commercant['id_commercant'];
                $_SESSION['username'] = $commercant['nom_entreprise']; // Stocker le nom de l'entreprise
                $_SESSION['role'] = 'Commerçant';

                // Redirection vers le tableau de bord commerçant
                header("Location: ../index_comm.php");
                exit;
            } else {
                $error = 'Mot de passe incorrect.';
            }
        } else {
            $error = 'Email non trouvé.';
        }
    }

}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="conn2.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main">
        <!-- Formulaire de connexion -->
        <div class="container b-container" id="b-container">
            <form id="b-form" class="form" method="POST" action="connexion.php">
                <h2 class="form_title title">Connectez-vous au site Web</h2>

                <!-- Affichage des erreurs si elles existent -->
                <?php if ($error): ?>
                    <div class="error-message" style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <input class="form__input" type="email" name="email" placeholder="Email" required>
                <input class="form__input" type="password" name="password" placeholder="Mot de Passe" required>
                <a class="form__link" href="forgot_password.php">Mot de Passe oublié?</a>
                <button class="form__button button submit" type="submit">SE CONNECTER</button>
            </form>
        </div>

        <!-- Formulaire d'inscription -->
        <div class="container a-container" id="a-container">
            <form id="a-form" class="form" method="POST" action="register_details.php">
                <h2 class="form_title title">Créer un compte</h2>
                <label for="role">Choisissez votre rôle :</label>
                <select id="role" name="role" class="form__input" required>
                    <option value="Commerçant">Commerçant</option>
                    <option value="Utilisateur">Utilisateur</option>
                    <option value="Bénévole">Bénévole</option>
                </select>
                <button class="form__button button submit" type="submit">S'INSCRIRE</button>
            </form>
        </div>

        <!-- Mécanisme de basculement entre les formulaires -->
        <div class="switch" id="switch-cnt">
            <div class="switch__circle"></div>
            <div class="switch__circle switch__circle--t"></div>
            <div class="switch__container" id="switch-c1">
                <h2 class="switch__title title">De retour !</h2>
                <p class="switch__description description">Pour rester en contact avec nous, veuillez vous connecter avec vos informations personnelles</p>
                <button class="switch__button button switch-btn">SE CONNECTER</button>
            </div>
            <div class="switch__container is-hidden" id="switch-c2">
                <h2 class="switch__title title">Bienvenue !</h2>
                <p class="switch__description description">Entrez vos informations personnelles et commencez votre voyage avec nous</p>
                <button class="switch__button button switch-btn">S'INSCRIRE</button>
            </div>
        </div>
    </div>
    <script src="conn2.js"></script>
</body>
</html>
