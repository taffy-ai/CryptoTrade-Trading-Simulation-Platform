<?php
require_once 'config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function create($username, $email, $hashedPassword) {
        $query = $this->db->prepare(
            "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)"
        );
        return $query->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }

    public function getByEmail($email) {
        $query = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $query->execute(['email' => $email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user['password'] = trim($user['password']);
        }
        return $user;
    }

    public function getById($userId) {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $query->execute(['id' => $userId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $query = $this->db->prepare("SELECT * FROM users ORDER BY id ASC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($userId) {
        $query = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $query->execute(['id' => $userId]);
    }

    public function enableTwoFactor($userId, $secret) {
        $query = $this->db->prepare("UPDATE users SET two_factor_enabled = 1, two_factor_secret = :secret, reset_2fa_request = 0 WHERE id = :id");
        return $query->execute(['secret' => $secret, 'id' => $userId]);
    }

    public function disableTwoFactor($userId) {
        $query = $this->db->prepare("UPDATE users SET two_factor_enabled = 0, two_factor_secret = NULL, reset_2fa_request = 0 WHERE id = :id");
        return $query->execute(['id' => $userId]);
    }

    public function requestReset2FA($userId) {
        $query = $this->db->prepare("UPDATE users SET reset_2fa_request = 1 WHERE id = :id");
        return $query->execute(['id' => $userId]);
    }

    public function clear2FA($userId) {
        $query = $this->db->prepare("UPDATE users SET two_factor_enabled = 0, two_factor_secret = NULL, reset_2fa_request = 0 WHERE id = :id");
        return $query->execute(['id' => $userId]);
    }

    public function updateBalance($userId, $cryptoId, $newBalance) {
        $query = $this->db->prepare("UPDATE wallets SET balance = :balance WHERE user_id = :user_id AND crypto_id = :crypto_id");
        return $query->execute([
            'balance' => $newBalance,
            'user_id' => $userId,
            'crypto_id' => $cryptoId
        ]);
    }

    public function getWalletsByUserId($userId) {
        $query = $this->db->prepare("
            SELECT 
                w.crypto_id, 
                c.name AS crypto_name,
                c.symbol,
                w.balance AS total_balance,
                IFNULL((
                    SELECT SUM(t.amount)
                    FROM transactions t
                    WHERE t.user_id = w.user_id
                    AND t.crypto_id = w.crypto_id
                    AND t.type = 'buy'
                ), 0) AS total_spent
            FROM wallets w
            JOIN cryptos c ON w.crypto_id = c.id
            WHERE w.user_id = :user_id
            GROUP BY w.crypto_id, c.name, c.symbol
        ");
        $query->execute(['user_id' => $userId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function updateWalletBalance($userId, $cryptoId, $newBalance) {
        // Vérifier si le wallet existe déjà
        $query = $this->db->prepare("
            SELECT * FROM wallets WHERE user_id = :user_id AND crypto_id = :crypto_id
        ");
        $query->execute([
            'user_id' => $userId,
            'crypto_id' => $cryptoId
        ]);
    
        $wallet = $query->fetch(PDO::FETCH_ASSOC);
    
        if ($wallet) {
            // Si wallet existe, update
            $update = $this->db->prepare("
                UPDATE wallets SET balance = :balance WHERE user_id = :user_id AND crypto_id = :crypto_id
            ");
            return $update->execute([
                'balance' => $newBalance,
                'user_id' => $userId,
                'crypto_id' => $cryptoId
            ]);
        } else {
            // Si wallet n'existe pas, insert
            $insert = $this->db->prepare("
                INSERT INTO wallets (user_id, crypto_id, balance) VALUES (:user_id, :crypto_id, :balance)
            ");
            return $insert->execute([
                'user_id' => $userId,
                'crypto_id' => $cryptoId,
                'balance' => $newBalance
            ]);
        }
    }
    
    public function getWalletBalance($userId, $cryptoId) {
        $query = $this->db->prepare("
            SELECT balance FROM wallets WHERE user_id = :user_id AND crypto_id = :crypto_id
        ");
        $query->execute([
            'user_id' => $userId,
            'crypto_id' => $cryptoId
        ]);
    
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['balance'] : 0;
    }
    
    public function getWalletBalanceDetails($userId, $cryptoId) {
        $query = $this->db->prepare("
            SELECT w.balance, c.name AS crypto_name
            FROM wallets w
            JOIN cryptos c ON w.crypto_id = c.id
            WHERE w.user_id = :user_id AND w.crypto_id = :crypto_id
        ");
        $query->execute([
            'user_id' => $userId,
            'crypto_id' => $cryptoId
        ]);
    
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    
}
