-- Thêm dữ liệu mẫu cho bảng accounts
INSERT INTO accounts (game_id, seller_id, title, username, password, basic_description, detailed_description, price, status, images)
VALUES 
(1, 1, 'Tài khoản Genshin Impact AR55', 'user123', 'pass123', 'Tài khoản Genshin Impact cấp độ cao', 'Chi tiết: AR55, nhiều nhân vật 5 sao', 1500000, 'available', 
'{"main": "/public/img/accounts/default/default-account.svg", "sub": []}'),

(1, 1, 'Tài khoản Genshin Impact AR45', 'user124', 'pass124', 'Tài khoản Genshin Impact trung bình', 'Chi tiết: AR45, vài nhân vật 5 sao', 800000, 'available',
'{"main": "/public/img/accounts/default/default-account.svg", "sub": []}'),

(2, 1, 'Tài khoản Honkai Star Rail', 'user125', 'pass125', 'Tài khoản Honkai Star Rail mới', 'Chi tiết: Level 40, nhân vật hiếm', 500000, 'available',
'{"main": "/public/img/accounts/default/default-account.svg", "sub": []}'),

(2, 1, 'Tài khoản Honkai Star Rail VIP', 'user126', 'pass126', 'Tài khoản Honkai Star Rail cao cấp', 'Chi tiết: Level 60, nhiều nhân vật SSR', 2000000, 'available',
'{"main": "/public/img/accounts/default/default-account.svg", "sub": []}'),

(3, 1, 'Tài khoản PUBG Mobile', 'user127', 'pass127', 'Tài khoản PUBG Mobile Royal Pass', 'Chi tiết: Royal Pass season 20, nhiều skin hiếm', 1000000, 'available',
'{"main": "/public/img/accounts/default/default-account.svg", "sub": []}');

-- Kiểm tra dữ liệu đã thêm
SELECT * FROM accounts; 