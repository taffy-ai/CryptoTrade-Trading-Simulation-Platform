<?php
require_once 'config/database.php';

class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function create($userId, $cryptoId, $type, $amount, $price) {
        $query = $this->db->prepare(
            "INSERT INTO transactions (user_id, crypto_id, type, amount, price_at_transaction) VALUES (:user_id, :crypto_id, :type, :amount, :price)"
        );
    
        $success = $query->execute([
            'user_id' => $userId,
            'crypto_id' => $cryptoId,
            'type' => $type,
            'amount' => $amount,
            'price' => $price
        ]);
        return $success;
    }
    

    public function getByUser($userId) {
        $query = $this->db->prepare(
            "SELECT transactions.*, cryptos.name, cryptos.symbol FROM transactions 
             JOIN cryptos ON transactions.crypto_id = cryptos.id 
             WHERE transactions.user_id = :user_id 
             ORDER BY transactions.created_at DESC"
        );
        $query->execute(['user_id' => $userId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    public function getAll() {
        $query = $this->db->prepare(
            "SELECT transactions.*, users.username, cryptos.name, cryptos.symbol 
             FROM transactions
             JOIN users ON transactions.user_id = users.id
             JOIN cryptos ON transactions.crypto_id = cryptos.id
             ORDER BY transactions.created_at DESC"
        );
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countTodayByUser($userId) {
        $query = $this->db->prepare(
            "SELECT COUNT(*) as total FROM transactions 
             WHERE user_id = :user_id AND DATE(created_at) = CURDATE()"
        );
        $query->execute(['user_id' => $userId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
    }
    
}
