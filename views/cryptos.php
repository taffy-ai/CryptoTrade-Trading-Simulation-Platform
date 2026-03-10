<?php
require_once __DIR__ . '/../models/Crypto.php';

$cryptoModel = new Crypto();
$cryptos = $cryptoModel->getAllWithCurrentPrice();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Marché des Cryptomonnaies - CryptoTrade</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="/CryptoTrade/public/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- jQuery et JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/CryptoTrade/public/js/live-prices.js"></script>
</head>

<body>

<div class="card shadow login-card animate__animated animate__fadeInUp w-100 text-center" style="max-width: 600px;">

    <h1 class="mb-4 fw-bold text-uppercase">Marché Crypto</h1>

    <nav class="mb-4 d-flex justify-content-center flex-wrap">
        <a class="btn btn-warning me-2 mb-2" href="index.php">Accueil</a>
        <a class="btn btn-warning mb-2" href="index.php?route=transactions">Mes Transactions</a>
    </nav>

    <!--  Liste des cryptos -->
    <?php if (!empty($cryptos)): ?>
        <div class="table-responsive">
            <table class="table table-dark table-striped text-center align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Symbole</th>
                        <th>Prix Actuel ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cryptos as $crypto): ?>
                        <tr>
                            <td><?= htmlspecialchars($crypto['name']) ?></td>
                            <td><?= htmlspecialchars($crypto['symbol']) ?></td>
                            <td id="crypto-price-<?= $crypto['id'] ?>">
                                <?= number_format($crypto['current_price'], 2) ?> $
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center text-warning">
            Aucune cryptomonnaie disponible pour le moment
        </div>
    <?php endif; ?>

</div>

</body>
</html>
