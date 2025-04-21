<?php
namespace App\Core;

class Controller {
    protected $params = [];
    protected $view = null;

    public function __construct() {
        $this->view = new View();
    }

    public function render($view, $data = []) {
        $this->view->render($view, $data);
    }

    public function renderPartial($view, $data = []) {
        return $this->view->renderPartial($view, $data);
    }

    public function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    public function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    public function getQuery($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    public function getFile($key = null) {
        if ($key === null) {
            return $_FILES;
        }
        return $_FILES[$key] ?? null;
    }

    public function setFlash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }

    public function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    public function hasFlash($key) {
        return isset($_SESSION['flash'][$key]);
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }
    }

    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('login');
        }
    }
} 