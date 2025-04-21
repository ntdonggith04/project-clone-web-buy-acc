<?php
// Load core files
require_once __DIR__ . '/core/Autoloader.php';
require_once __DIR__ . '/core/View.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

// Initialize autoloader
$autoloader = new App\Core\Autoloader();
$autoloader->register(); 