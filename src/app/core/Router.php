<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function __construct() {
        $this->routes = require_once dirname(__DIR__, 2) . '/config/routes.php';
    }

    public function dispatch() {
        // Lấy URI từ request
        $uri = $_SERVER['REQUEST_URI'];
        
        // Debug
        error_log("Original URI: " . $uri);
        
        // Loại bỏ query string nếu có
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Loại bỏ phần /project-clone-web-buy-acc/src/public khỏi URI
        $basePath = '/project-clone-web-buy-acc/src/public';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        $uri = trim($uri, '/');
        
        // Debug
        error_log("Processed URI: " . $uri);
        
        // Xử lý trường hợp root path
        if (empty($uri)) {
            $uri = '';
        }

        // Debug
        error_log("Available routes: " . implode(", ", array_keys($this->routes)));

        foreach ($this->routes as $route => $config) {
            $pattern = preg_replace('/\{([a-z]+)\}/', '([^/]+)', $route);
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';
            
            error_log("Checking pattern: " . $pattern . " against URI: " . $uri);

            if (preg_match($pattern, $uri, $matches)) {
                error_log("Found match for route: " . $route);
                array_shift($matches);
                
                $controllerName = $config['controller'];
                $action = $config['action'];
                
                error_log("Controller: " . $controllerName . ", Action: " . $action);
                
                // Sử dụng đường dẫn tuyệt đối để tránh lỗi
                $controllerFile = dirname(__DIR__, 2) . '/app/controllers/' . $controllerName . '.php';
                error_log("Controller file path: " . $controllerFile);
                
                if (file_exists($controllerFile)) {
                    error_log("Controller file exists, loading it");
                    require_once $controllerFile;
                    
                    // Sử dụng namespace đầy đủ
                    $fullyQualifiedClass = "\\App\\Controllers\\" . $controllerName;
                    error_log("Creating instance of: " . $fullyQualifiedClass);
                    
                    // Truyền đối số phù hợp vào controller
                    $params = [];
                    if (!empty($matches)) {
                        // Chuyển mảng số thành mảng có key để truyền vào controller
                        $routeParts = explode('/', $route);
                        $uriParts = explode('/', $uri);
                        
                        foreach ($routeParts as $index => $part) {
                            if (preg_match('/\{([a-z]+)\}/', $part, $paramMatches)) {
                                $params[$paramMatches[1]] = $uriParts[$index];
                            }
                        }
                    }
                    
                    error_log("Params: " . json_encode($params));
                    $controllerInstance = new $fullyQualifiedClass();
                    $controllerInstance->$action($params);
                    return;
                } else {
                    error_log("Controller file does not exist: " . $controllerFile);
                }
            }
        }

        // Nếu không tìm thấy route, hiển thị trang 404
        error_log("No route found for URI: " . $uri);
        header("HTTP/1.0 404 Not Found");
        require_once dirname(__DIR__, 2) . '/app/views/errors/404.php';
    }
}
?> 