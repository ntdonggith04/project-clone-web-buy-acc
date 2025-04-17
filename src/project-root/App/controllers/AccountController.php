<?php
namespace App\Controllers;

use App\Models\Account;

class AccountController {
    private $accountModel;

    public function __construct() {
        $this->accountModel = new Account();
    }

    public function index() {
        try {
            $accounts = $this->accountModel->getAll();
            
            // Lưu thông báo thành công nếu có
            $success_message = isset($_SESSION['success']) ? $_SESSION['success'] : null;
            unset($_SESSION['success']); // Xóa thông báo sau khi đã lưu
            
            // Kiểm tra và lưu thông báo nếu không có tài khoản
            if (empty($accounts)) {
                $no_accounts_message = 'Hiện chưa có tài khoản nào được đăng bán.';
            }
            
            require_once BASE_PATH . '/App/views/accounts/index.php';
        } catch (\Exception $e) {
            error_log('AccountController::index - Error: ' . $e->getMessage());
            $_SESSION['error'] = 'Không thể tải danh sách tài khoản. Vui lòng thử lại sau.';
            header('Location: /project-clone-web-buy-acc/src/project-root/public');
            exit;
        }
    }

    public function show($id) {
        try {
            $account = $this->accountModel->getById($id);
            if (!$account) {
                $_SESSION['error'] = 'Account not found';
                header('Location: /accounts');
                exit;
            }
            require_once 'App/views/accounts/show.php';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to load account details. Please try again.';
            header('Location: /accounts');
            exit;
        }
    }

    public function sell() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to sell accounts';
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            if (empty($_POST['title'])) {
                $errors['title'] = 'Title is required';
            }
            if (empty($_POST['description'])) {
                $errors['description'] = 'Description is required';
            }
            if (empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
                $errors['price'] = 'Valid price is required';
            }

            if (empty($errors)) {
                try {
                    $data = [
                        'title' => $_POST['title'],
                        'description' => $_POST['description'],
                        'price' => $_POST['price'],
                        'seller_id' => $_SESSION['user_id']
                    ];

                    if ($this->accountModel->create($data)) {
                        $_SESSION['success'] = 'Account listed successfully!';
                        header('Location: /accounts');
                        exit;
                    }
                } catch (\Exception $e) {
                    $errors['general'] = 'Failed to list account. Please try again.';
                }
            }
        }
        require_once 'App/views/accounts/sell.php';
    }
} 