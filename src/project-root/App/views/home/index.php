<?php
$pageTitle = 'Trang chủ';
$content = __FILE__;

// Khởi tạo các model cần thiết
$gameModel = new \App\Models\Game();
$accountModel = new \App\Models\Account();

// Lấy danh sách game
$games = $gameModel->getAllGames();

// Lấy số lượng tài khoản cho mỗi game
$accountStats = $accountModel->getAccountsByGame();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Mua bán tài khoản game</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/public/css/style.css">
    <style>
        .category-card {
            position: relative;
            padding: 20px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-image {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .category-card:hover .category-image img {
            transform: scale(1.05);
        }
        .category-icon {
            position: absolute;
            top: 30px;
            right: 30px;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .category-icon i {
            font-size: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <?php require_once ROOT_PATH . '/App/views/layouts/header.php'; ?>

    <main class="main-content">
        <section class="hero-section">
            <div class="container">
                <h1>Chào mừng đến với GameAccount</h1>
                <p>Nơi mua bán tài khoản game uy tín và an toàn</p>
                <a href="<?php echo BASE_PATH; ?>/accounts" class="btn btn-primary">Xem danh sách tài khoản</a>
            </div>
        </section>

        <section class="features-section">
            <div class="container">
                <h2>Tại sao chọn chúng tôi?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-shield-alt"></i>
                        <h3>An toàn</h3>
                        <p>Bảo mật thông tin và giao dịch an toàn</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-bolt"></i>
                        <h3>Nhanh chóng</h3>
                        <p>Giao dịch nhanh chóng và tiện lợi</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-headset"></i>
                        <h3>Hỗ trợ 24/7</h3>
                        <p>Đội ngũ hỗ trợ luôn sẵn sàng giúp đỡ</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="account-categories">
            <div class="container">
                <h2>Danh mục tài khoản game</h2>
                <div class="categories-grid">
                    <?php foreach ($games as $game): 
                        $accountCount = isset($accountStats[$game['id']]) ? $accountStats[$game['id']] : 0;
                        $gameIcon = 'fa-gamepad'; // Default icon
                        
                        // Xác định icon phù hợp cho từng game
                        switch(strtolower($game['slug'])) {
                            case 'genshin-impact':
                                $gameIcon = 'fa-gamepad';
                                break;
                            case 'honkai-star-rail':
                                $gameIcon = 'fa-train';
                                break;
                            case 'lien-minh':
                                $gameIcon = 'fa-chess-knight';
                                break;
                            case 'valorant':
                                $gameIcon = 'fa-crosshairs';
                                break;
                        }
                    ?>
                    <div class="category-card">
                        <div class="category-image">
                            <img src="<?php echo BASE_PATH . ($game['image'] ?? '/public/images/default-game.png'); ?>" alt="<?php echo htmlspecialchars($game['name']); ?>">
                        </div>
                        <div class="category-icon">
                            <i class="fas <?php echo $gameIcon; ?>"></i>
                        </div>
                        <h3>Tài khoản <?php echo htmlspecialchars($game['name']); ?></h3>
                        <p><?php echo htmlspecialchars($game['short_description']); ?></p>
                        <div class="category-stats">
                            <span><i class="fas fa-users"></i> <?php echo number_format($accountCount); ?> tài khoản</span>
                            <span><i class="fas fa-star"></i> 4.8/5</span>
                        </div>
                        <a href="<?php echo BASE_PATH; ?>/accounts/game/<?php echo $game['slug']; ?>" class="btn btn-secondary">Xem thêm</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php require_once ROOT_PATH . '/App/views/layouts/footer.php'; ?>

    <script src="<?php echo BASE_PATH; ?>/public/js/main.js"></script>
</body>
</html> 