-- Thêm cột status vào bảng users nếu chưa tồn tại
ALTER TABLE users
ADD COLUMN IF NOT EXISTS status ENUM('active', 'banned') NOT NULL DEFAULT 'active';

-- Cập nhật status cho các user hiện tại
UPDATE users SET status = 'active' WHERE status IS NULL; 