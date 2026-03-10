<?php
require_once 'config/database.php';

class TransactionLimit {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getUserLimit($userId) {
        $query = $this->db->prepare(
            "SELECT max_transactions_per_day FROM transaction_limits WHERE user_id = :user_id"
        );
        $query->execute(['user_id' => $userId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['max_transactions_per_day'] : 10; // par défaut 10
    }

    public function setUserLimit($userId, $limit) {
        $query = $this->db->prepare(
            "INSERT INTO transaction_limits (user_id, max_transactions_per_day)
            VALUES (:user_id, :limit)
            ON DUPLICATE KEY UPDATE max_transactions_per_day = :limit"
        );
    
        return $query->execute([
            'user_id' => $userId,
            'limit' => $limit
        ]);
    }
    
}
