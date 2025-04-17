<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Mua bán tài khoản game</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/public/css/style.css">
</head>
<body>
    <?php require_once BASE_PATH . '/App/views/layouts/header.php'; ?>

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

        <section class="popular-games">
            <div class="container">
                <h2>Game phổ biến</h2>
                <div class="games-grid">
                    <div class="game-card">
                        <img src="<?php echo BASE_PATH; ?>/public/img/lol.jpg" alt="League of Legends">
                        <h3>League of Legends</h3>
                    </div>
                    <div class="game-card">
                        <img src="<?php echo BASE_PATH; ?>/public/img/valorant.jpg" alt="Valorant">
                        <h3>Valorant</h3>
                    </div>
                    <div class="game-card">
                        <img src="<?php echo BASE_PATH; ?>/public/img/pubg.jpg" alt="PUBG">
                        <h3>PUBG</h3>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once BASE_PATH . '/App/views/layouts/footer.php'; ?>

    <script src="<?php echo BASE_PATH; ?>/public/js/main.js"></script>
</body>
</html> 