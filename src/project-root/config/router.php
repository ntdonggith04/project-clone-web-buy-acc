<?php
// config/router.php

namespace App\Config;

class Router {
    private $routes = [];
    private $base_path = '/project-clone-web-buy-acc/src/project-root/public';

    public function __construct() {
        // Auth routes
        $this->addRoute('GET', '/login', 'AuthController', 'login');
        $this->addRoute('POST', '/login', 'AuthController', 'login');
        $this->addRoute('GET', '/register', 'AuthController', 'register');
        $this->addRoute('POST', '/register', 'AuthController', 'register');
        $this->addRoute('GET', '/logout', 'AuthController', 'logout');

        // Recharge routes
        $this->addRoute('GET', '/recharge', 'RechargeController', 'index');
        $this->addRoute('POST', '/recharge/process', 'RechargeController', 'process');
        $this->addRoute('GET', '/recharge/bank/:id', 'RechargeController', 'bank');
        $this->addRoute('GET', '/recharge/momo/:id', 'RechargeController', 'momo');
        $this->addRoute('GET', '/recharge/zalopay/:id', 'RechargeController', 'zalopay');
        $this->addRoute('GET', '/recharge/card', 'RechargeController', 'card');
        $this->addRoute('POST', '/recharge/card/submit', 'RechargeController', 'processCard');
        $this->addRoute('GET', '/recharge/callback', 'RechargeController', 'callback');

        // Password Reset routes
        $this->addRoute('GET', '/forgot-password', 'AuthController', 'forgotPassword');
        $this->addRoute('POST', '/forgot-password', 'AuthController', 'sendResetLink');
        $this->addRoute('GET', '/reset-password/:token', 'AuthController', 'resetPassword');
        $this->addRoute('POST', '/reset-password', 'AuthController', 'updatePassword');

        // Google Auth routes
        $this->addRoute('GET', '/auth/google', 'AuthController', 'google');
        $this->addRoute('GET', '/auth/google/callback', 'AuthController', 'googleCallback');

        // Admin routes
        $this->addRoute('GET', '/admin', 'AdminController', 'index');
        
        // Admin - Users Management
        $this->addRoute('GET', '/admin/users', 'UserController', 'index');
        $this->addRoute('POST', '/admin/users/add', 'UserController', 'add');
        $this->addRoute('GET', '/admin/users/get/:id', 'UserController', 'get');
        $this->addRoute('POST', '/admin/users/edit/:id', 'UserController', 'edit');
        $this->addRoute('GET', '/admin/users/delete/:id', 'UserController', 'delete');
        
        // Admin - Games Management
        $this->addRoute('GET', '/admin/games', 'AdminController', 'games');
        $this->addRoute('POST', '/admin/games/add', 'AdminController', 'addGame');
        $this->addRoute('GET', '/admin/games/get/:id', 'AdminController', 'getGame');
        $this->addRoute('POST', '/admin/games/edit/:id', 'AdminController', 'editGame');
        $this->addRoute('GET', '/admin/games/delete/:id', 'AdminController', 'deleteGame');
        
        // Admin - Accounts Management
        $this->addRoute('GET', '/admin/accounts', 'AdminController', 'accounts');
        $this->addRoute('POST', '/admin/accounts/add', 'AdminController', 'addAccount');
        $this->addRoute('GET', '/admin/accounts/get/:id', 'AdminController', 'getAccount');
        $this->addRoute('POST', '/admin/accounts/edit/:id', 'AdminController', 'editAccount');
        $this->addRoute('GET', '/admin/accounts/delete/:id', 'AdminController', 'deleteAccount');

        // User routes
        $this->addRoute('GET', '/', 'HomeController', 'index');
        
        // User Profile routes
        $this->addRoute('GET', '/profile', 'UserController', 'profile');
        $this->addRoute('GET', '/profile/edit', 'UserController', 'edit');
        $this->addRoute('POST', '/profile/update', 'UserController', 'update');
        $this->addRoute('GET', '/profile/accounts', 'UserController', 'accounts');
        $this->addRoute('GET', '/profile/purchases', 'UserController', 'purchases');

        // Game routes
        $this->addRoute('GET', '/games', 'GameController', 'index');
        $this->addRoute('GET', '/games/:id', 'GameController', 'show');

        // Account routes
        $this->addRoute('GET', '/accounts', 'AccountController', 'index');
        $this->addRoute('GET', '/accounts/:id', 'AccountController', 'show');
        $this->addRoute('GET', '/accounts/create', 'AccountController', 'create');
        $this->addRoute('POST', '/accounts/store', 'AccountController', 'store');
        $this->addRoute('POST', '/accounts/buy/:id', 'AccountController', 'buy');

        // Search routes
        $this->addRoute('GET', '/search', 'SearchController', 'index');
        $this->addRoute('POST', '/search', 'SearchController', 'search');

        // Cart routes
        $this->addRoute('GET', '/cart', 'CartController', 'index');
        $this->addRoute('POST', '/cart/add/:id', 'CartController', 'add');
        $this->addRoute('POST', '/cart/remove/:id', 'CartController', 'remove');
        $this->addRoute('GET', '/cart/clear', 'CartController', 'clear');

        // Checkout routes
        $this->addRoute('GET', '/checkout', 'CheckoutController', 'index');
        $this->addRoute('POST', '/checkout/process', 'CheckoutController', 'process');
        $this->addRoute('GET', '/checkout/success', 'CheckoutController', 'success');
        $this->addRoute('GET', '/checkout/cancel', 'CheckoutController', 'cancel');

        // Transaction routes
        $this->addRoute('GET', '/transactions', 'TransactionController', 'index');
        $this->addRoute('GET', '/transactions/:id', 'TransactionController', 'show');

        // API routes for AJAX requests
        $this->addRoute('GET', '/api/games', 'ApiController', 'games');
        $this->addRoute('GET', '/api/accounts', 'ApiController', 'accounts');
        $this->addRoute('GET', '/api/stats', 'ApiController', 'stats');
        $this->addRoute('POST', '/api/validate/username', 'ApiController', 'validateUsername');
        $this->addRoute('POST', '/api/validate/email', 'ApiController', 'validateEmail');
    }

    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch() {
        $request_method = $_SERVER['REQUEST_METHOD'];
        $request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Debug
        error_log("Original Request Path: " . $request_path);
        error_log("Base Path: " . $this->base_path);

        // Loại bỏ base path từ request path
        if (strpos($request_path, $this->base_path) === 0) {
            $request_path = substr($request_path, strlen($this->base_path));
        }

        // Nếu path rỗng, set về /
        if (empty($request_path)) {
            $request_path = '/';
        }

        // Debug
        error_log("Processed Request Path: " . $request_path);
        error_log("Request Method: " . $request_method);

        foreach ($this->routes as $route) {
            error_log("Checking route: " . $route['method'] . " " . $route['path']);
            if ($route['method'] === $request_method && $this->matchPath($route['path'], $request_path)) {
                error_log("Route matched: " . $route['controller'] . "@" . $route['action']);
                $controller_name = "App\\Controllers\\" . $route['controller'];
                $controller = new $controller_name();
                $action = $route['action'];
                
                return call_user_func_array([$controller, $action], $this->getPathParams($route['path'], $request_path));
            }
        }

        // 404 Not Found
        error_log("No route found for: " . $request_method . " " . $request_path);
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . '/../App/views/errors/404.php';
    }

