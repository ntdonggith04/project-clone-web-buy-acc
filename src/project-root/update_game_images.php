<?php
require_once __DIR__ . '/App/bootstrap.php';

use App\Models\Game;

$gameModel = new Game();

// Cập nhật đường dẫn ảnh cho các game
if ($gameModel->updateGameImages()) {
    echo "Cập nhật ảnh game thành công!\n";
} else {
    echo "Có lỗi xảy ra khi cập nhật ảnh game.\n";
} 