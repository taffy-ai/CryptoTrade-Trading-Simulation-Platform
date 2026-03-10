<?php
if (!isset($_SESSION['user_id'])) {
    echo "<p>Access denied. Please <a href='index.php?route=login'>log in</a>.</p>";
    exit;
}

require_once 'models/User.php';
$userModel = new User();
$user = $userModel->getById($_SESSION['user_id']);
$is2FAEnabled = $user['two_factor_enabled'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard - CryptoTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/CryptoTrade/public/js/live-prices.js"></script>
    <link rel="stylesheet" href="/CryptoTrade/public/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body>
<div class="container my-5 text-center">

    <h1 class="mb-4 fw-bold text-uppercase">Dashboard CryptoTrade</h1>

    <nav class="mb-4 d-flex justify-content-center flex-wrap">
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=dashboard">Dashboard</a>
        <?php if ($is2FAEnabled): ?>
            <a class="btn btn-warning me-2 mb-2" href="index.php?route=trade">Trade Cryptos</a>
            <a class="btn btn-warning me-2 mb-2" href="index.php?route=deposit">Deposit Funds</a>
            <a class="btn btn-warning me-2 mb-2" href="index.php?route=transactions">My Transactions</a>
            <a class="btn btn-warning me-2 mb-2" href="index.php?route=portfolio-report">Rapport</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && $is2FAEnabled): ?>
            <a class="btn btn-danger me-2 mb-2" href="index.php?route=admin">Admin Panel</a>
        <?php endif; ?>
        <a class="btn btn-secondary mb-2" href="index.php?route=logout">Logout</a>
    </nav>

    <?php if (!$is2FAEnabled): ?>
        <div class="alert alert-danger fw-bold">
            Vous devez activer la double authentification (2FA) pour accéder aux fonctionnalités de la plateforme
        </div>
        <a href="index.php?route=2fa-setup" class="btn btn-warning mb-4">Activer la vérification 2FA</a>
    <?php else: ?>

        <div id="ajax-alerts-container"></div>

        <!-- Portfolio -->
        <h2 class="mb-3 fw-semibold text-uppercase">My Portfolio</h2>
        <?php if (empty($wallets)): ?>
            <div class="alert alert-info">Aucun portefeuille disponible</div>
        <?php else: ?>
            <div class="table-responsive mb-5 animate__animated animate__fadeIn">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Crypto</th>
                            <th>Symbol</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wallets as $wallet): ?>
                            <tr>
                                <td><?= htmlspecialchars($wallet['name']) ?></td>
                                <td><?= htmlspecialchars($wallet['symbol']) ?></td>
                                <td><?= number_format($wallet['balance'], 6) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="portfolio-total-row">
                            <td colspan="2"><strong>Total Portfolio (USDT)</strong></td>
                            <td><strong><span id="total-portfolio-usd">$0.00</span></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Transactions -->
        <h2 class="mb-3 fw-semibold text-uppercase">Recent Transactions</h2>
        <?php if (empty($transactions)): ?>
            <div class="alert alert-info">Aucune transaction récente</div>
        <?php else: ?>
            <div class="table-responsive mb-5 animate__animated animate__fadeIn">
                <table class="table table-dark table-striped">
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
                        <?php foreach (array_slice($transactions, 0, 5) as $transaction): ?>
                            <tr>
                                <td><?= htmlspecialchars($transaction['type']) ?></td>
                                <td><?= htmlspecialchars($transaction['name']) ?> (<?= htmlspecialchars($transaction['symbol']) ?>)</td>
                                <td><?= number_format($transaction['amount'], 6) ?></td>
                                <td>$<?= number_format($transaction['price_at_transaction'], 2) ?></td>
                                <td><?= $transaction['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Market Overview -->
        <h2 class="mb-3 fw-semibold text-uppercase">Market Overview</h2>
        <?php if (empty($cryptos)): ?>
            <div class="alert alert-info">Aucune crypto-monnaie disponible</div>
        <?php else: ?>
            <div class="table-responsive animate__animated animate__fadeIn">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Crypto</th>
                            <th>Symbol</th>
                            <th>Current Price ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cryptos as $crypto): ?>
                            <tr>
                                <td><?= htmlspecialchars($crypto['name']) ?></td>
                                <td><?= htmlspecialchars($crypto['symbol']) ?></td>
                                <td id="crypto-price-<?= $crypto['id'] ?>"><?= number_format($crypto['current_price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>
</body>
</html>
