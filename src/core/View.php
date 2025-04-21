<?php
namespace App\Core;

class View {
    public function render($view, $data = []) {
        // Extract data to variables
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        $viewFile = dirname(__DIR__) . '/app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new \Exception("View file not found: " . $viewFile);
        }

        // Get the content
        $content = ob_get_clean();
        
        // Include the layout file
        $layoutFile = dirname(__DIR__) . '/app/views/layouts/main.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    public function renderPartial($view, $data = []) {
        // Extract data to variables
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        $viewFile = dirname(__DIR__) . '/app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new \Exception("View file not found: " . $viewFile);
        }

        // Return the contents of the buffer
        return ob_get_clean();
    }
} 