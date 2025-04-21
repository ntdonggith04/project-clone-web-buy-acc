<?php
// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Tắt secure cookie cho localhost

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'web_buy_acc');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('APP_NAME', 'Mua Bán Tài Khoản Game');
define('APP_URL', 'http://localhost/project-clone-web-buy-acc/src/public/');
define('APP_TIMEZONE', 'Asia/Ho_Chi_Minh');

// Upload configuration
define('UPLOAD_PATH', BASE_PATH . '/public/uploads');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

// Cấu hình cơ bản
define('BASE_URL', 'http://localhost/project-clone-web-buy-acc/src/public/');
define('SITE_NAME', 'Game Account Store');

// Cấu hình email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-password');

// Cấu hình thanh toán
define('BANK_NAME', 'Vietcombank');
define('BANK_ACCOUNT', '1234567890');
define('BANK_ACCOUNT_NAME', 'NGUYEN VAN A');
define('MOMO_PHONE', '0912345678');
define('ZALOPAY_PHONE', '0912345678');

// Cấu hình VNPay
define('VNPAY_TMN_CODE', 'YO0K43X7'); // Terminal ID - Mã website tại VNPAY
define('VNPAY_HASH_SECRET_KEY', 'KJHGFDSAQWERTYUIOPZXCVBNM'); // Secret key
define('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'); // URL thanh toán
define('VNPAY_RETURN_URL', BASE_URL . 'payment/vnpay-return'); // URL callback
define('VNPAY_SANDBOX', true); // Bật chế độ sandbox
?> 