<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Access denied. Please <a href='index.php?route=login'>log in</a>.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Panel - CryptoTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/CryptoTrade/public/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body>

<div class="container my-5 text-center">

    <h1 class="mb-4 fw-bold text-uppercase">Admin Panel</h1>

    <nav class="mb-4 d-flex justify-content-center flex-wrap">
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=dashboard">Dashboard</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=trade">Trade Cryptos</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=deposit">Deposit Funds</a>
        <a class="btn btn-warning me-2 mb-2" href="index.php?route=transactions">My Transactions</a>
        <a class="btn btn-danger me-2 mb-2" href="index.php?route=admin">Admin Panel</a>
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

    <!-- Gestion des Utilisateurs -->
    <h2 class="mb-3 fw-semibold text-uppercase">Gestion des Utilisateurs</h2>
    <div class="table-responsive mb-5 animate__animated animate__fadeIn">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>2FA</th>
                    <th>Reset 2FA</th>
                    <th>Actions</th>
                    <th>Limite</th>
                    <th>Modifier Solde</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['two_factor_enabled'] ? 'Activée' : 'Désactivée' ?></td>
                        <td><?= $user['reset_2fa_request'] ? 'Demandé' : '-' ?></td>
                        <td>
                            <form method="POST" action="index.php?route=delete-user" class="mb-1">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button class="btn btn-danger btn-sm w-100" type="submit">Supprimer</button>
                            </form>
                            <?php if ($user['reset_2fa_request']): ?>
                                <form method="POST" action="index.php?route=reset-2fa">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button class="btn btn-warning btn-sm w-100" type="submit">Réinitialiser 2FA</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="index.php?route=set-user-limit">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="number" name="limit" class="form-control form-control-sm mb-1" placeholder="Limite / jour" required>
                                <button class="btn btn-primary btn-sm w-100" type="submit">Configurer</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="index.php?route=update-balance" class="balance-form">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="crypto_id" class="form-select form-select-sm mb-1 crypto-selector" data-user-id="<?= $user['id'] ?>" required>
                                    <option value="">Sélectionner une crypto</option>
                                    <?php foreach ($cryptos as $crypto): ?>
                                        <option value="<?= $crypto['id'] ?>"><?= htmlspecialchars($crypto['symbol']) ?> (<?= htmlspecialchars($crypto['name']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="current-balance d-block text-white mb-1">Solde actuel : 0</span>
                                <input type="number" step="0.00000001" name="balance" class="form-control form-control-sm mb-1" placeholder="Nouveau montant" required>
                                <button class="btn btn-success btn-sm w-100" type="submit">Modifier</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Ajouter une Crypto -->
    <h2 class="mb-3 fw-semibold text-uppercase">Ajouter une Crypto</h2>
    <form method="POST" action="index.php?route=add-crypto" class="row g-2 justify-content-center mb-5 animate__animated animate__fadeIn">
        <div class="col-md-2">
            <input type="text" name="name" class="form-control form-control-sm" placeholder="Nom" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="symbol" class="form-control form-control-sm" placeholder="Symbole" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="initial_price" class="form-control form-control-sm" step="0.01" placeholder="Prix initial" required>
        </div>
        <div class="col-md-2">
            <select name="volatility" class="form-select form-select-sm" required>
                <option value="">Volatilité</option>
                <option value="low">Faible</option>
                <option value="medium">Moyenne</option>
                <option value="high">Forte</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary btn-sm w-100" type="submit">Ajouter</button>
        </div>
    </form>

    <!-- Liste des Cryptos -->
    <h2 class="mb-3 fw-semibold text-uppercase">Liste des Cryptos</h2>
    <div class="table-responsive animate__animated animate__fadeIn">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Symbole</th>
                    <th>Prix initial</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cryptos as $crypto): ?>
                    <tr>
                        <td><?= htmlspecialchars($crypto['name']) ?></td>
                        <td><?= htmlspecialchars($crypto['symbol']) ?></td>
                        <td><?= number_format($crypto['initial_price'], 2) ?> $</td>
                        <td>
                            <form method="POST" action="index.php?route=delete-crypto">
                                <input type="hidden" name="crypto_id" value="<?= $crypto['id'] ?>">
                                <button class="btn btn-danger btn-sm w-100" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
$(document).ready(function(){
    $('.crypto-selector').on('change', function(){
        var cryptoId = $(this).val();
        var userId = $(this).data('user-id');
        var currentBalanceSpan = $(this).closest('form').find('.current-balance');

        if (cryptoId) {
            $.ajax({
                url: 'index.php?route=get-balance',
                method: 'POST',
                data: { user_id: userId, crypto_id: cryptoId },
                dataType: 'json',
                success: function(response) {
                    currentBalanceSpan.text('Solde actuel : ' + response.balance + ' ' + response.crypto_name).css('color', '#fff');
                },
                error: function() {
                    currentBalanceSpan.text('Erreur de récupération du solde').css('color', '#fff');
                }
            });
        } else {
            currentBalanceSpan.text('Solde actuel : 0').css('color', '#fff');
        }
    });
});
</script>

</body>
</html>
