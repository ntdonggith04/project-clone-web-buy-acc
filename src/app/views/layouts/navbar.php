<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="<?= BASE_URL ?>">Game Account Store</a>
        
        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Nav links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item mx-1">
                    <a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" href="<?= BASE_URL ?>games">Danh mục game</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" href="<?= BASE_URL ?>accounts">Tài khoản game</a>
                </li>
            </ul>
            
            <!-- Right side items -->
            <div class="d-flex align-items-center">
                <!-- Auth buttons -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            <?= $_SESSION['username']; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>dashboard"><i class="fas fa-tachometer-alt me-2"></i>Đơn đã mua</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>profile"><i class="fas fa-user me-2"></i>Tài khoản</a></li>
                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>admin"><i class="fas fa-cog me-2"></i>Quản trị</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>login" class="btn btn-outline-primary me-3">Đăng nhập</a>
                    <a href="<?= BASE_URL ?>register" class="btn btn-primary">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Modern Navbar Styling */
    .navbar {
        background-color: rgba(252, 253, 255, 0.95);
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        padding: 15px 0;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        height: 70px;
    }
    
    .navbar-brand {
        font-weight: 900;
        color: var(--primary-color, #5e72e4);
        font-size: 1.6rem;
        background: linear-gradient(45deg, var(--primary-color, #5e72e4), #5580f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: all 0.3s ease;
        margin-right: 25px;
    }
    
    .navbar-brand:hover {
        transform: scale(1.05);
    }
    
    .nav-link {
        color: #525f7f;
        font-weight: 600;
        padding: 0.7rem 1.2rem;
        border-radius: 6px;
        transition: all 0.3s ease;
        position: relative;
        margin: 0 3px;
    }
    
    .nav-link:hover {
        color: var(--primary-color, #5e72e4);
        background-color: rgba(94, 114, 228, 0.1);
    }
    
    .nav-link.active {
        color: var(--primary-color, #5e72e4);
        background-color: rgba(94, 114, 228, 0.1);
    }
    
    .nav-link:after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        background-color: var(--primary-color, #5e72e4);
        bottom: 5px;
        left: 50%;
        transform: translateX(-50%);
        transition: width 0.3s;
    }
    
    .nav-link:hover:after,
    .nav-link.active:after {
        width: 50%;
    }
    
    .navbar-nav {
        column-gap: 5px;
    }
    
    .nav-item {
        display: flex;
        align-items: center;
    }
    
    /* Dropdown styling */
    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    
    .dropdown-item {
        padding: 0.5rem 1rem;
        border-radius: 5px;
        transition: all 0.2s ease;
        color: #525f7f;
        font-weight: 500;
    }
    
    .dropdown-item:hover {
        background-color: rgba(94, 114, 228, 0.1);
        color: var(--primary-color, #5e72e4);
        transform: translateX(5px);
    }
    
    .dropdown-divider {
        border-color: rgba(0, 0, 0, 0.05);
    }
    
    /* Buttons */
    .btn-outline-primary {
        border-radius: 20px;
        border: 1px solid var(--primary-color, #5e72e4);
        color: var(--primary-color, #5e72e4);
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color, #5e72e4);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(94, 114, 228, 0.3);
    }
    
    .btn-primary {
        border-radius: 20px;
        background-color: var(--primary-color, #5e72e4);
        border: none;
        padding: 0.5rem 1.2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    
    .btn-primary:hover {
        background-color: #4a5dd0;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(94, 114, 228, 0.3);
    }
    
    /* Icons */
    .nav-icon {
        font-size: 1.2rem;
        color: #525f7f;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .nav-icon:hover {
        color: var(--primary-color, #5e72e4);
        background-color: rgba(94, 114, 228, 0.1);
        transform: translateY(-2px);
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .navbar-collapse {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
        }
        
        .nav-link:after {
            display: none;
        }
        
        .nav-icon {
            margin-top: 10px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Đánh dấu menu active dựa theo URL hiện tại
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        // Xóa tất cả active trước
        navLinks.forEach(link => link.classList.remove('active'));
        
        // Tìm liên kết phù hợp nhất (match dài nhất)
        let bestMatch = null;
        let bestMatchLength = -1;
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            // Chuyển đổi href thành đường dẫn tương đối nếu cần
            const hrefPath = href.includes('://') ? new URL(href).pathname : href.replace(/^(?:\/\/|[^/]+)*\//, '/');
            
            // Kiểm tra xem hrefPath có là một phần của currentPath không
            // Và đảm bảo đây là phần match dài nhất
            if (currentPath === hrefPath || (currentPath.startsWith(hrefPath) && hrefPath !== '/' && hrefPath.length > bestMatchLength)) {
                bestMatch = link;
                bestMatchLength = hrefPath.length;
            }
        });
        
        // Trường hợp đặc biệt cho trang chủ
        if (bestMatchLength === -1 && (currentPath === '/' || currentPath === '')) {
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === '<?= BASE_URL ?>') {
                    bestMatch = link;
                }
            });
        }
        
        // Áp dụng active cho liên kết phù hợp nhất
        if (bestMatch) {
            bestMatch.classList.add('active');
        }
        
        // Hiệu ứng scroll navbar
        const navbar = document.querySelector('.navbar');
        
        function updateNavbarOnScroll() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        
        // Kiểm tra ngay khi trang tải xong
        updateNavbarOnScroll();
        
        // Cập nhật khi cuộn
        window.addEventListener('scroll', updateNavbarOnScroll);
    });
</script>