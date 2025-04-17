<?php
// config/router.php

namespace App\Config;

class Router {
    private $routes = [];

    public function __construct() {
        $this->routes = [
            '/' => ['controller' => 'HomeController', 'action' => 'index'],
            '/login' => ['controller' => 'AuthController', 'action' => 'login'],
            '/register' => ['controller' => 'AuthController', 'action' => 'register'],
            '/logout' => ['controller' => 'AuthController', 'action' => 'logout'],
            '/auth/google' => ['controller' => 'AuthController', 'action' => 'google'],
            '/auth/google/callback' => ['controller' => 'AuthController', 'action' => 'googleCallback'],
            '/accounts' => ['controller' => 'AccountController', 'action' => 'index'],
            '/sell' => ['controller' => 'AccountController', 'action' => 'sell'],
            '/games' => ['controller' => 'GameController', 'action' => 'index'],
            // Admin routes
            '/admin' => ['controller' => 'AdminController', 'action' => 'index'],
            '/admin/users' => ['controller' => 'AdminController', 'action' => 'users'],
            '/admin/accounts' => ['controller' => 'AdminController', 'action' => 'accounts'],
            '/admin/games' => ['controller' => 'AdminController', 'action' => 'games'],
            '/admin/users/delete/(\d+)' => ['controller' => 'AdminController', 'action' => 'deleteUser'],
            '/admin/accounts/delete/(\d+)' => ['controller' => 'AdminController', 'action' => 'deleteAccount'],
            '/admin/games/delete/(\d+)' => ['controller' => 'AdminController', 'action' => 'deleteGame']
        ];
    }

    public function dispatch() {
        $url = $_SERVER['REQUEST_URI'];
        
        // Xử lý URL để loại bỏ đường dẫn cơ sở
        $basePath = '/project-clone-web-buy-acc/src/project-root/public';
        if (strpos($url, $basePath) === 0) {
            $url = substr($url, strlen($basePath));
        }
        
        $url = strtok($url, '?'); // Loại bỏ query string
        $url = rtrim($url, '/'); // Loại bỏ dấu / ở cuối

        if (empty($url)) {
            $url = '/';
        }

        // Debug URL
        error_log("Processing URL: " . $url);

        foreach ($this->routes as $pattern => $route) {
            // Debug pattern matching
            error_log("Trying pattern: " . $pattern);
            
            if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                $controllerName = 'App\\Controllers\\' . $route['controller'];
                $action = $route['action'];

                // Debug matched route
                error_log("Matched route: " . $controllerName . "::" . $action);

                // Kiểm tra xem controller có tồn tại không
                if (!class_exists($controllerName)) {
                    throw new \Exception("Controller $controllerName not found");
                }

                $controller = new $controllerName();

                // Kiểm tra xem action có tồn tại không
                if (!method_exists($controller, $action)) {
                    throw new \Exception("Action $action not found in controller $controllerName");
                }

                // Gọi action với các tham số từ URL
                array_shift($matches); // Loại bỏ phần tử đầu tiên (toàn bộ URL)
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }

        // Nếu không tìm thấy route phù hợp
        header("HTTP/1.0 404 Not Found");
        require_once BASE_PATH . '/App/views/errors/404.php';
    }
}



