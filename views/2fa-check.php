<?php if (!isset($_SESSION['pending_2fa_user'])) {
    header('Location: index.php?route=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>2FA Verification - CryptoTrade</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="/CryptoTrade/public/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body class="d-flex align-items-center justify-content-center" style="height: 100vh; overflow: hidden; margin: 0;">

<div class="card shadow login-card animate__animated animate__fadeInUp w-100" style="max-width: 400px;">

    <h1 class="mb-4 fw-bold text-uppercase text-center">2FA Verification</h1>
    <p class="text-white mb-3 text-center">Enter the 6-digit code from your Google Authenticator app</p>

    <!-- Message d'erreur -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="text-danger text-center mb-3 fw-bold">
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?route=verify-2fa">
        <div class="mb-3 text-start">
            <label for="code" class="form-label">2FA Code</label>
            <input type="text" name="code" id="code" class="form-control" placeholder="Enter your 6-digit code" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Verify</button>
    </form>

    <form method="POST" action="index.php?route=reset-2fa" class="mt-3">
        <button type="submit" class="btn btn-secondary w-100">Request 2FA Reset</button>
    </form>

    <div class="mt-3 text-center">
        <a href="index.php?route=logout">Logout</a>
    </div>

</div>

</body>
</html>
