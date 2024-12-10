<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../../back/includes/db.php'; // Connexion à la base de données

if (!isset($_POST['id_service']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
    exit;
}

$id_service = $_POST['id_service'];
$action = $_POST['action'];
$id_utilisateur_beneficiaire = $_SESSION['user_id'];

try {
    if ($action == 'accept') {
        $stmt = $conn->prepare("
            UPDATE services
            SET etat = 'accepté', id_utilisateur_beneficiaire = :id_utilisateur_beneficiaire
            WHERE id_service = :id_service
        ");
        $stmt->bindParam(':id_utilisateur_beneficiaire', $id_utilisateur_beneficiaire, PDO::PARAM_INT);
    } elseif ($action == 'refuse') {
        $stmt = $conn->prepare("
            UPDATE services
            SET etat = 'refusé'
            WHERE id_service = :id_service
        ");
    } else {
        echo json_encode(['success' => false, 'message' => 'Action inconnue.']);
        exit;
    }

    $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Action effectuée avec succès.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur SQL : ' . $e->getMessage()]);
}
