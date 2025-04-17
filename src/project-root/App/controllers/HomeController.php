<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        // Lấy dữ liệu cần thiết từ model
        // Hiển thị view trang chủ
        require_once dirname(__DIR__) . '/views/home/index.php';
    }
} 