<?php
require_once __DIR__ . '/../App/config/Database.php';

use App\Config\Database;

function checkDatabaseConnection() {
    try {
        $db = new Database();
        echo "✅ Kết nối database thành công\n";
        return $db;
    } catch (\Exception $e) {
        echo "❌ Lỗi kết nối database: " . $e->getMessage() . "\n";
        return null;
    }
}

function checkTableExists($db, $tableName) {
    try {
        $result = $db->query("SHOW TABLES LIKE ?", [$tableName]);
        if (count($result) > 0) {
            echo "✅ Bảng '$tableName' tồn tại\n";
            return true;
        } else {
            echo "❌ Bảng '$tableName' không tồn tại\n";
            return false;
        }
    } catch (\Exception $e) {
        echo "❌ Lỗi kiểm tra bảng '$tableName': " . $e->getMessage() . "\n";
        return false;
    }
}

function checkTableData($db, $tableName) {
    try {
        $result = $db->query("SELECT COUNT(*) as count FROM $tableName");
        $count = $result[0]['count'];
        echo "ℹ️ Số lượng bản ghi trong bảng '$tableName': $count\n";
        
        if ($count > 0) {
            $sample = $db->query("SELECT * FROM $tableName LIMIT 1");
            echo "ℹ️ Mẫu dữ liệu từ bảng '$tableName':\n";
            print_r($sample[0]);
        }
        
        return $count;
    } catch (\Exception $e) {
        echo "❌ Lỗi kiểm tra dữ liệu bảng '$tableName': " . $e->getMessage() . "\n";
        return 0;
    }
}

echo "=== Bắt đầu kiểm tra kết nối database ===\n\n";

$db = checkDatabaseConnection();
if ($db) {
    $tables = ['games', 'users', 'accounts'];
    
    echo "\n=== Kiểm tra cấu trúc bảng ===\n\n";
    foreach ($tables as $table) {
        if (checkTableExists($db, $table)) {
            echo "\n--- Kiểm tra dữ liệu bảng $table ---\n";
            checkTableData($db, $table);
            echo "\n";
        }
    }
}

echo "\n=== Kết thúc kiểm tra ===\n"; 