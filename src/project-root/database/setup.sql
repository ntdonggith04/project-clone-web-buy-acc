-- Tạo database nếu chưa tồn tại
CREATE DATABASE IF NOT EXISTS account_db;
USE account_db;

-- Tạo bảng users nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng games nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng accounts nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT,
    seller_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('available', 'sold', 'pending') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE SET NULL,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Thêm tài khoản admin mặc định
INSERT INTO users (username, email, password, role) VALUES 
('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Mật khẩu mặc định là "password"

-- Thêm một số game mẫu
INSERT INTO games (name, description) VALUES
('League of Legends', 'Game MOBA phổ biến nhất thế giới'),
('Valorant', 'Game FPS chiến thuật từ Riot Games'),
('Genshin Impact', 'Game nhập vai thế giới mở'),
('PUBG', 'Game battle royale nổi tiếng');

-- Thêm một số tài khoản mẫu
INSERT INTO accounts (game_id, seller_id, title, description, price, status) VALUES
(1, 1, 'Tài khoản LOL Rank Cao Thủ', 'Nhiều tướng và trang phục', 1000000, 'available'),
(2, 1, 'Tài khoản Valorant Full Skin', 'Đầy đủ skin hiếm', 500000, 'available'),
(3, 1, 'Tài khoản Genshin AR55', 'Nhiều nhân vật 5 sao', 800000, 'available'),
(4, 1, 'Tài khoản PUBG Level Max', 'Nhiều skin súng và trang phục', 600000, 'available'); 