    private function matchPath($route_path, $request_path) {
        // Chuẩn hóa path
        $route_path = '/' . trim($route_path, '/');
        $request_path = '/' . trim($request_path, '/');

        error_log("Matching - Route: " . $route_path . " Request: " . $request_path);

        if ($route_path === $request_path) {
            error_log("Exact match found");
            return true;
        }

        $route_parts = explode('/', trim($route_path, '/'));
        $request_parts = explode('/', trim($request_path, '/'));

        if (count($route_parts) !== count($request_parts)) {
            error_log("Part count mismatch - Route: " . count($route_parts) . " Request: " . count($request_parts));
            return false;
        }

        foreach ($route_parts as $i => $route_part) {
            if (strpos($route_part, ':') === 0) {
                continue;
            }
            if ($route_part !== $request_parts[$i]) {
                error_log("Part mismatch at index " . $i . " - Route: " . $route_part . " Request: " . $request_parts[$i]);
                return false;
            }
        }

        error_log("Dynamic match found");
        return true;
    }

    private function getPathParams($route_path, $request_path) {
        $params = [];
        $route_parts = explode('/', trim($route_path, '/'));
        $request_parts = explode('/', trim($request_path, '/'));

        foreach ($route_parts as $i => $route_part) {
            if (strpos($route_part, ':') === 0) {
                $params[] = $request_parts[$i];
            }
        }

        return $params;
    }
}



