<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../../back/includes/db.php'; // Connexion à la base de données

header('Content-Type: application/json'); // Assure que le contenu renvoyé est en JSON

$response = ['success' => false, 'message' => 'Erreur lors de la réservation.'];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Session utilisateur invalide. Veuillez vous reconnecter.';
    echo json_encode($response);
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

if (isset($_POST['id_collecte'], $_POST['quantite']) && is_numeric($_POST['id_collecte']) && is_numeric($_POST['quantite'])) {
    $id_collecte = (int)$_POST['id_collecte'];
    $quantite_reservee = (int)$_POST['quantite'];

    // Récupération des informations du panier dans la base de données
    try {
        $stmt = $conn->prepare("SELECT quantite FROM collectes WHERE id_collecte = :id_collecte AND etat = 'à la vente'");
        $stmt->bindParam(':id_collecte', $id_collecte);
        $stmt->execute();
        $collecte = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($collecte && $collecte['quantite'] >= $quantite_reservee) {
            $nouvelle_quantite = $collecte['quantite'] - $quantite_reservee;
            $nouvel_etat = $nouvelle_quantite > 0 ? 'à la vente' : 'en cours';

            // Mise à jour de la quantité et de l'état du panier
            $stmt = $conn->prepare("
                UPDATE collectes
                SET quantite = :nouvelle_quantite, etat = :nouvel_etat, id_utilisateur = :id_utilisateur, quantite_reservee = :quantite_reservee
                WHERE id_collecte = :id_collecte
            ");
            $stmt->bindParam(':nouvelle_quantite', $nouvelle_quantite);
            $stmt->bindParam(':nouvel_etat', $nouvel_etat);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur);
            $stmt->bindParam(':quantite_reservee', $quantite_reservee);
            $stmt->bindParam(':id_collecte', $id_collecte);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Panier réservé avec succès.';
            } else {
                $errorInfo = $stmt->errorInfo();
                $response['message'] = 'Erreur lors de la mise à jour du panier dans la base de données : ' . $errorInfo[2];
            }
        } else {
            $response['message'] = 'Quantité insuffisante ou panier non disponible.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Erreur SQL : ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Paramètres invalides.';
}

echo json_encode($response);
