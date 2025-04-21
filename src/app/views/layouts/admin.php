<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Trang quản trị | ' . SITE_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_URL ?>favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #5e72e4;
            --secondary-color: #f5365c;
            --success-color: #2dce89;
            --info-color: #11cdef;
            --warning-color: #fb6340;
            --danger-color: #f5365c;
            --light-color: #f8f9fe;
            --dark-color: #172b4d;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fe;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background-color: white;
            box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
            z-index: 1;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .sidebar-logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: #525f7f;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            border-left: 3px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-link:hover {
            background-color: rgba(94, 114, 228, 0.05);
            color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .sidebar-link.active {
            background-color: rgba(94, 114, 228, 0.1);
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
            font-weight: 600;
            box-shadow: 0 0 10px rgba(94, 114, 228, 0.1);
        }
        
        .sidebar-link.active i {
            color: var(--primary-color);
        }
        
        .sidebar-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .sidebar-link:hover::after {
            width: 100%;
        }
        
        .sidebar-icon {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }
        
        .sidebar-link:hover .sidebar-icon {
            transform: translateY(-2px);
            color: var(--primary-color);
        }
        
        .sidebar-link.active .sidebar-icon {
            transform: scale(1.1);
        }
        
        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .navbar-admin {
            background-color: transparent;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .admin-container {
            padding: 1.5rem;
        }
        
        /* Cards */
        .card {
            border: 0;
            box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
            border-radius: 0.5rem;
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
            margin-bottom: 0;
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        /* Stats Cards */
        .icon-shape {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .border-radius-md {
            border-radius: 0.5rem;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%);
        }
        
        .bg-gradient-danger {
            background: linear-gradient(87deg, #f5365c 0, #f56036 100%);
        }
        
        .bg-gradient-warning {
            background: linear-gradient(87deg, #fb6340 0, #fbb140 100%);
        }
        
        .bg-gradient-dark {
            background: linear-gradient(87deg, #172b4d 0, #1a174d 100%);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-250px);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Table */
        .table thead th {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table td {
            vertical-align: middle;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 0.375rem;
            object-fit: cover;
        }
        
        /* Badges */
        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 500;
            border-radius: 0.25rem;
        }
        
        .badge.badge-sm {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
        }
        
        .bg-gradient-success {
            background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%);
        }
        
        .bg-gradient-warning {
            background: linear-gradient(87deg, #fb6340 0, #fbb140 100%);
        }
        
        .bg-gradient-danger {
            background: linear-gradient(87deg, #f5365c 0, #f56036 100%);
        }
        
        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.01);
        }
        
        /* Dropdown Actions */
        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 400;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: rgba(94, 114, 228, 0.05);
        }
        
        .dropdown-item i {
            margin-right: 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="<?= BASE_URL ?>admin" class="sidebar-logo text-decoration-none">
                <i class="fas fa-shield-alt"></i> Admin Panel
            </a>
        </div>
        <div class="sidebar-nav">
            <?php
            // Lấy URL hiện tại
            $current_url = $_SERVER['REQUEST_URI'];
            $current_path = parse_url($current_url, PHP_URL_PATH);
            $current_path = trim($current_path, '/');
            $base_url_path = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
            
            // Loại bỏ base_url nếu có
            if (!empty($base_url_path)) {
                $current_path = str_replace($base_url_path, '', $current_path);
                $current_path = trim($current_path, '/');
            }
            
            // Tách đường dẫn thành mảng các phần
            $path_parts = explode('/', $current_path);
            
            // Xác định phần chính của đường dẫn (sau "admin/")
            $main_section = isset($path_parts[1]) ? $path_parts[1] : '';
            ?>
            
            <a href="<?= BASE_URL ?>admin" class="sidebar-link <?= ($current_path == 'admin' || empty($current_path)) ? 'active' : '' ?>">
                <i class="fas fa-chart-line sidebar-icon"></i> Thống kê
            </a>
            <a href="<?= BASE_URL ?>admin/accounts" class="sidebar-link <?= $main_section == 'accounts' ? 'active' : '' ?>">
                <i class="fas fa-gamepad sidebar-icon"></i> Quản lý tài khoản game
            </a>
            <a href="<?= BASE_URL ?>admin/orders" class="sidebar-link <?= $main_section == 'orders' ? 'active' : '' ?>">
                <i class="fas fa-shopping-cart sidebar-icon"></i> Quản lý đơn hàng
            </a>
            <a href="<?= BASE_URL ?>admin/users" class="sidebar-link <?= $main_section == 'users' ? 'active' : '' ?>">
                <i class="fas fa-users sidebar-icon"></i> Quản lý người dùng
            </a>
            <a href="<?= BASE_URL ?>admin/games" class="sidebar-link <?= $main_section == 'games' ? 'active' : '' ?>">
                <i class="fas fa-dice sidebar-icon"></i> Quản lý danh mục
            </a>
            <a href="<?= BASE_URL ?>admin/transactions" class="sidebar-link <?= $main_section == 'transactions' ? 'active' : '' ?>">
                <i class="fas fa-money-bill-wave sidebar-icon"></i> Giao dịch
            </a>
            <hr />
            <a href="<?= BASE_URL ?>" class="sidebar-link">
                <i class="fas fa-home sidebar-icon"></i> Trang chủ
            </a>
            <a href="<?= BASE_URL ?>logout" class="sidebar-link">
                <i class="fas fa-sign-out-alt sidebar-icon"></i> Đăng xuất
            </a>
        </div>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <nav class="navbar navbar-admin">
            <div class="container-fluid">
                <button type="button" class="btn btn-sm px-3 d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="d-flex align-items-center ms-auto">
                    <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> <?= $_SESSION['username'] ?? 'Admin' ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>profile"><i class="fas fa-user me-2"></i>Hồ sơ</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="admin-container">
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?= $content ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html> 