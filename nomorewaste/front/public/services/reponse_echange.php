<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_service = $_POST['id_service'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($id_service && $action) {
        try {
            if ($action === 'accepter') {
                // Accepter l'échange, mettre à jour les services
                $stmt = $conn->prepare("
                    UPDATE services
                    SET etat = 'accepté'
                    WHERE id_service = :id_service
                ");
                $stmt->bindParam(':id_service', $id_service);
                if ($stmt->execute()) {
                    echo "Échange accepté.";
                }
            } elseif ($action === 'refuser') {
                // Refuser l'échange, annuler la demande
                $stmt = $conn->prepare("
                    UPDATE services
                    SET etat = 'refusé'
                    WHERE id_service = :id_service
                ");
                $stmt->bindParam(':id_service', $id_service);
                if ($stmt->execute()) {
                    echo "Échange refusé.";
                }
            } else {
                echo "Action non valide.";
            }
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        echo "Données manquantes.";
    }
} else {
    echo "Méthode non autorisée.";
}
