<?php
session_start();
include '../../../back/includes/db.php'; // Connexion à la base de données

// Vérification si le formulaire est soumis via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $id_service_user = $_POST['id_service_user'] ?? null;
    $id_service_other = $_POST['id_service_other'] ?? null;

    // Vérification que les données obligatoires sont présentes
    if (!$id_service_user || !$id_service_other) {
        echo "Données du formulaire manquantes.";
        exit();
    }

    try {
        // Mise à jour du service pour signaler qu'une demande d'échange est en attente
        $stmt = $conn->prepare("
            UPDATE services
            SET etat = 'en attente', id_service_autre = :id_service_other
            WHERE id_service = :id_service_user
        ");
        $stmt->bindParam(':id_service_other', $id_service_other);
        $stmt->bindParam(':id_service_user', $id_service_user);

        // Après la requête INSERT pour l'échange
if ($stmt->execute()) {
    // Redirection vers la page d'historique d'échanges
    header("Location: historique_echanges.php");
    exit; // Assurez-vous d'utiliser exit après une redirection pour éviter tout code supplémentaire
} else {
    echo "Erreur lors de l'envoi de la demande d'échange.";
}

    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Méthode non autorisée.";
}
