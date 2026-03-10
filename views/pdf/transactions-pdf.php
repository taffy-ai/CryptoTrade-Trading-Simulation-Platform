<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-top: 0; }
    </style>
</head>
<body>
    <h2>Rapport des Transactions - CryptoTrade</h2>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Cryptomonnaie</th>
                <th>Symbole</th>
                <th>Montant</th>
                <th>Prix ($)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= ucfirst($t['type']) ?></td>
                <td><?= htmlspecialchars($t['name']) ?></td>
                <td><?= htmlspecialchars($t['symbol']) ?></td>
                <td><?= number_format($t['amount'], 6) ?></td>
                <td>$<?= number_format($t['price_at_transaction'], 2) ?></td>
                <td><?= date('Y-m-d H:i', strtotime($t['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php ob_end_flush(); ?>
