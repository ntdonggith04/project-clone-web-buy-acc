<?php
namespace App\Core;

class Autoloader {
    public function register() {
        spl_autoload_register([$this, 'autoload']);
    }

    public function autoload($class) {
        // Convert namespace to full file path
        $class = str_replace('App\\', '', $class);
        $file = dirname(__DIR__) . '/' . str_replace('\\', '/', $class) . '.php';
        
        // Debug
        error_log("Trying to load class: " . $class);
        error_log("Looking for file at: " . $file);
        
        // If file exists, require it
        if (file_exists($file)) {
            error_log("File found at: " . $file);
            require_once $file;
            return;
        }
        
        // Try alternative path
        $file = dirname(__DIR__) . '/app/' . str_replace('\\', '/', $class) . '.php';
        error_log("Trying alternative path: " . $file);
        
        if (file_exists($file)) {
            error_log("File found at: " . $file);
            require_once $file;
            return;
        }
        
        error_log("File not found for class: " . $class);
    }
} 