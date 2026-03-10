<?php
require_once 'config/database.php';

class Payment {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function create($userId, $amount, $status, $transactionId) {
        $query = $this->db->prepare(
            "INSERT INTO payments (user_id, amount, status, transaction_id) VALUES (:user_id, :amount, :status, :transaction_id)"
        );
        $query->execute([
            'user_id' => $userId,
            'amount' => $amount,
            'status' => $status,
            'transaction_id' => $transactionId
        ]);
        return $this->db->lastInsertId();
    }

    public function getById($paymentId) {
        $query = $this->db->prepare("SELECT * FROM payments WHERE id = :id");
        $query->execute(['id' => $paymentId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($paymentId, $status) {
        $query = $this->db->prepare("UPDATE payments SET status = :status WHERE id = :id");
        return $query->execute(['status' => $status, 'id' => $paymentId]);
    }

    public function getByUser($userId) {
        $query = $this->db->prepare("SELECT * FROM payments WHERE user_id = :user_id ORDER BY created_at DESC");
        $query->execute(['user_id' => $userId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
