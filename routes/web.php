<?php
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CryptoController.php';
require_once __DIR__ . '/../controllers/TransactionController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/PaymentController.php';
require_once __DIR__ . '/../controllers/AlertController.php';
require_once __DIR__ . '/../controllers/TwoFactorController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/MarketController.php';
require_once __DIR__ . '/../controllers/StopLossController.php';




$request = $_GET['route'] ?? '/';

switch ($request) {
    case '/':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'cryptos':
        $controller = new CryptoController();
        $controller->list();
        break;

    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'trade':
        $controller = new TransactionController();
        $controller->index();
        break;

    case 'buy':
        $controller = new TransactionController();
        $controller->buy();
        break;

    case 'sell':
        $controller = new TransactionController();
        $controller->sell();
        break;

    case 'transactions':
        $controller = new TransactionController();
        $controller->history();
        break;

    case 'admin':
        $controller = new AdminController();
        $controller->index();
        break;

    case 'delete-user':
        $controller = new AdminController();
        $controller->deleteUser();
        break;

    case 'add-crypto':
        $controller = new AdminController();
        $controller->addCrypto();
        break;

    case 'delete-crypto':
        $controller = new AdminController();
        $controller->deleteCrypto();
        break;

    case 'set-user-limit':
        $controller = new AdminController();
        $controller->setUserLimit();
        break;

    case 'set-transaction-limit':
        $controller = new AdminController();
        $controller->setTransactionLimit();
        break;

    case 'deposit':
        $controller = new PaymentController();
        $controller->index();
        break;

    case 'process-payment':
        $controller = new PaymentController();
        $controller->processPayment();
        break;

    case 'payment-success':
        $controller = new PaymentController();
        $controller->paymentSuccess();
        break;

    case 'create-alert':
        $controller = new AlertController();
        $controller->create();
        break;

    case '2fa-setup':
        $controller = new TwoFactorController();
        $controller->setup();
        break;

    case '2fa-confirm':
        $controller = new TwoFactorController();
        $controller->confirm();
        break;

    case '2fa-form':
        $controller = new TwoFactorController();
        $controller->verifyForm();
        break;

    case 'verify-2fa':
        $controller = new TwoFactorController();
        $controller->verify();
        break;

    case 'reset-2fa':
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
             $controller = new AdminController();
            $controller->reset2FA();
        } else {
            $controller = new TwoFactorController();
            $controller->requestReset2FA();
        }
        break;
    
    case 'update-balance':
        $controller = new AdminController();
        $controller->updateUserBalance();
        break;

    case 'get-balance':
        $controller = new AdminController();
        $controller->getUserBalance();
        break;

    case 'live-prices':
        $controller = new MarketController();
        $controller->getLivePrices();
        break;
        
    case 'check-alerts':
        $controller = new AlertController();
        $controller->checkAlerts();
        break;

    case 'fetch-active-alerts':
        $controller = new AlertController();
        $controller->fetchActiveAlerts();
        break;

    case 'mark-alert-triggered':
        $controller = new AlertController();
        $controller->markAlertTriggered();
        break;

    case 'get-portfolio-total':
        $controller = new DashboardController();
        $controller->getPortfolioTotal();
        break;

    case 'create-stop-loss':
        $controller = new StopLossController();
        $controller->create();
        break;

    case 'check-stop-losses':
        $controller = new StopLossController();
        $controller->checkStopLosses();
        break;

    case 'export-transactions':
        $controller = new TransactionController();
        $controller->exportPDF();
        break;


        
    default:
        http_response_code(404);
        echo "Page non trouvée.";
        break;
}
