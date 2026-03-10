<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Accueil - CryptoTrade</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSS  -->
    <link rel="stylesheet" href="/CryptoTrade/public/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body class="d-flex align-items-center justify-content-center" style="height: 100vh; overflow: hidden; margin: 0;">

<div class="card shadow login-card animate__animated animate__fadeInUp w-100 text-center" style="max-width: 400px;">

    <h1 class="mb-4 fw-bold text-uppercase">Bienvenue CryptoTrade</h1>

    <p class="text-white mb-2">Suivez les prix du marché et effectuez vos transactions facilement</p>
    <p class="text-white mb-4">Accédez aux meilleures opportunités d'investissement en cryptomonnaies</p>

    <div class="d-grid gap-2 mb-3">
        <a href="index.php?route=cryptos" class="btn btn-primary w-100">Voir les Cryptos</a>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="d-grid gap-2 mb-2">
            <a href="index.php?route=transactions" class="btn btn-primary w-100">Mes Transactions</a>
        </div>
        <div class="d-grid gap-2">
            <a href="index.php?route=logout" class="btn btn-secondary w-100">Déconnexion</a>
        </div>
    <?php else: ?>
        <div class="d-grid gap-2 mb-2">
            <a href="index.php?route=login" class="btn btn-primary w-100">Connexion</a>
        </div>
        <div class="d-grid gap-2">
            <a href="index.php?route=register" class="btn btn-secondary w-100">Inscription</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
