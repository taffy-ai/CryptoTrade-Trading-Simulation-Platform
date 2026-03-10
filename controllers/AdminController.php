<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Crypto.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/TransactionLimit.php';
require_once __DIR__ . '/../config/helpers.php';

class AdminController {
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            redirect('/dashboard');
            exit;
        }
    
        $userModel = new User();
        $cryptoModel = new Crypto();
        $transactionModel = new Transaction();
    
        $users = $userModel->getAll();
        $cryptos = $cryptoModel->getAll();
        $transactions = $transactionModel->getAll();
    
        // Charger les wallets par utilisateur correctement
        $wallets = [];
        foreach ($users as $user) {
            $wallets[$user['id']] = $userModel->getWalletsByUserId($user['id']);
        }
    
        require_once __DIR__ . '/../views/admin.php';
    }
    

    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
            $userId = $_POST['user_id'];
            $userModel = new User();
            $userModel->delete($userId);
        }
        redirect('/admin');
    }

    public function addCrypto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
            $name = $_POST['name'];
            $symbol = strtoupper($_POST['symbol']);
            $initialPrice = $_POST['initial_price'];
            $volatility = $_POST['volatility'];

            $cryptoModel = new Crypto();
            $cryptoModel->create($name, $symbol, $initialPrice, $volatility);
        }
        redirect('/admin');
    }

    public function deleteCrypto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
            $cryptoId = $_POST['crypto_id'];
            $cryptoModel = new Crypto();
            $cryptoModel->delete($cryptoId);
        }
        redirect('/admin');
    }

    public function setUserLimit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
            $userId = intval($_POST['user_id']);
            $limit = intval($_POST['limit']);

            $limitModel = new TransactionLimit();
            $limitModel->setUserLimit($userId, $limit);

            $_SESSION['success_message'] = "Limite mise à jour avec succès.";
            redirect('/admin');
        } else {
            redirect('/login');
        }
    }

    public function reset2FA() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $userId = intval($_POST['user_id']);
            $userModel = new User();
            $userModel->clear2FA($userId); // remet les champs 2FA à zéro
            $_SESSION['success_message'] = "Le 2FA a été désactivé et la demande supprimée";
        }
        redirect('/admin');
    }
    
    public function updateUserBalance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
            $userId = intval($_POST['user_id']);
            $cryptoId = intval($_POST['crypto_id']);
            $newBalance = floatval($_POST['balance']);
    
            $userModel = new User();
            $userModel->updateWalletBalance($userId, $cryptoId, $newBalance);
    
            $_SESSION['success_message'] = "Solde mis à jour avec succès";
            redirect('/admin');
        } else {
            redirect('/login');
        }
    }

    public function getUserBalance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
            $userId = intval($_POST['user_id']);
            $cryptoId = intval($_POST['crypto_id']);
    
            $userModel = new User();
            $wallet = $userModel->getWalletBalanceDetails($userId, $cryptoId);
    
            header('Content-Type: application/json');
            echo json_encode([
                'balance' => $wallet['balance'] ?? 0,
                'crypto_name' => $wallet['crypto_name'] ?? 'unité'
            ]);
            exit;
        }
    }
}
