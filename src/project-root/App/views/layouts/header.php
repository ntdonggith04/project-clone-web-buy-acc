<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Account Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --text-color: #333;
            --bg-color: #fff;
            --header-height: 70px;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: var(--bg-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: transform var(--transition-speed);
        }

        .header.hide {
            transform: translateY(-100%);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-color);
            font-size: 1.4rem;
            font-weight: bold;
            transition: transform var(--transition-speed);
            flex-shrink: 0;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo i {
            color: var(--primary-color);
            margin-right: 8px;
        }

        .logo span {
            color: var(--primary-color);
        }

        .nav-links {
            display: flex;
            gap: 15px;
            margin: 0;
            flex-grow: 1;
            justify-content: center;
        }

        .nav-link {
            color: var(--text-color);
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 20px;
            transition: all var(--transition-speed);
            position: relative;
            overflow: hidden;
            font-size: 1rem;
        }

        .nav-link.active {
            color: var(--primary-color);
            font-weight: 500;
        }

        .nav-link.active:before {
            width: 100%;
        }

        .nav-link:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width var(--transition-speed);
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link:hover:before {
            width: 100%;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .balance-section {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(52, 152, 219, 0.1);
            white-space: nowrap;
            font-size: 1rem;
        }

        .balance-display {
            display: flex;
            align-items: center;
            gap: 4px;
            color: var(--text-color);
        }

        .balance-amount {
            font-weight: 500;
            color: var(--primary-color);
        }

        .recharge-btn {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 15px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all var(--transition-speed);
            border: none;
            cursor: pointer;
        }

        .recharge-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .recharge-btn i {
            font-size: 0.9em;
        }

        .user-action {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            color: var(--text-color);
            transition: all var(--transition-speed);
            background: transparent;
            border: 1px solid transparent;
        }

        .user-action:hover {
            background: rgba(52, 152, 219, 0.1);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 4px 12px;
            border-radius: 20px;
            transition: all var(--transition-speed);
        }

        .user-profile:hover {
            background: rgba(52, 152, 219, 0.1);
        }

        .avatar-container {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--primary-color);
            transition: all var(--transition-speed);
        }

        .user-profile:hover .avatar-container {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.3);
        }

        .user-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-speed);
        }

        .user-profile:hover .user-avatar {
            transform: scale(1.1);
        }

        .welcome-text {
            font-size: 0.9em;
            color: var(--text-color);
            transition: color var(--transition-speed);
        }

        .user-profile:hover .welcome-text {
            color: var(--primary-color);
        }

        .dropdown-menu {
            position: absolute;
            top: 120%;
            right: 0;
            background: var(--bg-color);
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 10px 0;
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all var(--transition-speed);
        }

        .dropdown-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-color);
            text-decoration: none;
            transition: all var(--transition-speed);
        }

        .dropdown-item:hover {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .dropdown-item i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            transition: transform var(--transition-speed);
        }

        .dropdown-item:hover i {
            transform: scale(1.2);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            transition: all var(--transition-speed);
        }

        .mobile-menu-btn:hover {
            color: var(--primary-color);
            transform: rotate(90deg);
        }

        @media (max-width: 1024px) {
            .header-container {
                padding: 0 15px;
                gap: 10px;
            }

            .nav-links {
                gap: 8px;
            }

            .nav-link {
                padding: 4px 8px;
                font-size: 0.95rem;
            }

            .welcome-text {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
                margin-left: auto;
            }

            .nav-links, .user-actions {
                display: none;
            }

            .nav-links.active, .user-actions.active {
                display: flex;
                position: absolute;
                top: var(--header-height);
                left: 0;
                right: 0;
                background: var(--bg-color);
                padding: 15px;
                flex-direction: column;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .user-actions.active {
                top: auto;
                bottom: 0;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            }

            .balance-section {
                width: 100%;
                justify-content: space-between;
            }

            .user-profile {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="<?php echo BASE_PATH; ?>/" class="logo">
                <i class="fas fa-gamepad"></i> Game<span>Account</span>
            </a>
            
            <nav class="nav-links">
                <a href="<?php echo BASE_PATH; ?>/" class="nav-link <?php echo $_SERVER['REQUEST_URI'] == BASE_PATH . '/' ? 'active' : ''; ?>">Trang chủ</a>
                <a href="<?php echo BASE_PATH; ?>/accounts" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/accounts') !== false ? 'active' : ''; ?>">Tài khoản game</a>
                <a href="<?php echo BASE_PATH; ?>/games" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/games') !== false ? 'active' : ''; ?>">Game phổ biến</a>
            </nav>

            <div class="user-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="balance-section">
                        <div class="balance-display">
                            <i class="fas fa-wallet"></i>
                            <span>Số dư:</span>
                            <span class="balance-amount"><?php echo number_format($_SESSION['balance'] ?? 0, 0, ',', '.'); ?>đ</span>
                        </div>
                        <a href="<?php echo BASE_PATH; ?>/recharge" class="recharge-btn">
                            <i class="fas fa-plus-circle"></i>
                            Nạp tiền
                        </a>
                    </div>
                    <div class="user-profile">
                        <span class="welcome-text">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <div class="avatar-container">
                            <img src="<?php echo BASE_PATH; ?>/images/avatars/default.png" alt="Avatar" class="user-avatar">
                        </div>
                        <div class="dropdown-menu">
                            <a href="<?php echo BASE_PATH; ?>/profile" class="dropdown-item">
                                <i class="fas fa-user-circle"></i> Thông tin cá nhân
                            </a>
                            <a href="<?php echo BASE_PATH; ?>/orders" class="dropdown-item">
                                <i class="fas fa-shopping-bag"></i> Đơn hàng
                            </a>
                            <a href="<?php echo BASE_PATH; ?>/logout" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo BASE_PATH; ?>/login" class="user-action">
                        <i class="fas fa-user"></i> Đăng nhập
                    </a>
                    <a href="<?php echo BASE_PATH; ?>/register" class="user-action">
                        <i class="fas fa-user-plus"></i> Đăng ký
                    </a>
                <?php endif; ?>
            </div>

            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.header');
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            const userActions = document.querySelector('.user-actions');
            let lastScroll = 0;

            // Hide header on scroll down, show on scroll up
            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset;
                if (currentScroll <= 0) {
                    header.classList.remove('hide');
                    return;
                }

                if (currentScroll > lastScroll && !header.classList.contains('hide')) {
                    header.classList.add('hide');
                } else if (currentScroll < lastScroll && header.classList.contains('hide')) {
                    header.classList.remove('hide');
                }
                lastScroll = currentScroll;
            });

            // Mobile menu toggle
            mobileMenuBtn.addEventListener('click', function() {
                navLinks.classList.toggle('active');
                userActions.classList.toggle('active');
                this.querySelector('i').classList.toggle('fa-bars');
                this.querySelector('i').classList.toggle('fa-times');
            });

            // Dropdown menu functionality
            const userProfile = document.querySelector('.user-profile');
            if (userProfile) {
                userProfile.addEventListener('click', function(e) {
                    this.querySelector('.dropdown-menu').classList.toggle('active');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userProfile.contains(e.target)) {
                        userProfile.querySelector('.dropdown-menu').classList.remove('active');
                    }
                });
            }

            // Close mobile menu when clicking a link
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        navLinks.classList.remove('active');
                        userActions.classList.remove('active');
                        mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                        mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                    }
                });
            });
        });
    </script>
</body>
</html>
   