<?php
namespace App\Core;

class View {
    public function render($view, $data = []) {
        // Extract data to make variables available in view
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        require_once '../app/views/' . $view . '.php';

        // Get the contents of the buffer
        $content = ob_get_clean();

        // Include the layout
        require_once '../app/views/layouts/main.php';
    }
}
?> 