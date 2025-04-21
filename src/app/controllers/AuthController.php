<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/User.php';

use App\Models\User;

class AuthController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    // Hiển thị form đăng nhập
    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->password = $_POST['password'];

            if($this->user->login()) {
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['role'] = $this->user->role;

                // Kiểm tra nếu là admin thì chuyển đến trang quản trị
                if ($_SESSION['role'] === 'admin') {
                    header('Location: ' . BASE_URL . 'admin');
                } else {
                    // Nếu là người dùng thường thì chuyển đến trang chủ
                    header('Location: ' . BASE_URL);
                }
                exit;
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng";
                require_once __DIR__ . '/../views/auth/login.php';
            }
        } else {
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }

    // Hiển thị form đăng ký
    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->password = $_POST['password'];
            $this->user->email = $_POST['email'];
            $this->user->full_name = $_POST['full_name'];
            $this->user->phone = $_POST['phone'];
            $this->user->address = $_POST['address'];

            // Kiểm tra username đã tồn tại
            if($this->user->usernameExists()) {
                $error = "Tên đăng nhập đã tồn tại";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            // Kiểm tra email đã tồn tại
            if($this->user->emailExists()) {
                $error = "Email đã tồn tại";
                require_once __DIR__ . '/../views/auth/register.php';
                return;
            }

            // Tạo tài khoản mới
            if($this->user->register()) {
                $_SESSION['success'] = "Đăng ký thành công. Vui lòng đăng nhập.";
                header('Location: ' . BASE_URL . 'login');
                exit;
            } else {
                $error = "Đăng ký thất bại. Vui lòng thử lại.";
                require_once __DIR__ . '/../views/auth/register.php';
            }
        } else {
            require_once __DIR__ . '/../views/auth/register.php';
        }
    }

    // Đăng xuất
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . 'login');
        exit;
    }
}
?> 