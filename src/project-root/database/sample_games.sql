USE account_db;

INSERT INTO games (name, slug, description) VALUES
('Genshin Impact', 'genshin-impact', 'Game nhập vai thế giới mở'),
('Honkai: Star Rail', 'honkai-star-rail', 'Game nhập vai theo lượt'),
('Liên Minh Huyền Thoại', 'lien-minh', 'Game MOBA 5v5'),
('Valorant', 'valorant', 'Game bắn súng chiến thuật 5v5'),
('PUBG', 'pubg', 'Game battle royale')
ON DUPLICATE KEY UPDATE 
name = VALUES(name),
description = VALUES(description); 