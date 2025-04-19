<?php
// Cấu hình session
ini_set('session.save_handler', 'files');
ini_set('session.save_path', 'D:/XAMPP/tmp/sessions');
ini_set('session.gc_probability', 1);

session_start();

// Require Composer's autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Định nghĩa đường dẫn gốc
define('BASE_PATH', '/project-clone-web-buy-acc/src/project-root/public');
define('ROOT_PATH', dirname(__DIR__));

// Autoload classes
spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load file cấu hình
require_once ROOT_PATH . '/config/router.php';

// Khởi tạo router
$router = new App\Config\Router();

// Xử lý request
$router->dispatch(); 