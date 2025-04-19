<?php

namespace App\Controllers;

class Controller {
    /**
     * Render a view file
     * 
     * @param string $view The view file to render
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function view($view, $data = []) {
        // Extract data to make variables available in view
        if (!empty($data)) {
            extract($data);
        }

        // Convert view path to file path
        $viewFile = ROOT_PATH . '/App/views/' . $view . '.php';

        // Check if view file exists
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }

        // Include the view file
        require_once $viewFile;
    }

    /**
     * Redirect to a URL
     * 
     * @param string $url The URL to redirect to
     * @return void
     */
    protected function redirect($url) {
        header('Location: ' . BASE_PATH . $url);
        exit;
    }

    /**
     * Set a flash message
     * 
     * @param string $type The type of message (success, error, info, warning)
     * @param string $message The message content
     * @return void
     */
    protected function setFlash($type, $message) {
        $_SESSION[$type] = $message;
    }

    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Require user to be logged in
     * If not logged in, redirect to login page
     * 
     * @return void
     */
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->setFlash('error', 'Vui lòng đăng nhập để tiếp tục');
            $this->redirect('/login');
        }
    }

    /**
     * Get current logged in user ID
     * 
     * @return int|null
     */
    protected function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Check if request is POST
     * 
     * @return bool
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Get POST data
     * 
     * @param string $key The key to get
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     * 
     * @param string $key The key to get
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    protected function getQuery($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Validate required fields
     * 
     * @param array $fields Array of field names to check
     * @param array $data Data to check against
     * @return bool
     */
    protected function validateRequired($fields, $data) {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return false;
            }
        }
        return true;
    }

    /**
     * Send JSON response
     * 
     * @param mixed $data The data to send
     * @param int $status HTTP status code
     * @return void
     */
    protected function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
} 