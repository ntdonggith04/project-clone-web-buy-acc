<?php
namespace App\Core;

class Controller {
    protected $view;
    protected $params;

    public function __construct($params = []) {
        $this->params = $params;
        $this->view = new View();
    }

    protected function redirect($url) {
        // Xử lý URL
        $url = ltrim($url, '/'); // Loại bỏ dấu / ở đầu nếu có
        
        $redirectUrl = BASE_URL . $url;
        error_log("Redirecting to: " . $redirectUrl);
        
        header('Location: ' . $redirectUrl);
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    protected function render($view, $data = []) {
        error_log("Rendering view: " . $view);
        $this->view->render($view, $data);
    }
    
    protected function getQuery($key, $default = null) {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    
    protected function getPost($key, $default = null) {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
}
?> 