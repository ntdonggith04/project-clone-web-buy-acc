-- Tạo bảng games nếu chưa có
CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    price_range VARCHAR(100),
    total_accounts INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tạo bảng game_accounts nếu chưa có
CREATE TABLE IF NOT EXISTS game_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    username VARCHAR(100),
    password VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    images TEXT,
    game_level INT,
    game_rank VARCHAR(50),
    game_server VARCHAR(100),
    special_attributes TEXT,
    status ENUM('available', 'sold', 'pending') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
);

-- Thêm dữ liệu mẫu vào bảng games
INSERT INTO games (name, slug, description, image, status) VALUES
('League of Legends', 'league-of-legends', 'League of Legends là game MOBA phổ biến nhất thế giới với hơn 140 tướng khác nhau. Trở thành nhà vô địch trên đấu trường công lý với tài khoản đẳng cấp từ chúng tôi.', 'lol.jpg', 1),
('Valorant', 'valorant', 'Valorant là game FPS chiến thuật 5v5 từ Riot Games. Tham gia trận chiến căng thẳng với các đặc vụ có kỹ năng độc đáo và vũ khí chết người.', 'valorant.jpg', 1),
('PUBG', 'pubg', 'PlayerUnknown\'s Battlegrounds là game battle royale hàng đầu. Chiến đấu để trở thành người sống sót cuối cùng trong 100 người chơi.', 'pubg.jpg', 1),
('Genshin Impact', 'genshin-impact', 'Genshin Impact là game nhập vai thế giới mở với đồ họa tuyệt đẹp. Khám phá thế giới Teyvat rộng lớn và sưu tầm các nhân vật mạnh mẽ.', 'genshin.jpg', 1),
('Mobile Legends', 'mobile-legends', 'Mobile Legends: Bang Bang là game MOBA di động phổ biến với các trận đấu 5v5 nhanh chóng và gay cấn.', 'ml.jpg', 1);

-- Thêm dữ liệu mẫu vào bảng game_accounts
INSERT INTO game_accounts (game_id, title, description, price, game_rank, game_level, status) VALUES
(1, 'Tài khoản LOL Rank Vàng', 'Tài khoản có 50 tướng, 30 trang phục, đã rank Vàng season hiện tại', 200000, 'Gold', 100, 'available'),
(1, 'Tài khoản LOL Rank Bạch Kim', 'Tài khoản full tướng, 50+ trang phục, rank Bạch Kim', 500000, 'Platinum', 150, 'available'),
(1, 'Tài khoản LOL Rank Kim Cương', 'Tài khoản VIP với nhiều trang phục hiếm, rank Kim Cương', 1200000, 'Diamond', 200, 'available'),
(2, 'Tài khoản Valorant có skin', 'Tài khoản có nhiều skin súng đẹp, đã mở khóa tất cả đặc vụ', 300000, 'Gold', 50, 'available'),
(2, 'Tài khoản Valorant rank cao', 'Tài khoản rank Immortal, nhiều skin hiếm', 800000, 'Immortal', 100, 'available'),
(3, 'Tài khoản PUBG level cao', 'Tài khoản level 70, nhiều skin súng và trang phục', 250000, 'Experienced', 70, 'available'),
(4, 'Tài khoản Genshin Impact AR45', 'Tài khoản có nhiều nhân vật 5 sao, vũ khí tốt', 400000, 'High', 45, 'available'),
(4, 'Tài khoản Genshin Impact AR55', 'Tài khoản VIP có hầu hết nhân vật 5 sao', 1500000, 'Very High', 55, 'available'),
(5, 'Tài khoản Mobile Legends Rank Cao Thủ', 'Tài khoản có nhiều skin epic và legend', 350000, 'Mythic', 60, 'available'); 