<?php

// Fonctions utilitaires globales

/**
 * Nettoie les entrées utilisateur pour éviter les injections XSS.
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Redirige l'utilisateur vers une autre page.
 */
function redirect($url) {
    header("Location: index.php?route=" . ltrim($url, '/'));
    exit();
}

/**
 * Vérifie si un utilisateur est authentifié.
 */
function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

/**
 * Récupère le rôle de l'utilisateur (par défaut 'guest').
 */
function getUserRole() {
    return $_SESSION['role'] ?? 'guest';
}

?>
