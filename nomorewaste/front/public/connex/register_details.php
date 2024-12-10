<?php
session_start();

// Connexion à la base de données
include '../../../back/includes/db.php';

// Récupérer la liste des sites depuis la base de données
$stmt = $conn->prepare("SELECT id_site, nom_site FROM Sites");
$stmt->execute();
$sites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complétez votre inscription</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form__input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            font-size: 14px;
        }

        .form__button {
            background-color: #6f42c1;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .form__button:hover {
            background-color: #563d7c;
        }

        #additional-fields textarea {
            resize: none;
            height: 100px;
        }

        @media (max-width: 768px) {
            .main {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="main">
    <h3>Complétez votre inscription</h3>
    <form class="form" method="POST" action="register_complete.php">
        <input class="form__input" type="text" name="username" placeholder="Nom" required>
        <input class="form__input" type="email" name="email" placeholder="Email" required>
        <input class="form__input" type="password" name="password" placeholder="Mot de Passe" required>
        <input class="form__input" type="text" name="telephone" placeholder="Téléphone" required>

        <label for="role">Choisissez votre rôle :</label>
        <select id="role" name="role" class="form__input" onchange="updateForm()" required>
            <option value="">--Sélectionner un rôle--</option>
            <option value="Commerçant">Commerçant</option>
            <option value="Utilisateur">Utilisateur</option>
            <option value="Bénévole">Bénévole</option>
        </select>

        <label for="site">Choisissez votre ville :</label>
        <select id="site" name="site" class="form__input" required>
            <option value="">--Sélectionner un site--</option>
            <?php foreach ($sites as $site): ?>
                <option value="<?php echo $site['id_site']; ?>"><?php echo htmlspecialchars($site['nom_site']); ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Section pour les champs supplémentaires en fonction du rôle -->
        <div id="additional-fields"></div>

        <button class="form__button button submit" type="submit">Compléter l'inscription</button>
    </form>
</div>

<script>
    function updateForm() {
        const role = document.getElementById('role').value;
        const additionalFields = document.getElementById('additional-fields');

        additionalFields.innerHTML = '';

        if (role === 'Commerçant') {
            additionalFields.innerHTML = `
                <input class="form__input" type="text" name="nom_entreprise" placeholder="Nom de l'entreprise" required>
                <textarea class="form__input" name="type_magasin" placeholder="Précisez le type de magasin" required></textarea>
                <input class="form__input" type="text" name="adresse" placeholder="Adresse du magasin" required>
                <input class="form__input" type="text" name="ville" placeholder="Ville" required>
                <input class="form__input" type="text" name="siret" placeholder="Numéro SIRET" required>
            `;
        } else if (role === 'Utilisateur' || role === 'Bénévole') {
            additionalFields.innerHTML = `
                <input class="form__input" type="text" name="prenom" placeholder="Prénom" required>
                <textarea class="form__input" name="description" placeholder="Description de vous-même" required></textarea>
                <input class="form__input" type="text" name="adresse" placeholder="Adresse" required>
                <input class="form__input" type="text" name="ville" placeholder="Ville" required>
                <input class="form__input" type="text" name="pays" placeholder="Pays" required>
                <input class="form__input" type="text" name="code_postal" placeholder="Code Postal" required>
            `;
        }
    }
</script>
</body>
</html>
