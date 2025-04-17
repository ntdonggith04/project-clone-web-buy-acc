<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách tài khoản</title>
    <link rel="stylesheet" href="/project-clone-web-buy-acc/src/project-root/public/css/style.css">
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php require_once BASE_PATH . '/App/views/layouts/header.php'; ?>

    <main class="container">
        <?php if (isset($success_message)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="accounts-page">
            <h1 class="page-title">Tài khoản game</h1>
            
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm tài khoản...">
                    <button type="button" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <div class="filter-options">
                    <select id="gameFilter">
                        <option value="">Tất cả game</option>
                        <option value="lienquan">Liên Quân Mobile</option>
                        <option value="pubg">PUBG Mobile</option>
                        <option value="genshin">Genshin Impact</option>
                        <option value="freefire">Free Fire</option>
                    </select>
                    
                    <select id="priceFilter">
                        <option value="">Giá</option>
                        <option value="asc">Giá thấp đến cao</option>
                        <option value="desc">Giá cao đến thấp</option>
                    </select>
                </div>
            </div>

            <!-- Accounts Grid -->
            <div class="accounts-grid">
                <?php if (!empty($accounts)): ?>
                    <?php foreach ($accounts as $account): ?>
                        <div class="account-card">
                            <div class="account-image">
                                <img src="<?php echo $account['image_url']; ?>" alt="<?php echo $account['game_name']; ?>">
                            </div>
                            <div class="account-info">
                                <h3 class="game-name"><?php echo $account['game_name']; ?></h3>
                                <p class="account-details">
                                    <span class="detail-item">
                                        <i class="fas fa-level-up-alt"></i>
                                        Level: <?php echo $account['level']; ?>
                                    </span>
                                    <span class="detail-item">
                                        <i class="fas fa-star"></i>
                                        Rank: <?php echo $account['rank']; ?>
                                    </span>
                                </p>
                                <div class="price-section">
                                    <span class="price"><?php echo number_format($account['price'], 0, ',', '.'); ?>đ</span>
                                    <a href="/project-clone-web-buy-acc/src/project-root/public/accounts/<?php echo $account['id']; ?>" class="view-details-btn">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-accounts">Hiện chưa có tài khoản nào được đăng bán.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <a href="#" class="page-link">&laquo;</a>
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link">3</a>
                <a href="#" class="page-link">&raquo;</a>
            </div>
        </div>
    </main>

    <?php require_once BASE_PATH . '/App/views/layouts/footer.php'; ?>

    <style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .accounts-page {
        padding: 20px 0;
    }

    .page-title {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }

    .filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .search-box {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px 10px;
        flex: 1;
        max-width: 400px;
    }

    .search-box input {
        border: none;
        outline: none;
        padding: 8px;
        width: 100%;
    }

    .search-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #666;
    }

    .filter-options {
        display: flex;
        gap: 10px;
    }

    .filter-options select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #fff;
        cursor: pointer;
    }

    .accounts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .account-card {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .account-card:hover {
        transform: translateY(-5px);
    }

    .account-image {
        height: 200px;
        overflow: hidden;
    }

    .account-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .account-info {
        padding: 15px;
    }

    .game-name {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 1.2em;
    }

    .account-details {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 15px;
        color: #666;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .price-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }

    .price {
        font-size: 1.2em;
        font-weight: bold;
        color: #e74c3c;
    }

    .view-details-btn {
        background: #3498db;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .view-details-btn:hover {
        background: #2980b9;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 30px;
    }

    .page-link {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s ease;
    }

    .page-link:hover, .page-link.active {
        background: #3498db;
        color: white;
        border-color: #3498db;
    }

    @media (max-width: 768px) {
        .filter-section {
            flex-direction: column;
        }
        
        .search-box {
            max-width: 100%;
        }
        
        .filter-options {
            width: 100%;
            justify-content: space-between;
        }
        
        .accounts-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }
    </style>
</body>
</html> 