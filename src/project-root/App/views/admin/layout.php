<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Shop Account Game</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-bg: linear-gradient(180deg, #1a237e, #303f9f);
            --sidebar-hover: rgba(255,255,255,0.1);
            --sidebar-active: rgba(255,255,255,0.2);
            --transition-speed: 0.3s;
            --sidebar-width: 250px;
        }

        body {
            overflow-x: hidden;
        }

        .dashboard {
            padding: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform var(--transition-speed);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            margin: 0;
            font-size: 16px;
            color: #666;
        }

        .stat-card .number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }

        .card {
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        .card-title {
            margin: 0;
            font-size: 18px;
        }

        .table {
            margin: 0;
        }

        .navbar-brand {
            font-weight: bold;
            padding: 15px 20px;
            font-size: 1.5rem;
            background: rgba(0,0,0,0.2);
            margin: 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .toggle-sidebar {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 0;
            cursor: pointer;
        }

        .admin-profile {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
            color: white;
            transition: all var(--transition-speed);
        }

        .admin-profile:hover {
            background: rgba(0,0,0,0.2);
        }

        .admin-profile .admin-name {
            font-size: 1rem;
            font-weight: 500;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-profile .admin-role {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
            margin: 0;
        }

        .admin-profile .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 12px;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            padding-top: 0;
            position: fixed;
            width: var(--sidebar-width);
            z-index: 1000;
            transition: all var(--transition-speed);
            left: 0;
        }

        .sidebar.collapsed {
            left: calc(-1 * var(--sidebar-width));
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 12px 20px;
            transition: all var(--transition-speed);
            position: relative;
            display: flex;
            align-items: center;
            border-radius: 0 30px 30px 0;
            margin: 4px 0;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: var(--sidebar-hover);
            padding-left: 25px;
        }

        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            font-weight: 500;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #fff;
        }

        .main-content {
            padding: 20px;
            margin-left: var(--sidebar-width);
            transition: margin var(--transition-speed);
            width: calc(100% - var(--sidebar-width));
        }

        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.mobile-show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .toggle-sidebar {
                display: block;
            }

            .overlay.show {
                display: block;
            }

            .admin-profile {
                padding: 10px 15px;
            }

            .admin-profile .admin-name {
                font-size: 0.9rem;
            }

            .admin-profile .admin-role {
                font-size: 0.8rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
                padding: 10px 15px;
            }
        }

        /* Animation for menu items */
        .nav-item {
            opacity: 0;
            animation: fadeInLeft 0.5s ease forwards;
        }

        .nav-item:nth-child(1) { animation-delay: 0.1s; }
        .nav-item:nth-child(2) { animation-delay: 0.2s; }
        .nav-item:nth-child(3) { animation-delay: 0.3s; }
        .nav-item:nth-child(4) { animation-delay: 0.4s; }
        .nav-item:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto px-0">
                <div class="sidebar">
                    <div class="navbar-brand text-white">
                        <span><i class="fas fa-gamepad me-2"></i>Admin Panel</span>
                        <button class="toggle-sidebar d-md-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="admin-profile d-flex align-items-center">
                        <div class="admin-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="admin-info">
                            <p class="admin-name"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?></p>
                            <p class="admin-role"><?php echo isset($_SESSION['role']) && $_SESSION['role'] === 'admin' ? 'Administrator' : 'User'; ?></p>
                        </div>
                    </div>
                    <nav class="mt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin') !== false && $_SERVER['REQUEST_URI'] == BASE_PATH . '/admin' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin">
                                    <i class="fas fa-home"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/users">
                                    <i class="fas fa-users"></i>
                                    Quản lý người dùng
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/games') !== false ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/games">
                                    <i class="fas fa-gamepad"></i>
                                    Quản lý danh mục
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/accounts') !== false ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/accounts">
                                    <i class="fas fa-user-circle"></i>
                                    Quản lý tài khoản game
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo BASE_PATH; ?>/logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Main content -->
            <div class="col main-content">
                <div class="d-md-none mb-3">
                    <button class="toggle-sidebar btn btn-primary">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <?php echo $content; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const overlay = document.querySelector('.overlay');
            const toggleButtons = document.querySelectorAll('.toggle-sidebar');

            function toggleSidebar() {
                sidebar.classList.toggle('mobile-show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('mobile-show') ? 'hidden' : '';
            }

            toggleButtons.forEach(button => {
                button.addEventListener('click', toggleSidebar);
            });

            overlay.addEventListener('click', toggleSidebar);

            // Close sidebar when clicking on a link (mobile)
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        toggleSidebar();
                    }
                });
            });

            // Handle resize
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('mobile-show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
</body>
</html> 