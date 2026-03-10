<?php
if (!isset($_SESSION['user_id'])) {
    echo "<p>Access denied. Please <a href='index.php?route=login'>log in</a>.</p>";
    exit;
}

require_once __DIR__ . '/../models/Wallet.php';
require_once __DIR__ . '/../models/Payment.php';

$walletModel = new Wallet();
$usdtId = $walletModel->getUSDTId();
$usdtBalance = $walletModel->getBalance($_SESSION['user_id'], $usdtId);

$paymentModel = new Payment();
$payments = $paymentModel->getByUser($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Deposit USDT - CryptoTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/CryptoTrade/public/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body>

<div class="container my-5 text-center">

    <h1 class="mb-4 fw-bold text-uppercase">Deposit</h1>

    <nav class="mb-4 d-flex justify-content-center flex-wrap">
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=dashboard">Dashboard</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=trade">Trade Cryptos</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=deposit">Deposit Funds</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=transactions">My Transactions</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a class="btn btn-danger me-2 mb-2" href="index.php?route=admin">Admin Panel</a>
        <?php endif; ?>
        <a class="btn btn-secondary mb-2" href="index.php?route=logout">Logout</a>
    </nav>

    <div class="card bg-dark text-white mb-5 mx-auto animate__animated animate__fadeIn" style="max-width: 300px; border: 1px solid #fcd535; box-shadow: 0 0 15px rgba(252, 213, 53, 0.2);">
        <div class="card-body">
            <h5 class="card-title text-uppercase fw-bold mb-3" style="color: #fcd535;">My Balance</h5>
            <p class="card-text display-6 fw-bold" style="color: #ffffff;"><?= number_format($usdtBalance, 2) ?> <span style="color: #fcd535;">USDT</span></p>
        </div>
    </div>


    <h2 class="mb-3 fw-semibold text-uppercase">Deposit Funds</h2>
    <form method="POST" action="index.php?route=process-payment" class="row g-2 justify-content-center mb-5 animate__animated animate__fadeIn">
        <div class="col-md-3">
            <input type="number" name="amount" class="form-control form-control-sm" placeholder="Amount (min 5 USDT)" step="0.01" min="5" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success btn-sm w-100">Deposit via Stripe</button>
        </div>
    </form>

    <h2 class="mb-3 fw-semibold text-uppercase">Deposit History</h2>
    <?php if (empty($payments)): ?>
        <div class="alert alert-info w-50 mx-auto animate__animated animate__fadeIn">No deposits yet</div>
    <?php else: ?>
        <div class="table-responsive animate__animated animate__fadeIn">
            <table class="table table-dark table-striped text-center">
                <thead>
                    <tr>
                        <th>Amount (USDT)</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= number_format($payment['amount'], 2) ?> USDT</td>
                            <td><?= htmlspecialchars(ucfirst($payment['status'])) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($payment['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
