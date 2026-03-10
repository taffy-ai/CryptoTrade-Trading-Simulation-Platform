<?php
require_once 'config/database.php';

class Crypto {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $query = $this->db->prepare("SELECT * FROM cryptos");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($cryptoId) {
        $query = $this->db->prepare("SELECT * FROM cryptos WHERE id = :id");
        $query->execute(['id' => $cryptoId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getCurrentPrice($cryptoId) {
        $crypto = $this->getById($cryptoId);
        if (!$crypto) {
            return null;
        }

        // Définition des plages de volatilité
        $volatilityRanges = [
            'low' => [0.001, 0.005],   // 0.1% - 0.5% de variation
            'medium' => [0.005, 0.02], // 0.5% - 2% de variation
            'high' => [0.02, 0.05]     // 2% - 5% de variation
        ];

        // Récupération de la volatilité correspondante
        $minVar = $volatilityRanges[$crypto['volatility']][0];
        $maxVar = $volatilityRanges[$crypto['volatility']][1];

        // Génération d'une variation aléatoire (+ ou -)
        $variation = (rand($minVar * 10000, $maxVar * 10000) / 10000) * (rand(0, 1) ? 1 : -1);
        $newPrice = $crypto['initial_price'] + ($crypto['initial_price'] * $variation);

        return round($newPrice, 2);
    }

    public function getAllWithCurrentPrice() {
        $cryptos = $this->getAll();
        foreach ($cryptos as &$crypto) {
            $crypto['current_price'] = $this->getCurrentPrice($crypto['id']);
        }
        return $cryptos;
    }

    public function create($name, $symbol, $initialPrice, $volatility) {
        $query = $this->db->prepare(
            "INSERT INTO cryptos (name, symbol, initial_price, volatility) VALUES (:name, :symbol, :initial_price, :volatility)"
        );
        return $query->execute([
            'name' => $name,
            'symbol' => $symbol,
            'initial_price' => $initialPrice,
            'volatility' => $volatility
        ]);
    }
    

    public function delete($cryptoId) {
        $query = $this->db->prepare("DELETE FROM cryptos WHERE id = :id");
        return $query->execute(['id' => $cryptoId]);
    }
}
