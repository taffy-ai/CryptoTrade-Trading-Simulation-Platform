<?php
require_once __DIR__ . '/../config/stripe_config.php';
require_once __DIR__ . '/../models/Wallet.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/User.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

class PaymentController {
    public function index() {
        if (!isset($_SESSION['user_id'])) redirect('/login');

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);
        if (!$user['two_factor_enabled']) redirect('/2fa-setup');

        require_once __DIR__ . '/../views/deposit.php';
    }

    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $amount = floatval($_POST['amount']);
            $userId = $_SESSION['user_id'];

            $paymentModel = new Payment();
            $paymentId = $paymentModel->create($userId, $amount, 'pending', null);

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'CryptoTrade Deposit'],
                        'unit_amount' => $amount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://localhost/CryptoTrade/index.php?route=payment-success&payment_id=' . $paymentId,
                'cancel_url' => 'http://localhost/CryptoTrade/index.php?route=deposit',
            ]);

            header("Location: " . $session->url);
            exit;
        }
    }

    public function paymentSuccess() {
        if (isset($_GET['payment_id']) && isset($_SESSION['user_id'])) {
            $paymentId = intval($_GET['payment_id']);
            $userId = $_SESSION['user_id'];

            $paymentModel = new Payment();
            $payment = $paymentModel->getById($paymentId);

            if ($payment && $payment['status'] === 'pending') {
                $paymentModel->updateStatus($paymentId, 'completed');

                $walletModel = new Wallet();
                $usdtId = $walletModel->getUSDTId();
                if ($usdtId !== null) {
                    $walletModel->updateBalance($userId, $usdtId, $payment['amount']);
                    $_SESSION['success_message'] = "Dépôt réussi : {$payment['amount']} USDT.";
                } else {
                    $_SESSION['error_message'] = "Erreur : USDT n'est pas configuré dans la base de données.";
                }
            }

            redirect('/dashboard');
        }
    }
}
?>
