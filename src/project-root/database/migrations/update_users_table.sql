-- Kiểm tra và tạo bảng users nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    avatar VARCHAR(255) DEFAULT '/images/avatars/default.png',
    status ENUM('active', 'banned') NOT NULL DEFAULT 'active',
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_token_expiry DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Cập nhật role seller thành user
UPDATE users SET role = 'user' WHERE role = 'seller';

-- Thêm cột status nếu chưa tồn tại
ALTER TABLE users
ADD COLUMN IF NOT EXISTS status ENUM('active', 'banned') NOT NULL DEFAULT 'active';

-- Thêm cột avatar nếu chưa tồn tại
ALTER TABLE users
ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) DEFAULT '/images/avatars/default.png';

-- Cập nhật giá trị mặc định cho các cột hiện có
UPDATE users SET status = 'active' WHERE status IS NULL;
UPDATE users SET avatar = '/images/avatars/default.png' WHERE avatar IS NULL; 