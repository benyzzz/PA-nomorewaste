<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nom_service = $_POST['nom_service'];
    $description = $_POST['description'];
    $date_service = $_POST['date_service'];
    $quantite = $_POST['quantite'];

    $stmt = $conn->prepare("UPDATE Services SET nom_service = :nom_service, description = :description, date_service = :date_service, quantite = :quantite WHERE id_service = :id");
    $stmt->bindParam(':nom_service', $nom_service);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date_service', $date_service);
    $stmt->bindParam(':quantite', $quantite);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Service mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour du service.";
    }
}
?>
