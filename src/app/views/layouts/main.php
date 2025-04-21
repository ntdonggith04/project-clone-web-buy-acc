<?php
/**
 * Main layout template
 * This file is used to wrap content from views inside common header and footer
 */

// Include the header
require_once __DIR__ . '/header.php';

// Output the content from view
echo $content;

// Include the footer
require_once __DIR__ . '/footer.php';
?> 