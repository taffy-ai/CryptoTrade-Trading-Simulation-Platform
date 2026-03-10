<?php
require_once 'config/database.php';

class StopLoss {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function create($userId, $cryptoId, $targetPrice, $amount) {
        $query = $this->db->prepare(
            "INSERT INTO stop_losses (user_id, crypto_id, target_price, amount, status) 
             VALUES (:user_id, :crypto_id, :target_price, :amount, 'active')"
        );

        return $query->execute([
            'user_id' => $userId,
            'crypto_id' => $cryptoId,
            'target_price' => $targetPrice,
            'amount' => $amount
        ]);
    }

    public function getActiveByUser($userId) {
        $query = $this->db->prepare(
            "SELECT stop_losses.*, cryptos.name, cryptos.symbol 
             FROM stop_losses 
             JOIN cryptos ON stop_losses.crypto_id = cryptos.id
             WHERE stop_losses.user_id = :user_id AND stop_losses.status = 'active'"
        );

        $query->execute(['user_id' => $userId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsTriggered($stopLossId) {
        $query = $this->db->prepare(
            "UPDATE stop_losses SET status = 'triggered' WHERE id = :id"
        );

        return $query->execute(['id' => $stopLossId]);
    }
}
