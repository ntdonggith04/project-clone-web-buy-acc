<?php

namespace Core;

class Controller {
    public function __construct() {
        // Base constructor for all controllers
    }

    protected function view($view, $data = []) {
        // Extract data để có thể sử dụng các biến trong view
        extract($data);
        
        // Đường dẫn đến file view
        $viewPath = ROOT_PATH . "/App/views/" . $view . ".php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new \Exception("View {$view} không tồn tại");
        }
    }

    protected function redirect($path) {
        header('Location: ' . BASE_PATH . $path);
        exit;
    }

    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    protected function getQuery($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    protected function validateRequired($data, $fields) {
        $errors = [];
        foreach ($fields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $errors[$field] = "Trường {$field} là bắt buộc";
            }
        }
        return $errors;
    }

    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    protected function validateLength($string, $min, $max) {
        $length = strlen($string);
        return $length >= $min && $length <= $max;
    }

    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $this->setFlash('error', 'Vui lòng đăng nhập để tiếp tục');
            $this->redirect('/login');
        }
    }

    protected function requireAdmin() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            $this->setFlash('error', 'Bạn không có quyền truy cập trang này');
            $this->redirect('/');
        }
    }
} 