<?php
require_once 'models/User.php';
require_once 'config/helpers.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitizeInput($_POST['email']);
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->getByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['two_factor_enabled'] = $user['two_factor_enabled'];
                $_SESSION['two_factor_secret'] = $user['two_factor_secret'];

                if ($user['two_factor_enabled']) {
                    $_SESSION['pending_2fa_user'] = $user;
                    redirect('/2fa-form');
                } else {
                    redirect('/dashboard');
                }
                return;
            } else {
                $_SESSION['error_message'] = "Identifiants incorrects.";
            }            
        }
        require_once 'views/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username']);
            $email = sanitizeInput($_POST['email']);
            $password = $_POST['password'];
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
            $userModel = new User();

            if ($userModel->getByEmail($email)) {
                $_SESSION['error_message'] = "Un utilisateur avec cet email existe déjà";
                require_once 'views/register.php';
                return;
            }

            if ($userModel->create($username, $email, $hashedPassword)) {
                redirect('/login');
            } else {
                $_SESSION['error_message'] = "Erreur lors de l’inscription";
                require_once 'views/register.php';
            }
        } else {
            require_once 'views/register.php';
        }
    }

    public function logout() {
        session_destroy();
        redirect('/login');
    }
}
