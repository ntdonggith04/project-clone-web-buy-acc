<?php
require_once __DIR__ . '/../App/config/Database.php';
require_once __DIR__ . '/../App/models/Account.php';
require_once __DIR__ . '/../App/models/Game.php';

use App\Config\Database;
use App\Models\Account;
use App\Models\Game;

echo "=== Kiểm tra luồng dữ liệu ===\n\n";

// Kiểm tra Account Model
echo "1. Kiểm tra Account Model:\n";
try {
    $accountModel = new Account();
    
    echo "- Kiểm tra cấu trúc bảng accounts...\n";
    $accountModel->checkTableStructure();
    
    echo "- Lấy danh sách tài khoản...\n";
    $accounts = $accountModel->getAllAccounts();
    echo "Số lượng tài khoản: " . count($accounts) . "\n";
    if (!empty($accounts)) {
        echo "Mẫu dữ liệu tài khoản đầu tiên:\n";
        print_r($accounts[0]);
    }
} catch (\Exception $e) {
    echo "❌ Lỗi Account Model: " . $e->getMessage() . "\n";
}

// Kiểm tra Game Model
echo "\n2. Kiểm tra Game Model:\n";
try {
    $gameModel = new Game();
    
    echo "- Lấy danh sách game...\n";
    $games = $gameModel->getAllGames();
    echo "Số lượng game: " . count($games) . "\n";
    if (!empty($games)) {
        echo "Mẫu dữ liệu game đầu tiên:\n";
        print_r($games[0]);
    }
} catch (\Exception $e) {
    echo "❌ Lỗi Game Model: " . $e->getMessage() . "\n";
}

// Kiểm tra dữ liệu kết hợp
echo "\n3. Kiểm tra dữ liệu kết hợp:\n";
try {
    $db = new Database();
    $sql = "SELECT a.*, g.name as game_name 
            FROM accounts a 
            LEFT JOIN games g ON a.game_id = g.id 
            LIMIT 5";
    $result = $db->query($sql);
    
    echo "Số lượng bản ghi kết hợp: " . count($result) . "\n";
    if (!empty($result)) {
        echo "Mẫu dữ liệu kết hợp đầu tiên:\n";
        print_r($result[0]);
    }
} catch (\Exception $e) {
    echo "❌ Lỗi truy vấn kết hợp: " . $e->getMessage() . "\n";
}

echo "\n=== Kết thúc kiểm tra ===\n"; 