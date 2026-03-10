<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Configurer 2FA - CryptoTrade</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="/CryptoTrade/public/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body class="bg-dark text-white" style="min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center;">

<div class="card shadow login-card animate__animated animate__fadeInUp w-100" style="max-width: 400px;">

    <h1 class="mb-4 fw-bold text-uppercase text-center">Configurer 2FA</h1>
    <p class="text-white mb-3 text-center">Scanne le QR Code dans Google Authenticator</p>

    <div class="text-center mb-4">
        <img src="<?= $qrCodeUrl ?>" alt="QR Code" class="img-fluid border rounded shadow" style="max-width: 200px;">
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="text-danger text-center mb-3 fw-bold">
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?route=2fa-confirm">
        <div class="mb-3 text-start">
            <label for="code" class="form-label">Code à 6 chiffres</label>
            <input type="text" name="code" id="code" class="form-control" placeholder="Entre le code" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Valider le code</button>
    </form>

    <div class="mt-3 text-center">
        <a href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>
