<?php
session_start();
include '../../../back/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telephone = $_POST['telephone'];
    $role = $_POST['role'];
    $site = $_POST['site']; // Utilisé pour les commerçants

    // Hachage du mot de passe
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Validation des champs obligatoires
    if (empty($username) || empty($email) || empty($password) || empty($telephone) || empty($role)) {
        die("Tous les champs obligatoires doivent être remplis.");
    }

    // Insérer l'utilisateur selon le rôle
    try {
        $conn->beginTransaction();

        if ($role === 'Commerçant') {
            // Récupérer les données spécifiques au commerçant
            $nom_entreprise = $_POST['nom_entreprise'];
    $type_magasin = $_POST['type_magasin'];
    $adresse = $_POST['adresse'];
    $siret = $_POST['siret'];
    $mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validation des champs spécifiques au commerçant
    if (empty($nom_entreprise) || empty($type_magasin) || empty($adresse) || empty($siret) || empty($site)) {
        throw new Exception("Tous les champs spécifiques au commerçant doivent être remplis.");
    }

    // Insertion du commerçant
    $stmt = $conn->prepare("
        INSERT INTO commercants (nom_entreprise, adresse, telephone, email, id_site, siret, ville, type_magasin, mot_de_passe)
        VALUES (:nom_entreprise, :adresse, :telephone, :email, :site, :siret, :ville, :type_magasin, :mot_de_passe)
    ");
    $stmt->bindParam(':nom_entreprise', $nom_entreprise);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':site', $site);
    $stmt->bindParam(':siret', $siret);
    $stmt->bindParam(':ville', $ville);
    $stmt->bindParam(':type_magasin', $type_magasin);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe);

        } elseif ($role === 'Utilisateur' || $role === 'Bénévole') {
            // Récupérer les données spécifiques aux utilisateurs et bénévoles
            $prenom = $_POST['prenom'];
            $description = $_POST['description'];
            $adresse = $_POST['adresse'];
            $ville = $_POST['ville'];
            $pays = $_POST['pays'];
            $code_postal = $_POST['code_postal'];

            // Validation des champs spécifiques aux utilisateurs/bénévoles
            if (empty($prenom) || empty($description) || empty($adresse) || empty($ville) || empty($pays) || empty($code_postal)) {
                throw new Exception("Tous les champs spécifiques à l'utilisateur ou bénévole doivent être remplis.");
            }

            // Insertion de l'utilisateur ou bénévole
            $stmt = $conn->prepare("
                INSERT INTO utilisateurs (nom, email, mot_de_passe, telephone, role, prenom, description, adresse, ville, pays, code_postal)
                VALUES (:username, :email, :password, :telephone, :role, :prenom, :description, :adresse, :ville, :pays, :code_postal)
            ");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hashed);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':pays', $pays);
            $stmt->bindParam(':code_postal', $code_postal);
        }

        // Exécution de la requête
        if ($stmt->execute()) {
            $conn->commit();
            // Rediriger vers la page de connexion après une inscription réussie
            header("Location: connexion.php");
            exit(); // Assurez-vous de quitter le script après une redirection
        } else {
            $conn->rollBack();
            echo "Erreur lors de l'inscription.";
        }

    } catch (Exception $e) {
        $conn->rollBack();
        echo "Erreur : " . $e->getMessage();
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Erreur SQL : " . $e->getMessage();
    }
}
?>
