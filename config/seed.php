<?php
require_once __DIR__ . '/database.php';

$pdo = Database::connect();

// Suppression des anciennes données pour éviter les doublons
$pdo->exec("DELETE FROM cryptos");

// Ajout de cryptomonnaies de test
$cryptos = [
    ['Bitcoin', 'BTC', 84000.00, 'medium'],
    ['Ethereum', 'ETH', 1900.00, 'high'],
    ['Litecoin', 'LTC', 93.00, 'low'],
    ['Ripple', 'XRP', 2.40, 'medium'],
    ['Dogecoin', 'DOGE', 0.17, 'high']
];

$query = $pdo->prepare("INSERT INTO cryptos (name, symbol, initial_price, volatility) VALUES (?, ?, ?, ?)");
foreach ($cryptos as $crypto) {
    $query->execute($crypto);
}

echo "Cryptomonnaies insérées avec succès !";
?>
