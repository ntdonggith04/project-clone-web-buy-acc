<?php
require_once __DIR__ . '/../../App/config/Database.php';

use App\Config\Database;

try {
    $db = new Database();
    
    $sql = "ALTER TABLE users 
            ADD COLUMN IF NOT EXISTS reset_token VARCHAR(255) NULL,
            ADD COLUMN IF NOT EXISTS reset_token_expiry DATETIME NULL";
    
    $db->query($sql);
    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Error running migration: " . $e->getMessage() . "\n";
} 