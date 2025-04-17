<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/project-clone-web-buy-acc/src/project-root/public/css/style.css">
</head>
<body>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3 class="footer-title">Về chúng tôi</h3>
                <p class="footer-description">
                    Game Account Store là nền tảng mua bán tài khoản game uy tín, 
                    cung cấp các tài khoản game chất lượng với giá cả hợp lý.
                </p>
            </div>

            <div class="footer-section">
                <h3 class="footer-title">Liên kết nhanh</h3>
                <ul class="footer-links">
                    <li><a href="/project-clone-web-buy-acc/src/project-root/public">Trang chủ</a></li>
                    <li><a href="/project-clone-web-buy-acc/src/project-root/public/accounts">Tài khoản game</a></li>
                    <li><a href="/project-clone-web-buy-acc/src/project-root/public/games">Game phổ biến</a></li>
                    <li><a href="/project-clone-web-buy-acc/src/project-root/public/support">Hỗ trợ</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3 class="footer-title">Liên hệ</h3>
                <ul class="footer-contact">
                    <li><i class="fas fa-envelope"></i> support@gameaccountstore.com</li>
                    <li><i class="fas fa-phone"></i> 0123 456 789</li>
                    <li><i class="fas fa-map-marker-alt"></i> Hà Nội, Việt Nam</li>
                </ul>
            </div>

            <div class="footer-section">
                <h3 class="footer-title">Theo dõi chúng tôi</h3>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Game Account Store. All rights reserved.</p>
        </div>
    </footer>

    <style>
    .footer {
        background-color: #2c3e50;
        color: #ecf0f1;
        padding: 40px 0 20px;
        margin-top: 50px;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .footer-section {
        margin-bottom: 20px;
        text-align: left;
    }

    .footer-title {
        color: #3498db;
        font-size: 1.2em;
        margin-bottom: 15px;
        position: relative;
        padding-bottom: 10px;
        display: inline-block;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 2px;
        background-color: #3498db;
        width: 100%;
    }

    .footer-description {
        line-height: 1.6;
        color: #bdc3c7;
        text-align: left;
        margin-bottom: 15px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        text-align: left;
    }

    .footer-links li {
        margin-bottom: 8px;
    }

    .footer-links a {
        color: #bdc3c7;
        text-decoration: none;
        transition: color 0.3s ease;
        display: inline-block;
        padding: 2px 0;
    }

    .footer-links a:hover {
        color: #3498db;
    }

    .footer-contact {
        list-style: none;
        padding: 0;
        text-align: left;
    }

    .footer-contact li {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #bdc3c7;
    }

    .footer-contact i {
        color: #3498db;
        width: 20px;
        text-align: center;
    }

    .social-links {
        display: flex;
        gap: 15px;
    }

    .social-link {
        color: #bdc3c7;
        font-size: 1.5em;
        transition: color 0.3s ease;
    }

    .social-link:hover {
        color: #3498db;
    }

    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        margin-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.1);
        color: #bdc3c7;
        font-size: 0.9em;
    }

    @media (max-width: 768px) {
        .footer-container {
            grid-template-columns: 1fr;
        }

        .footer-section {
            text-align: center;
        }

        .footer-title {
            display: inline-block;
        }

        .footer-description {
            text-align: center;
        }

        .footer-links {
            text-align: center;
        }

        .footer-contact {
            text-align: center;
        }

        .footer-contact li {
            justify-content: center;
        }
    }
    </style>
</body>
</html>    