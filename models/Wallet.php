<?php
require_once 'config/database.php';

class Wallet {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getBalance($userId, $cryptoId) {
        $query = $this->db->prepare(
            "SELECT SUM(balance) AS total_balance 
             FROM wallets 
             WHERE user_id = :user_id AND crypto_id = :crypto_id"
        );
        $query->execute(['user_id' => $userId, 'crypto_id' => $cryptoId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total_balance'] : 0;
    }

    public function updateBalance($userId, $cryptoId, $amount) {
        // Lire le solde actuel
        $query = $this->db->prepare(
            "SELECT balance FROM wallets WHERE user_id = :user_id AND crypto_id = :crypto_id"
        );
        $query->execute(['user_id' => $userId, 'crypto_id' => $cryptoId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $newBalance = $result['balance'] + $amount;
    
            $update = $this->db->prepare(
                "UPDATE wallets SET balance = :balance WHERE user_id = :user_id AND crypto_id = :crypto_id"
            );
            return $update->execute([
                'balance' => $newBalance,
                'user_id' => $userId,
                'crypto_id' => $cryptoId
            ]);
        } else {
            $insert = $this->db->prepare(
                "INSERT INTO wallets (user_id, crypto_id, balance) VALUES (:user_id, :crypto_id, :balance)"
            );
            return $insert->execute([
                'user_id' => $userId,
                'crypto_id' => $cryptoId,
                'balance' => $amount
            ]);
        }
    }
    
    

    public function getUserWallet($userId) {
        $query = $this->db->prepare(
            "SELECT wallets.crypto_id, cryptos.name, cryptos.symbol, SUM(wallets.balance) AS balance 
             FROM wallets 
             JOIN cryptos ON wallets.crypto_id = cryptos.id 
             WHERE wallets.user_id = :user_id 
             GROUP BY wallets.crypto_id"
        );
        $query->execute(['user_id' => $userId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
        

    public function getUSDTId() {
        $query = $this->db->prepare("SELECT id FROM cryptos WHERE symbol = 'USDT' LIMIT 1");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }
}
