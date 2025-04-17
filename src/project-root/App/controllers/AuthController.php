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
            // Validation
            $errors = [];
            if (empty($_POST['username'])) {
                $errors['username'] = 'Vui lòng nhập tên người dùng';
            }
            if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Vui lòng nhập email hợp lệ';
            }
            if (empty($_POST['password']) || strlen($_POST['password']) < 6) {
                $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
            }

            if (empty($errors)) {
                try {
                    // Kiểm tra email đã tồn tại chưa
                    $existingUser = $this->userModel->getByEmail($_POST['email']);
                    if ($existingUser) {
                        $errors['email'] = 'Email này đã được sử dụng';
                    } else {
                        // Xử lý đăng ký
                        $data = [
                            'username' => $_POST['username'],
                            'email' => $_POST['email'],
                            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                            'role' => 'user'
                        ];
                        
                        if ($this->userModel->create($data)) {
                            $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                            header('Location: /project-clone-web-buy-acc/src/project-root/public/login');
                            exit;
                        } else {
                            $errors['general'] = 'Đăng ký thất bại. Vui lòng thử lại.';
                        }
                    }
                } catch (\Exception $e) {
                    error_log("Registration Error: " . $e->getMessage());
                    $errors['general'] = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
                }
            }
        }

        require_once BASE_PATH . '/App/views/auth/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];

                // Kiểm tra nếu là admin thì chuyển hướng đến trang admin
                if ($user['role'] === 'admin') {
                    header('Location: /project-clone-web-buy-acc/src/project-root/public/admin');
                    exit;
                }

                header('Location: /project-clone-web-buy-acc/src/project-root/public/accounts');
                exit;
            } else {
                $_SESSION['error'] = 'Email hoặc mật khẩu không đúng';
            }
        }

        require_once BASE_PATH . '/App/views/auth/login.php';
    }

    public function logout() {
        $_SESSION['success'] = 'Đăng xuất thành công!';
        session_destroy();
        header('Location: /project-clone-web-buy-acc/src/project-root/public/accounts');
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
                        'password' => password_hash(uniqid(), PASSWORD_DEFAULT) // Tạo mật khẩu ngẫu nhiên
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
} 