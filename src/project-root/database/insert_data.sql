-- Thêm dữ liệu mẫu cho bảng users (nếu chưa có)
INSERT INTO users (username, email, password, role) VALUES 
('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Mật khẩu là "password"

-- Thêm dữ liệu mẫu cho bảng games (nếu chưa có)
INSERT INTO games (name, description) VALUES
('League of Legends', 'Game MOBA phổ biến nhất thế giới'),
('Valorant', 'Game FPS chiến thuật từ Riot Games'),
('Genshin Impact', 'Game nhập vai thế giới mở'),
('PUBG', 'Game battle royale nổi tiếng'),
('FIFA Online 4', 'Game bóng đá trực tuyến'),
('Counter-Strike 2', 'Game FPS huyền thoại');

-- Thêm dữ liệu mẫu cho bảng accounts
INSERT INTO accounts (game_id, seller_id, title, description, price, status) VALUES
(1, 1, 'Tài khoản LOL Rank Cao Thủ', 'Có 100 tướng, 50 trang phục, Rank Cao Thủ 500LP', 1500000, 'available'),
(1, 1, 'Nick LOL Rank Kim Cương', 'Full tướng, nhiều skin hiếm, rank Kim Cương 2', 800000, 'available'),
(2, 1, 'Account Valorant Full Skin', 'Full skin súng hiếm, BP full, rank Bất Tử', 1200000, 'available'),
(2, 1, 'Nick Valorant Premium', 'Nhiều skin knife, skin phantom, vandal đẹp', 600000, 'available'),
(3, 1, 'Tài khoản Genshin Impact AR60', 'Nhiều nhân vật 5 sao, vũ khí 5 sao, AR60', 2500000, 'available'),
(3, 1, 'Account Genshin AR55+', 'Có Raiden, Yelan, Ayaka C1, nhiều vũ khí 5 sao', 1500000, 'available'),
(4, 1, 'PUBG Account Level 70', 'Nhiều skin súng, trang phục hiếm, level 70', 900000, 'available'),
(4, 1, 'Nick PUBG Premium', 'Full set trang phục hiếm, nhiều skin súng đẹp', 700000, 'available'),
(5, 1, 'FIFA Online 4 VIP', 'Đội hình khủng, nhiều cầu thủ ICON', 1800000, 'available'),
(6, 1, 'CS2 Account Prime', 'Rank Cao, nhiều skin súng hiếm, Prime Status', 1000000, 'available');

-- Thêm một số tài khoản đã bán để có dữ liệu thống kê
INSERT INTO accounts (game_id, seller_id, title, description, price, status) VALUES
(1, 1, 'Tài khoản LOL Thách Đấu', 'Rank Thách Đấu, full tướng và trang phục', 3000000, 'sold'),
(2, 1, 'Valorant Radiant Account', 'Rank Radiant, full skin collection', 2500000, 'sold'),
(3, 1, 'Genshin Impact Whale Account', 'AR60, C6 nhiều nhân vật giới hạn', 5000000, 'sold'),
(4, 1, 'PUBG Pro Account', 'Level max, collection skin full', 1500000, 'sold'),
(5, 1, 'FIFA Online 4 Ultimate Team', 'Full team ICON và GOAT', 3000000, 'sold'); 