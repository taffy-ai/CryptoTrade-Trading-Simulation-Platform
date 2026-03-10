<?php
require_once __DIR__ . '/../models/Wallet.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Crypto.php';
require_once __DIR__ . '/../models/User.php';

class DashboardController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);

        // Bloque tout sauf la 2FA si pas activée
        if (!$user['two_factor_enabled']) {
            $_SESSION['force_2fa_only'] = true;
        } else {
            unset($_SESSION['force_2fa_only']);
        }

        $walletModel = new Wallet();
        $transactionModel = new Transaction();
        $cryptoModel = new Crypto();

        $wallets = $walletModel->getUserWallet($_SESSION['user_id']);
        $transactions = $transactionModel->getByUser($_SESSION['user_id']);
        $cryptos = $cryptoModel->getAllWithCurrentPrice();

        require_once __DIR__ . '/../views/dashboard.php';
    }

    public function getPortfolioTotal() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['total' => 0]);
            exit;
        }
    
        $walletModel = new Wallet();
        $cryptoModel = new Crypto();
    
        $wallets = $walletModel->getUserWallet($_SESSION['user_id']);
        $cryptos = $cryptoModel->getAllWithCurrentPrice();
    
        $total = 0;
        foreach ($wallets as $wallet) {
            foreach ($cryptos as $crypto) {
                if ($crypto['id'] == $wallet['crypto_id']) {
                    $total += $wallet['balance'] * $crypto['current_price'];
                }
            }
        }
    
        header('Content-Type: application/json');
        echo json_encode(['total' => $total]);
        exit;
    }
    
}
