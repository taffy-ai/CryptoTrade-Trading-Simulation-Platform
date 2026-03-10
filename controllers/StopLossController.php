<?php
require_once __DIR__ . '/../models/StopLoss.php';
require_once __DIR__ . '/../models/Wallet.php';
require_once __DIR__ . '/../models/Crypto.php';
require_once __DIR__ . '/../models/Transaction.php';

class StopLossController {
    public function create() {
        if (!isset($_SESSION['user_id'])) redirect('/login');

        $userId = $_SESSION['user_id'];
        $cryptoId = $_POST['crypto_id'];
        $targetPrice = $_POST['target_price'];
        $amount = $_POST['amount'];

        $amount = floatval($amount);

        $stopLossModel = new StopLoss();
        $stopLossModel->create($userId, $cryptoId, $targetPrice, $amount);

        $_SESSION['success_message'] = "Stop-loss créé avec succès";
        redirect('/trade');
    }

    public function checkStopLosses() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['message' => 'Unauthorized']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $stopLossModel = new StopLoss();
        $walletModel = new Wallet();
        $cryptoModel = new Crypto();
        $transactionModel = new Transaction();

        $stopLosses = $stopLossModel->getActiveByUser($userId);
        $cryptos = $cryptoModel->getAllWithCurrentPrice();

        $notifications = [];

        foreach ($stopLosses as $stopLoss) {
            foreach ($cryptos as $crypto) {
                if ($crypto['id'] == $stopLoss['crypto_id'] && $crypto['current_price'] <= $stopLoss['target_price']) {
                    $balance = $walletModel->getBalance($userId, $stopLoss['crypto_id']);
                    $amountToSell = min($stopLoss['amount'], $balance);

                    if ($amountToSell > 0) {
                        $walletModel->updateBalance($userId, $stopLoss['crypto_id'], -$amountToSell);

                        $usdtId = $walletModel->getUSDTId();
                        $walletModel->updateBalance($userId, $usdtId, $amountToSell * $crypto['current_price']);

                        $transactionModel->create($userId, $stopLoss['crypto_id'], 'sell', $amountToSell, $crypto['current_price']);

                        $stopLossModel->markAsTriggered($stopLoss['id']);

                        $notifications[] = "Stop-loss exécuté pour {$stopLoss['name']} — vendu {$amountToSell} {$stopLoss['symbol']} à {$crypto['current_price']}$";
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['notifications' => $notifications]);
    }
}
