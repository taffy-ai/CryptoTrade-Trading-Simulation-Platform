<?php
require_once 'models/User.php';
require_once 'vendor/autoload.php';

class TwoFactorController {
    public function setup() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();

        $_SESSION['2fa_temp_secret'] = $secret;
        $qrCodeUrl = $ga->getQRCodeGoogleUrl('CryptoTrade', $secret);

        require_once 'views/2fa-setup.php';
    }

    public function confirm() {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $code = $_POST['code'];
        $secret = $_SESSION['2fa_temp_secret'];
        $userId = $_SESSION['user_id'];

        if ($ga->verifyCode($secret, $code, 2)) {
            $userModel = new User();
            $userModel->enableTwoFactor($userId, $secret);

            unset($_SESSION['2fa_temp_secret']);
            $_SESSION['success_message'] = "Double authentification activée";
            redirect('/dashboard');
        } else {
            $_SESSION['error_message'] = "Code invalide";
            redirect('/2fa-setup');
        }
    }

    public function verifyForm() {
        require_once 'views/2fa-check.php';
    }

    public function verify() {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $code = $_POST['code'];
        $user = $_SESSION['pending_2fa_user'];

        if ($ga->verifyCode($user['two_factor_secret'], $code, 2)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            unset($_SESSION['pending_2fa_user']);
            redirect('/dashboard');
        } else {
            $_SESSION['error_message'] = "Code incorrect";
            redirect('/2fa-form');
        }
    }

    public function requestReset2FA() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['pending_2fa_user'])) {
            $userModel = new User();
            $userModel->requestReset2FA($_SESSION['pending_2fa_user']['id']);
            $_SESSION['error_message'] = "Une demande de réinitialisation 2FA a été envoyée à l'administrateur";
        }
        redirect('/2fa-form');
    }
    
    
}
