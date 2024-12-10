<?php
session_start();

// Vérifier si une session est active et la détruire
if (session_status() === PHP_SESSION_ACTIVE) {
    // Supprimer toutes les variables de session
    $_SESSION = array();

    // Si nécessaire, détruire aussi le cookie de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Détruire la session
    session_destroy();
}

// Rediriger vers la page de connexion ou la page d'accueil
header("Location: index.html");
exit;
