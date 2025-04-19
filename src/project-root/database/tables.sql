-- Tạo bảng games
CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng accounts
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

-- Thêm một số dữ liệu mẫu cho games
INSERT INTO games (name, description) VALUES
('League of Legends', 'Game MOBA phổ biến nhất thế giới'),
('Valorant', 'Game FPS chiến thuật từ Riot Games'),
('Genshin Impact', 'Game nhập vai thế giới mở'),
('PUBG', 'Game battle royale nổi tiếng');

-- Cập nhật cấu trúc bảng users nếu chưa có cột role
ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('user', 'admin') DEFAULT 'user'; 