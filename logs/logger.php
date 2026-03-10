<?php
function logAction($action) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) return;

    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'] ?? 'N/A';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP inconnue';
    $browser = $_SERVER['HTTP_USER_AGENT'] ?? 'Navigateur inconnu';

    $logLine = "$timestamp | UserID: $userId | Username: $username | IP: $ip | Action: $action | Browser: $browser" . PHP_EOL;

    $logDir = __DIR__ . '/actions.log';

    file_put_contents($logDir, $logLine, FILE_APPEND | LOCK_EX);
}
