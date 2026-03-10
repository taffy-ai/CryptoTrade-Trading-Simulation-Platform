<?php
require_once __DIR__ . '/../models/Crypto.php';

class CryptoController {
    public function list() {
        $cryptoModel = new Crypto();
        $cryptos = $cryptoModel->getAllWithCurrentPrice();
        require_once __DIR__ . '/../views/cryptos.php';
    }
}
?>
