<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/db.php';
include 'templates/header.php';

// Récupérer les demandes en attente
$stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE statut_demande = 'en attente' AND nouveau_role IS NOT NULL");
$stmt->execute();
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gérer l'acceptation ou le rejet des demandes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_utilisateur = $_POST['id_utilisateur'];
    $action = $_POST['action'];
    $new_statut = $action === 'accepter' ? 'acceptée' : 'rejetée';

    // Mettre à jour le statut de la demande et le rôle de l'utilisateur
    $stmt = $conn->prepare("UPDATE Utilisateurs SET role = IF(:statut = 'acceptée', nouveau_role, role), statut_demande = :statut, nouveau_role = NULL, message_demande = NULL WHERE id_utilisateur = :id_utilisateur");
    $stmt->bindParam(':statut', $new_statut);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office</title>
</head>
<body>
    <h1>Gestion des demandes de changement de rôle</h1>

    <?php foreach ($demandes as $demande): ?>
    <div class="demande">
        <p><?php echo htmlspecialchars($demande['prenom'] ?? ''); ?> souhaite devenir <?php echo htmlspecialchars($demande['nouveau_role'] ?? ''); ?>.</p>
        <p>Message : <?php echo htmlspecialchars($demande['message_demande'] ?? ''); ?></p>
        <form method="POST">
            <input type="hidden" name="id_utilisateur" value="<?php echo $demande['id_utilisateur'] ?? ''; ?>">
            <button name="action" value="accepter">Accepter</button>
            <button name="action" value="rejeter">Rejeter</button>
        </form>
    </div>
<?php endforeach; ?>

</body>
</html>
