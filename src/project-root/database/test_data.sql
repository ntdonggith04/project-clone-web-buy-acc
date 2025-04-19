-- Insert test account
INSERT INTO accounts (
    game_type,
    seller_id,
    username,
    password,
    basic_description,
    detailed_description,
    price,
    details,
    status,
    created_at
) VALUES (
    'lienquan',
    1,
    'test_account',
    'test123',
    'Acc Liên Quân rank Cao Thủ 100 tướng',
    'Chi tiết tài khoản:\n- Rank: Cao Thủ\n- Số tướng: 100\n- Trang phục: 50\n- Bảng ngọc: Full',
    500000,
    '{"rank": "Cao Thủ", "champions": "100", "skins": "50"}',
    'available',
    NOW()
); 