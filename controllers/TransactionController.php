<?php
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Wallet.php';
require_once __DIR__ . '/../models/Crypto.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../models/TransactionLimit.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../logs/logger.php';

use Dompdf\Dompdf;


class TransactionController {
    public function buy() {
        if (!isset($_SESSION['user_id'])) redirect('/login');

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);
        if (!$user['two_factor_enabled']) redirect('/2fa-setup');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $cryptoId = sanitizeInput($_POST['crypto_id']);
            $usdAmount = sanitizeInput($_POST['usd_amount']);

            $cryptoModel = new Crypto();
            $walletModel = new Wallet();
            $transactionModel = new Transaction();
            $limitModel = new TransactionLimit();
            $transactionLimit = $limitModel->getUserLimit($userId);
            $transactionCountToday = $transactionModel->countTodayByUser($userId);

            if ($transactionCountToday >= $transactionLimit) {
                $_SESSION['error_message'] = "Limite quotidienne atteinte ({$transactionLimit} transactions)";
                redirect('/trade');
                return;
            }

            $usdtId = $walletModel->getUSDTId();
            $currentPrice = $cryptoModel->getCurrentPrice($cryptoId);
            $cryptoUnits = $usdAmount / $currentPrice;
            $usdtBalance = $walletModel->getBalance($userId, $usdtId);

            if ($usdtBalance >= $usdAmount) {
                $walletModel->updateBalance($userId, $usdtId, -$usdAmount);
                $walletModel->updateBalance($userId, $cryptoId, $cryptoUnits);
                $transactionModel->create($userId, $cryptoId, 'buy', $cryptoUnits, $currentPrice);
                $_SESSION['success_message'] = "Achat effectué : " . number_format($cryptoUnits, 6);
            } else {
                $_SESSION['error_message'] = "Solde USDT insuffisant";
            }

            redirect('/trade');
        }
    }

    public function sell() {
        if (!isset($_SESSION['user_id'])) redirect('/login');

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);
        if (!$user['two_factor_enabled']) redirect('/2fa-setup');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $cryptoId = sanitizeInput($_POST['crypto_id']);
            $amount = sanitizeInput($_POST['amount']);

            $walletModel = new Wallet();
            $cryptoModel = new Crypto();
            $transactionModel = new Transaction();
            $limitModel = new TransactionLimit();
            $transactionLimit = $limitModel->getUserLimit($userId);
            $transactionCountToday = $transactionModel->countTodayByUser($userId);

            if ($transactionCountToday >= $transactionLimit) {
                $_SESSION['error_message'] = "Limite quotidienne atteinte";
                redirect('/trade');
                return;
            }

            $usdtId = $walletModel->getUSDTId();
            $currentPrice = $cryptoModel->getCurrentPrice($cryptoId);
            $cryptoBalance = $walletModel->getBalance($userId, $cryptoId);

            if ($cryptoBalance >= $amount) {
                $walletModel->updateBalance($userId, $cryptoId, -$amount);
                $walletModel->updateBalance($userId, $usdtId, $amount * $currentPrice);
                $transactionModel->create($userId, $cryptoId, 'sell', $amount, $currentPrice);
                $_SESSION['success_message'] = "Vente effectuée";
            } else {
                $_SESSION['error_message'] = "Solde crypto insuffisant";
            }

            redirect('/trade');
        }
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) redirect('/login');

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);
        if (!$user['two_factor_enabled']) redirect('/2fa-setup');

        $cryptoModel = new Crypto();
        $cryptos = $cryptoModel->getAllWithCurrentPrice();

        require_once __DIR__ . '/../views/trade.php';
    }

    public function history() {
        if (!isset($_SESSION['user_id'])) redirect('/login');

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);
        if (!$user['two_factor_enabled']) redirect('/2fa-setup');

        $transactionModel = new Transaction();
        $transactions = $transactionModel->getByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../views/transactions.php';
    }

    public function exportPDF() {
        if (!isset($_SESSION['user_id'])) redirect('/login');
    
        require_once __DIR__ . '/../vendor/autoload.php'; // Dompdf
        require_once __DIR__ . '/../logs/logger.php';
    
        $transactionModel = new Transaction();
        $transactions = $transactionModel->getByUser($_SESSION['user_id']);
    
        ob_start();
        require __DIR__ . '/../views/pdf/transactions-pdf.php';
        $html = ob_get_clean();
    
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("transactions.pdf");
    
        logAction("Exportation des transactions PDF");
    }
    
}
?>
