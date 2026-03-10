<?php
require_once 'models/Crypto.php';

class MarketController {
    public function index() {
        $cryptoModel = new Crypto();
        $cryptos = $cryptoModel->getAllWithCurrentPrice();

        require_once 'views/market.php';
    }

    public function getLivePrices() {
        header('Content-Type: application/json');
        $cryptoModel = new Crypto();
        $cryptos = $cryptoModel->getAllWithCurrentPrice();
        echo json_encode($cryptos);
    }
}
