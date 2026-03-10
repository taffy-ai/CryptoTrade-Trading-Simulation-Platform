<?php
// Chargement des fichiers de configuration
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';

// Démarrage de la session
session_start();

// Chargement des routes
require_once __DIR__ . '/routes/web.php';
?>
