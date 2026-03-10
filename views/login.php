<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - CryptoTrade</title>

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

    <h1 class="mb-4 fw-bold text-uppercase text-center">Login CryptoTrade</h1>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="text-danger text-center mb-3 fw-bold">
            <?= $_SESSION['error_message']; ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?route=login">
        <div class="mb-3 text-start">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3 text-start">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <div class="text-center mt-3">
        <span class="form-text">Don't have an account yet?</span><br>
        <a href="index.php?route=register">Create an account</a>
    </div>

</div>

</body>
</html>
