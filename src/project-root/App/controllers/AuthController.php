<?php
namespace App\Controllers;

use App\Models\User;
use Google\Client;
use Google\Service\Oauth2;

class AuthController {
    private $userModel;
    private $client;

    public function __construct() {
        $this->userModel = new User();
        $this->client = new Client();
        $this->client->setClientId('33116669455-cg2psat2a747n6ift3h74pjk6j0tsscq.apps.googleusercontent.com');
        $this->client->setClientSecret('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');
        $this->client->setRedirectUri('http://localhost/project-clone-web-buy-acc/src/project-root/public/auth/google/callback');
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validate input
            if (empty($username) || empty($email) || empty($password)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: /project-clone-web-buy-acc/src/project-root/public/register');
                exit;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
                header('Location: /project-clone-web-buy-acc/src/project-root/public/register');
                exit;
            }

            // Check if email already exists
            if ($this->userModel->getByEmail($email)) {
                $_SESSION['error'] = 'Email đã tồn tại trong hệ thống';
                header('Location: /project-clone-web-buy-acc/src/project-root/public/register');
                exit;
            }

            // Create user
            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'user'
            ];

            if ($this->userModel->create($userData)) {
                $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                header('Location: /project-clone-web-buy-acc/src/project-root/public/login');
                exit;
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: /project-clone-web-buy-acc/src/project-root/public/register');
                exit;
            }
        }

        // Display register form
        require_once ROOT_PATH . '/App/views/auth/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->getByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /project-clone-web-buy-acc/src/project-root/public/admin');
                } else {
                    header('Location: /project-clone-web-buy-acc/src/project-root/public/');
                }
                exit;
            } else {
                $_SESSION['error'] = 'Email hoặc mật khẩu không đúng';
                header('Location: /project-clone-web-buy-acc/src/project-root/public/login');
                exit;
            }
        }

        // Display login form
        require_once ROOT_PATH . '/App/views/auth/login.php';
    }

    public function logout() {
        // Xóa tất cả các biến session
        session_start();
        session_unset();
        session_destroy();
        
        // Chuyển hướng về trang login
        header('Location: /project-clone-web-buy-acc/src/project-root/public/');
        exit;
    }

    public function google() {
        try {
            $authUrl = $this->client->createAuthUrl();
            header('Location: ' . $authUrl);
            exit;
        } catch (\Exception $e) {
            error_log("Google Auth Error: " . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra khi kết nối với Google. Vui lòng thử lại sau.';
            header('Location: /project-clone-web-buy-acc/src/project-root/public/login');
            exit;
        }
    }

    public function googleCallback() {
        try {
            if (isset($_GET['code'])) {
                $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
                $this->client->setAccessToken($token);

                $oauth2 = new Oauth2($this->client);
                $userInfo = $oauth2->userinfo->get();

                $email = $userInfo->email;
                $name = $userInfo->name;

                // Kiểm tra xem email đã tồn tại chưa
                $user = $this->userModel->getByEmail($email);
                
                if (!$user) {
                    // Tạo tài khoản mới
                    $data = [
                        'username' => $name,
                        'email' => $email,
                        'password' => password_hash(uniqid(), PASSWORD_DEFAULT), // Tạo mật khẩu ngẫu nhiên
                        'role' => 'user' // Thêm role mặc định là user
                    ];
                    
                    if ($this->userModel->create($data)) {
                        $user = $this->userModel->getByEmail($email);
                    } else {
                        throw new \Exception("Không thể tạo tài khoản mới");
                    }
                }

                // Đăng nhập người dùng
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['success'] = 'Đăng nhập bằng Google thành công!';

                header('Location: /project-clone-web-buy-acc/src/project-root/public/');
                exit;
            } else {
                throw new \Exception("Không nhận được mã xác thực từ Google");
            }
        } catch (\Exception $e) {
            error_log("Google Callback Error: " . $e->getMessage());
            $_SESSION['error'] = 'Có lỗi xảy ra khi xác thực với Google. Vui lòng thử lại sau.';
            header('Location: /project-clone-web-buy-acc/src/project-root/public/login');
            exit;
        }
    }

    public function forgotPassword() {
        // Hiển thị form quên mật khẩu
        require_once ROOT_PATH . '/App/views/auth/forgot-password.php';
    }

    public function sendResetLink() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            if (empty($email)) {
                $_SESSION['error'] = 'Vui lòng nhập địa chỉ email';
                header('Location: ' . BASE_PATH . '/forgot-password');
                exit;
            }

            // Kiểm tra email có tồn tại trong database
            $user = new User();
            $userData = $user->findByEmail($email);

            if (!$userData) {
                $_SESSION['error'] = 'Email này chưa được đăng ký trong hệ thống';
                header('Location: ' . BASE_PATH . '/forgot-password');
                exit;
            }

            // Tạo token đặt lại mật khẩu
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Lưu token vào database
            $user->saveResetToken($email, $token, $expiry);

            // Tạo link đặt lại mật khẩu
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'];
            $resetLink = $protocol . $host . BASE_PATH . '/reset-password/' . $token;

            // Gửi email (tạm thời chỉ hiển thị thông báo)
            $_SESSION['success'] = 'Link đặt lại mật khẩu đã được gửi vào email của bạn. Link: ' . $resetLink;
            
            // TODO: Implement email sending here
            // Có thể sử dụng PHPMailer hoặc mail() function
            
            header('Location: ' . BASE_PATH . '/forgot-password');
            exit;
        }
    }

    public function resetPassword($token) {
        // Kiểm tra token có hợp lệ không
        $user = new User();
        $tokenData = $user->verifyResetToken($token);

        if (!$tokenData) {
            $_SESSION['error'] = 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn';
            header('Location: ' . BASE_PATH . '/forgot-password');
            exit;
        }

        // Hiển thị form đặt lại mật khẩu
        require_once ROOT_PATH . '/App/views/auth/reset-password.php';
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validate input
            if (empty($password) || empty($confirmPassword)) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
                header('Location: ' . BASE_PATH . '/reset-password/' . $token);
                exit;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
                header('Location: ' . BASE_PATH . '/reset-password/' . $token);
                exit;
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                header('Location: ' . BASE_PATH . '/reset-password/' . $token);
                exit;
            }

            // Cập nhật mật khẩu
            $user = new User();
            $success = $user->updatePasswordByToken($token, password_hash($password, PASSWORD_DEFAULT));

            if ($success) {
                $_SESSION['success'] = 'Mật khẩu đã được cập nhật thành công';
                header('Location: ' . BASE_PATH . '/login');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: ' . BASE_PATH . '/reset-password/' . $token);
            }
            exit;
        }
    }
} 