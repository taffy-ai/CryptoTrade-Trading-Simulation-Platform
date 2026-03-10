<?php
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../models/Crypto.php';
require_once __DIR__ . '/../config/helpers.php';

class AlertController {
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $cryptoId = sanitizeInput($_POST['crypto_id']);
            $targetPrice = sanitizeInput($_POST['target_price']);
            $action = sanitizeInput($_POST['action']);

            $alertModel = new Alert();
            $alertModel->create($userId, $cryptoId, $targetPrice, $action);

            $_SESSION['success_message'] = "Alert set successfully!";
            redirect('/trade');
        } else {
            redirect('/login');
        }
    }

    public function fetchActiveAlerts() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            exit;
        }

        $alertModel = new Alert();
        $alerts = $alertModel->getAllActiveWithCryptoPrices($_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode($alerts);
        exit;
    }

    public function markAlertTriggered() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alert_id'])) {
            $alertId = intval($_POST['alert_id']);

            $alertModel = new Alert();
            $alertModel->markTriggered($alertId);

            echo json_encode(['status' => 'success']);
            exit;
        }

        echo json_encode(['status' => 'error']);
        exit;
    }
}
