<?php
if (!isset($_SESSION['user_id'])) {
    echo "<p>Access denied. Please <a href='index.php?route=login'>log in</a>.</p>";
    exit;
}

if (!isset($_SESSION['triggered_alert_ids'])) {
    $_SESSION['triggered_alert_ids'] = [];
}

require_once __DIR__ . '/../models/Crypto.php';
require_once __DIR__ . '/../models/Alert.php';
require_once __DIR__ . '/../models/Wallet.php';

$cryptoModel = new Crypto();
$cryptos = $cryptoModel->getAllWithCurrentPrice();

$currentPrices = [];
foreach ($cryptos as $c) {
    $currentPrices[$c['id']] = $c['current_price'];
}

$alertModel = new Alert();
$activeAlerts = $alertModel->getActiveByUser($_SESSION['user_id']);

$walletModel = new Wallet();
$userWallets = $walletModel->getUserWallet($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Trade Cryptos - CryptoTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/CryptoTrade/public/js/live-prices.js"></script>
    <script src="/CryptoTrade/public/js/crypto-chart.js"></script>
    <link rel="stylesheet" href="/CryptoTrade/public/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body>

<div class="container my-5 text-center">

    <h1 class="mb-4 fw-bold text-uppercase">Trade Cryptos</h1>

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

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['trade_notifications'])): ?>
        <?php foreach ($_SESSION['trade_notifications'] as $notif): ?>
            <div class="alert alert-warning"><?= $notif; ?></div>
        <?php endforeach; unset($_SESSION['trade_notifications']); ?>
    <?php endif; ?>

    <div id="ajax-alerts-container"></div>

    <!-- Chart -->
    <h2 class="mb-4 fw-semibold text-uppercase">Live Price Chart</h2>
    <div class="crypto-chart-container mb-5 animate__animated animate__fadeIn">
        <canvas id="cryptoChart"></canvas>
    </div>

    <!-- Buy Crypto -->
    <h2 class="mb-3 fw-semibold text-uppercase">Buy Crypto</h2>
    <form method="POST" action="index.php?route=buy" class="row g-2 justify-content-center mb-5 animate__animated animate__fadeIn">
        <div class="col-md-3">
            <select name="crypto_id" id="buy-crypto-select" class="form-select form-select-sm" required>
                <option value="">Select Crypto</option>
                <?php foreach ($cryptos as $crypto): ?>
                    <option value="<?= $crypto['id'] ?>"><?= $crypto['name'] ?> (<?= $crypto['symbol'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="usd_amount" class="form-control form-control-sm" placeholder="Amount in USDT" step="0.01" required>
            <small class="d-block mt-1" id="buy-crypto-price">Select a crypto</small>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success btn-sm w-100">Buy</button>
        </div>
    </form>

    <!-- Sell Crypto -->
    <h2 class="mb-3 fw-semibold text-uppercase">Sell Crypto</h2>
    <form method="POST" action="index.php?route=sell" class="row g-2 justify-content-center mb-5 animate__animated animate__fadeIn">
        <div class="col-md-3">
            <select name="crypto_id" id="sell-crypto-select" class="form-select form-select-sm" required>
                <option value="">Select Crypto</option>
                <?php foreach ($cryptos as $crypto): ?>
                    <option value="<?= $crypto['id'] ?>"><?= $crypto['name'] ?> (<?= $crypto['symbol'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="amount" class="form-control form-control-sm" placeholder="Amount to Sell" step="0.0001" required>
            <small class="d-block mt-1" id="sell-crypto-price">Select a crypto</small>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-danger btn-sm w-100">Sell</button>
        </div>
    </form>

    <!-- Price Alert -->
    <h2 class="mb-3 fw-semibold text-uppercase">Set Price Alert</h2>
    <form method="POST" action="index.php?route=create-alert" class="row g-2 justify-content-center mb-5 animate__animated animate__fadeIn">
        <div class="col-md-3">
            <select name="crypto_id" class="form-select form-select-sm" id="alert-crypto-select" required>
                <option value="">Cryptocurrency</option>
                <?php foreach ($cryptos as $crypto): ?>
                    <option value="<?= $crypto['id'] ?>"><?= $crypto['name'] ?> (<?= $crypto['symbol'] ?>)</option>
                <?php endforeach; ?>
            </select>
            <small class="d-block mt-1 text-white" id="alert-crypto-price">Select a crypto</small>
        </div>

        <div class="col-md-3">
            <input type="number" name="target_price" class="form-control form-control-sm" placeholder="Target Price ($)" step="0.01" required>
        </div>
        <div class="col-md-2">
            <select name="action" class="form-select form-select-sm" required>
                <option value="buy">Buy</option>
                <option value="sell">Sell</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-warning btn-sm w-100">Set Alert</button>
        </div>
    </form>

    <!-- Stop-Loss -->
    <h2 class="mb-3 fw-semibold text-uppercase">Set Stop-Loss</h2>
    <form method="POST" action="index.php?route=create-stop-loss" class="row g-2 justify-content-center animate__animated animate__fadeIn">
        <div class="col-md-3">
            <select name="crypto_id" class="form-select form-select-sm" id="stop-loss-crypto-select" required>
                <option value="">Cryptocurrency</option>
                <?php foreach ($userWallets as $wallet): ?>
                    <?php if ($wallet['balance'] > 0): ?>
                        <option value="<?= $wallet['crypto_id'] ?>">
                            <?= htmlspecialchars($wallet['name']) ?> (<?= htmlspecialchars($wallet['symbol']) ?>) - Balance: <?= number_format($wallet['balance'], 6) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <small class="d-block mt-1" id="stop-loss-current-price">Select a crypto</small>
        </div>

        <div class="col-md-3">
            <input type="number" name="target_price" class="form-control form-control-sm" placeholder="Stop Price ($)" step="0.01" required>
        </div>

        <div class="col-md-3">
            <input type="number" name="amount" class="form-control form-control-sm" placeholder="Amount to sell" step="0.0001" required>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-danger btn-sm w-100">Set Stop-Loss</button>
        </div>
    </form>

</div>

</body>
</html>
