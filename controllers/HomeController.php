<?php
class HomeController {
    public function index() {
        // Charger les cryptos
        require_once 'models/Crypto.php';
        $cryptoModel = new Crypto();
        $cryptos = $cryptoModel->getAll();

        // Charger la vue avec les cryptos
        require_once 'views/home.php';
    }
}
?>
