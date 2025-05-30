<?php
session_start();
require_once 'config/config.php';

// Auto-include controllers
spl_autoload_register(function ($class_name) {
    $file = 'controllers/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Check if user is logged in for protected pages
$protected_pages = ['dashboard', 'financial', 'meat', 'users', 'warga'];
$page = $_GET['page'] ?? 'login';

if (in_array($page, $protected_pages) && !isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}

// Route handling
switch($page) {
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
        
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'financial':
        $controller = new FinancialController();
        if(isset($_GET['action']) && $_GET['action'] === 'create') {
            $controller->create();
        } else {
            $controller->index();
        }
        break;
        
    case 'meat':
        $controller = new MeatController();
        if(isset($_GET['action'])) {
            switch($_GET['action']) {
                case 'generate':
                    $controller->generate();
                    break;
                case 'pickup':
                    $controller->pickup();
                    break;
                case 'card':
                    $controller->card();
                    break;
                default:
                    $controller->index();
            }
        } else {
            $controller->index();
        }
        break;
        
    default:
        header("Location: index.php?page=login");
        exit();
}
?>