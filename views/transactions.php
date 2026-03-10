<?php
if (!isset($_SESSION['user_id'])) {
    echo "<p>Access denied. Please <a href='index.php?route=login'>log in</a>.</p>";
    exit;
}

require_once __DIR__ . '/../models/Transaction.php';
$transactionModel = new Transaction();
$transactions = $transactionModel->getByUser($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Transaction History - CryptoTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/CryptoTrade/public/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body>

<div class="container my-5 text-center">

    <h1 class="mb-4 fw-bold text-uppercase">Transaction History</h1>

    <nav class="mb-4 d-flex justify-content-center flex-wrap">
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=dashboard">Dashboard</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=trade">Trade Cryptos</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=deposit">Deposit Funds</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=transactions">My Transactions</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=export-transactions">Export in PDF</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a class="btn btn-danger me-2 mb-2" href="index.php?route=admin">Admin Panel</a>
        <?php endif; ?>
        <a class="btn btn-secondary mb-2" href="index.php?route=logout">Logout</a>
    </nav>

    <?php if (!empty($transactions)): ?>
        <div class="table-responsive animate__animated animate__fadeIn">
            <table class="table table-dark table-striped text-center">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Crypto</th>
                        <th>Amount</th>
                        <th>Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?= htmlspecialchars(ucfirst($transaction['type'])) ?></td>
                            <td><?= htmlspecialchars($transaction['name']) ?> (<?= htmlspecialchars($transaction['symbol']) ?>)</td>
                            <td><?= number_format($transaction['amount'], 6) ?></td>
                            <td>$<?= number_format($transaction['price_at_transaction'], 2) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($transaction['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info w-50 mx-auto animate__animated animate__fadeIn">
            No transactions recorded
        </div>
    <?php endif; ?>

</div>

</body>
</html>
