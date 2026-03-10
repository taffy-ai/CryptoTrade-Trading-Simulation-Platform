<?php
require_once 'config/database.php';

class Alert {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function create($userId, $cryptoId, $targetPrice, $action) {
        $query = $this->db->prepare(
            "INSERT INTO alerts (user_id, crypto_id, target_price, action, status) 
             VALUES (:user_id, :crypto_id, :target_price, :action, 'active')"
        );

        return $query->execute([
            'user_id' => $userId,
            'crypto_id' => $cryptoId,
            'target_price' => $targetPrice,
            'action' => $action
        ]);
    }

    public function getActiveByUser($userId) {
        $query = $this->db->prepare(
            "SELECT alerts.*, cryptos.name, cryptos.symbol FROM alerts 
             JOIN cryptos ON alerts.crypto_id = cryptos.id
             WHERE alerts.user_id = :user_id AND alerts.status = 'active'"
        );
        $query->execute(['user_id' => $userId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markTriggered($alertId) {
        $query = $this->db->prepare("UPDATE alerts SET status = 'triggered' WHERE id = :id");
        $query->execute(['id' => $alertId]);
    }

    public function getAllActiveWithCryptoPrices($userId) {
        $query = $this->db->prepare(
            "SELECT alerts.*, cryptos.name, cryptos.symbol, cryptos.initial_price, cryptos.volatility 
             FROM alerts 
             JOIN cryptos ON alerts.crypto_id = cryptos.id
             WHERE alerts.user_id = :user_id AND alerts.status = 'active'"
        );
        $query->execute(['user_id' => $userId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSymbolById($cryptoId) {
        $query = $this->db->prepare("SELECT symbol FROM cryptos WHERE id = :id");
        $query->execute(['id' => $cryptoId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['symbol'] : '';
    }
}
