<?php
$pageTitle = 'Danh sách tài khoản';
$content = __FILE__;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/public/css/accounts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        main.container {
            margin-top: 80px; /* Add space between header and content */
            min-height: calc(100vh - 80px); /* Ensure content fills the viewport height minus header */
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
        }

        .accounts-page {
            padding: 20px 0;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php require_once ROOT_PATH . '/App/views/layouts/header.php'; ?>

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
            <div class="container">
                <h1 class="page-title">Danh sách tài khoản game</h1>
                
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
                            <?php if (isset($games) && is_array($games)): ?>
                                <?php foreach ($games as $game): ?>
                                    <option value="<?php echo htmlspecialchars($game['slug'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($game['name'] ?? 'Unknown Game'); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        
                        <select id="priceFilter">
                            <option value="">Mức giá</option>
                            <option value="0-500000">Dưới 500.000đ</option>
                            <option value="500000-1000000">500.000đ - 1.000.000đ</option>
                            <option value="1000000-2000000">1.000.000đ - 2.000.000đ</option>
                            <option value="2000000">Trên 2.000.000đ</option>
                        </select>

                        <select id="sortFilter">
                            <option value="">Sắp xếp</option>
                            <option value="price_asc">Giá tăng dần</option>
                            <option value="price_desc">Giá giảm dần</option>
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                        </select>
                    </div>
                </div>

                <!-- Game Categories -->
                <div class="game-categories">
                    <?php if (isset($games) && is_array($games)): ?>
                        <?php foreach ($games as $game): ?>
                            <a href="<?php echo BASE_PATH; ?>/accounts/game/<?php echo htmlspecialchars($game['slug'] ?? ''); ?>" 
                               class="game-category">
                                <img src="<?php echo BASE_PATH; ?>/public/img/games/<?php echo htmlspecialchars($game['slug'] ?? ''); ?>-icon.jpg" 
                                     alt="<?php echo htmlspecialchars($game['name'] ?? ''); ?>"
                                     onerror="this.src='<?php echo BASE_PATH; ?>/public/img/default-game-icon.jpg'">
                                <span><?php echo htmlspecialchars($game['name'] ?? 'Unknown Game'); ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Accounts Grid -->
                <div class="accounts-grid">
                    <?php if (!empty($accounts)): ?>
                        <?php foreach ($accounts as $account): ?>
                            <div class="account-card">
                                <div class="account-image">
                                    <?php 
                                    $main_image = '/public/img/default-account.jpg';
                                    $sub_images = [];
                                    
                                    if (isset($account['images']) && !empty($account['images'])) {
                                        $images = json_decode($account['images'], true);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            $main_image = isset($images['main']) ? $images['main'] : $main_image;
                                            $sub_images = isset($images['sub']) ? $images['sub'] : [];
                                        }
                                    }
                                    
                                    $image_path = BASE_PATH . $main_image;
                                    ?>
                                    <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                         alt="<?php echo htmlspecialchars($account['title'] ?? 'Game Account'); ?>"
                                         onerror="this.src='<?php echo BASE_PATH; ?>/public/img/default-account.jpg'">
                                    
                                    <?php if (!empty($sub_images)): ?>
                                        <div class="image-count">
                                            <i class="fas fa-images"></i>
                                            <span><?php echo count($sub_images) + 1; ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="account-status <?php echo $account['status'] ?? 'available'; ?>">
                                        <?php echo isset($account['status']) && $account['status'] === 'sold' ? 'Đã bán' : 'Còn hàng'; ?>
                                    </div>
                                </div>
                                <div class="account-content">
                                    <div class="account-game">
                                        <?php 
                                        $game_name = htmlspecialchars($account['game_name'] ?? 'Unknown Game');
                                        $game_icon = '/public/img/games/' . strtolower(str_replace(' ', '-', $game_name)) . '-icon.jpg';
                                        $game_icon_path = BASE_PATH . $game_icon;
                                        ?>
                                        <img src="<?php echo htmlspecialchars($game_icon_path); ?>" 
                                             alt="<?php echo $game_name; ?>"
                                             onerror="this.src='<?php echo BASE_PATH; ?>/public/img/default-game-icon.jpg'">
                                        <span><?php echo $game_name; ?></span>
                                    </div>
                                    <h3 class="account-title"><?php echo htmlspecialchars($account['title'] ?? 'No Title'); ?></h3>
                                    <div class="account-description">
                                        <?php echo nl2br(htmlspecialchars($account['basic_description'] ?? '')); ?>
                                    </div>
                                    <div class="account-price">
                                        <span class="price"><?php echo number_format($account['price'] ?? 0, 0, ',', '.'); ?>đ</span>
                                        <a href="<?php echo BASE_PATH; ?>/accounts/<?php echo $account['id'] ?? '0'; ?>" class="btn btn-primary">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-accounts">
                            <i class="fas fa-box-open"></i>
                            <p>Không tìm thấy tài khoản nào</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if (!empty($accounts) && isset($totalPages) && isset($currentPage)): ?>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1; ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?php echo $currentPage + 1; ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php require_once ROOT_PATH . '/App/views/layouts/footer.php'; ?>

    <script src="<?php echo BASE_PATH; ?>/public/js/main.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const gameFilter = document.getElementById('gameFilter');
        const priceFilter = document.getElementById('priceFilter');
        const sortFilter = document.getElementById('sortFilter');
        const accountsGrid = document.querySelector('.accounts-grid');
        
        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Format price function
        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN').format(price);
        }

        // Render account card function
        function renderAccountCard(account) {
            const subImagesCount = Array.isArray(account.sub_images) ? account.sub_images.length : 0;
            const imageCountHtml = subImagesCount > 0 ? `
                <div class="image-count">
                    <i class="fas fa-images"></i>
                    <span>${subImagesCount + 1}</span>
                </div>
            ` : '';

            return `
                <div class="account-card">
                    <div class="account-image">
                        <img src="${BASE_PATH}${account.main_image}" 
                             alt="${account.title}"
                             onerror="this.src='${BASE_PATH}/public/img/default-account.jpg'">
                        ${imageCountHtml}
                        <div class="account-status ${account.status}">
                            ${account.status === 'sold' ? 'Đã bán' : 'Còn hàng'}
                        </div>
                    </div>
                    <div class="account-content">
                        <div class="account-game">
                            <img src="${BASE_PATH}/public/img/games/${account.game_slug}-icon.jpg" 
                                 alt="${account.game_name}"
                                 onerror="this.src='${BASE_PATH}/public/img/default-game-icon.jpg'">
                            <span>${account.game_name}</span>
                        </div>
                        <h3 class="account-title">${account.title}</h3>
                        <div class="account-description">
                            ${account.basic_description}
                        </div>
                        <div class="account-price">
                            <span class="price">${formatPrice(account.price)}đ</span>
                            <a href="${BASE_PATH}/accounts/${account.id}" class="btn btn-primary">Chi tiết</a>
                        </div>
                    </div>
                </div>
            `;
        }

        // Hàm tìm kiếm và lọc tài khoản
        function searchAccounts() {
            const searchValue = searchInput.value;
            const gameValue = gameFilter.value;
            const priceValue = priceFilter.value;
            const sortValue = sortFilter.value;

            // Hiển thị loading
            accountsGrid.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';

            // Tạo URL với các tham số tìm kiếm
            const params = new URLSearchParams({
                search: searchValue,
                game: gameValue,
                price: priceValue,
                sort: sortValue,
                ajax: 1
            });

            // Gọi API tìm kiếm
            fetch(`${window.location.pathname}?${params.toString()}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server không trả về dữ liệu JSON hợp lệ!');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Có lỗi xảy ra khi tải dữ liệu');
                    }

                    if (data.accounts && Array.isArray(data.accounts)) {
                        if (data.accounts.length > 0) {
                            // Render kết quả tìm kiếm
                            accountsGrid.innerHTML = data.accounts.map(renderAccountCard).join('');

                            // Cập nhật URL với tham số tìm kiếm
                            const newUrl = `${window.location.pathname}?${params.toString()}`;
                            window.history.pushState({ path: newUrl }, '', newUrl);
                        } else {
                            // Hiển thị thông báo không có kết quả
                            accountsGrid.innerHTML = `
                                <div class="no-accounts">
                                    <i class="fas fa-box-open"></i>
                                    <p>Không tìm thấy tài khoản nào</p>
                                </div>
                            `;
                        }
                    } else {
                        throw new Error('Dữ liệu không đúng định dạng');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    accountsGrid.innerHTML = `
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Có lỗi xảy ra khi tải dữ liệu</p>
                            <small>${error.message}</small>
                        </div>
                    `;
                });
        }

        // Gắn sự kiện cho các filter
        const debouncedSearch = debounce(searchAccounts, 500);
        searchInput.addEventListener('input', debouncedSearch);
        gameFilter.addEventListener('change', searchAccounts);
        priceFilter.addEventListener('change', searchAccounts);
        sortFilter.addEventListener('change', searchAccounts);

        // Khôi phục trạng thái filter từ URL
        const urlParams = new URLSearchParams(window.location.search);
        searchInput.value = urlParams.get('search') || '';
        gameFilter.value = urlParams.get('game') || '';
        priceFilter.value = urlParams.get('price') || '';
        sortFilter.value = urlParams.get('sort') || '';

        // Nếu có tham số tìm kiếm, thực hiện tìm kiếm ngay
        if (searchInput.value || gameFilter.value || priceFilter.value || sortFilter.value) {
            searchAccounts();
        }
    });
    </script>

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
        position: relative;
        width: 100%;
        padding-top: 75%; /* 4:3 Aspect Ratio */
        overflow: hidden;
        border-radius: 8px 8px 0 0;
    }

    .account-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .account-image:hover img {
        transform: scale(1.05);
    }

    .image-count {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9em;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .account-status {
        position: absolute;
        bottom: 10px;
        left: 10px;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9em;
        font-weight: 500;
    }

    .account-status.available {
        background: #28a745;
        color: white;
    }

    .account-status.sold {
        background: #dc3545;
        color: white;
    }

    .account-content {
        padding: 15px;
    }

    .account-game {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .account-game img {
        width: 30px;
        height: 30px;
        object-fit: cover;
        margin-right: 10px;
    }

    .account-game span {
        font-size: 1.2em;
        font-weight: bold;
        color: #333;
    }

    .account-title {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 1.2em;
    }

    .account-description {
        margin: 10px 0;
        font-size: 0.9em;
        color: #666;
        max-height: 60px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .account-price {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }

    .price {
        font-size: 1.2em;
        font-weight: bold;
        color: #e44d26;
    }

    .btn-primary {
        background: #007bff;
        color: white;
        padding: 8px 20px;
        border-radius: 5px;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .btn-primary:hover {
        background: #0056b3;
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

    .loading {
        text-align: center;
        padding: 40px;
        font-size: 18px;
        color: #666;
    }

    .loading i {
        margin-right: 10px;
    }

    .error-message {
        text-align: center;
        padding: 40px;
        color: #dc3545;
    }

    .error-message i {
        font-size: 48px;
        margin-bottom: 20px;
    }

    .filter-section {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .search-box {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 4px;
        padding: 8px 16px;
        margin-bottom: 15px;
    }

    .search-box input {
        flex: 1;
        border: none;
        background: none;
        padding: 8px;
        font-size: 16px;
        outline: none;
    }

    .search-box .search-btn {
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        padding: 8px;
    }

    .search-box .search-btn:hover {
        color: #333;
    }

    .filter-options {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-options select {
        padding: 8px 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
        cursor: pointer;
        outline: none;
        min-width: 150px;
    }

    .filter-options select:hover {
        border-color: #999;
    }

    .filter-options select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }

    @media (max-width: 768px) {
        .filter-options {
            flex-direction: column;
        }

        .filter-options select {
            width: 100%;
        }
    }
    </style>
</body>
</html> 