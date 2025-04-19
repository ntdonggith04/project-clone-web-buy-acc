-- Kiểm tra và tạo database nếu chưa tồn tại
CREATE DATABASE IF NOT EXISTS account_db;
USE account_db;

-- Kiểm tra và tạo bảng accounts nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    seller_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    basic_description TEXT,
    detailed_description TEXT,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('available', 'sold', 'pending') NOT NULL DEFAULT 'available',
    images JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Kiểm tra xem bảng có dữ liệu không
SELECT COUNT(*) as total FROM accounts;

-- Hiển thị cấu trúc bảng
DESCRIBE accounts;

-- Hiển thị một số bản ghi đầu tiên
SELECT a.*, g.name as game_name, u.username as seller_name
FROM accounts a
LEFT JOIN games g ON a.game_id = g.id
LEFT JOIN users u ON a.seller_id = u.id
LIMIT 5; 