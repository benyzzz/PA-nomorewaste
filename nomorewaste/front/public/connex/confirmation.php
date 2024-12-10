<?php
$role = isset($_GET['role']) ? htmlspecialchars($_GET['role']) : 'utilisateur';

if ($role === 'Commerçant') {
    // Rediriger vers une page spécifique pour les commerçants
    header("Location: commerçant_dashboard.php");
    exit;
} elseif ($role === 'Utilisateur') {
    // Rediriger vers une page spécifique pour les utilisateurs
    header("Location: utilisateur_dashboard.php");
    exit;
} elseif ($role === 'Bénévole') {
    // Rediriger vers une page spécifique pour les bénévoles
    header("Location: bénévole_dashboard.php");
    exit;
} else {
    // Par défaut, vous pouvez rediriger vers une page d'accueil générale
    header("Location: index.php");
    exit;
}
?>
