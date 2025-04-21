<?php
// Define base path
define('BASE_PATH', dirname(__DIR__));

// Load configuration
require_once BASE_PATH . '/config/config.php';

// Load core files
require_once BASE_PATH . '/core/Autoloader.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/View.php';
require_once BASE_PATH . '/core/Router.php';

// Initialize autoloader
$autoloader = new \App\Core\Autoloader();
$autoloader->register();

// Start session
session_start();

// Initialize router
$router = new \App\Core\Router();

// Get the URL path
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/project-clone-web-buy-acc/src/public';
if (strpos($url, $basePath) === 0) {
    $url = substr($url, strlen($basePath));
}
$url = trim($url, '/');

// Debug URL
error_log("URL: " . $url);

// Dispatch the request
$router->dispatch($url); 