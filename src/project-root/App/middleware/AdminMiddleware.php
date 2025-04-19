<?php
namespace App\Middleware;

class AdminMiddleware {
    public static function checkAdminAccess() {
        // Bắt đầu session nếu chưa được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra xem người dùng đã đăng nhập và có quyền admin không
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này!';
            header('Location: /project-clone-web-buy-acc/src/project-root/public/login');
            exit;
        }
    }
} 