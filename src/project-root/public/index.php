<?php
session_start();

// Require Composer's autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Định nghĩa đường dẫn gốc
define('BASE_PATH', dirname(__DIR__));

// Autoload classes
spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load file cấu hình
require_once BASE_PATH . '/config/router.php';

// Khởi tạo router
$router = new App\Config\Router();

// Xử lý request
$router->dispatch(); 