<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Account Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/project-clone-web-buy-acc/src/project-root/public/css/header.css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="/project-clone-web-buy-acc/src/project-root/public" class="logo">
                <i class="fas fa-gamepad"></i> Game<span>Account</span>
            </a>
            
            <nav class="nav-links">
                <a href="/project-clone-web-buy-acc/src/project-root/public" class="nav-link">Trang chủ</a>
                <a href="/project-clone-web-buy-acc/src/project-root/public/accounts" class="nav-link">Tài khoản game</a>
                <a href="/project-clone-web-buy-acc/src/project-root/public/games" class="nav-link">Game phổ biến</a>
                <a href="/project-clone-web-buy-acc/src/project-root/public/support" class="nav-link">Hỗ trợ</a>
            </nav>

            <div class="user-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-profile">
                        <div class="avatar-container">
                            <img src="/project-clone-web-buy-acc/src/project-root/public/images/default-avatar.png" alt="Avatar" class="user-avatar">
                        </div>
                        <span class="welcome-text">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <div class="dropdown-menu">
                            <a href="/project-clone-web-buy-acc/src/project-root/public/profile" class="dropdown-item">
                                <i class="fas fa-user-circle"></i> Thông tin cá nhân
                            </a>
                            <a href="/project-clone-web-buy-acc/src/project-root/public/orders" class="dropdown-item">
                                <i class="fas fa-shopping-bag"></i> Đơn hàng
                            </a>
                            <a href="/project-clone-web-buy-acc/src/project-root/public/logout" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/project-clone-web-buy-acc/src/project-root/public/login" class="user-action">
                        <i class="fas fa-user"></i> Đăng nhập
                    </a>
                    <a href="/project-clone-web-buy-acc/src/project-root/public/register" class="user-action">
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
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
            document.querySelector('.user-actions').classList.toggle('active');
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
    </script>

    <style>
        .user-profile {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .avatar-container {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #3498db;
        }

        .user-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .welcome-text {
            font-size: 0.9em;
            color: #333;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 10px 0;
            min-width: 200px;
            display: none;
            z-index: 1000;
        }

        .user-profile:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .dropdown-item:hover {
            background-color: #f5f5f5;
        }

        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .welcome-text {
                display: none;
            }
            
            .avatar-container {
                width: 35px;
                height: 35px;
            }
        }
    </style>
</body>
</html>
   