<?php
namespace App\Controllers;

class AdminController {
    private $userModel;
    private $accountModel;
    private $gameModel;

    public function __construct() {
        // Kiểm tra đăng nhập admin
        $this->checkAdminLogin();
        
        // Khởi tạo các model
        $this->userModel = new \App\Models\User();
        $this->accountModel = new \App\Models\Account();
        $this->gameModel = new \App\Models\Game();
    }

    private function checkAdminLogin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /project-clone-web-buy-acc/src/project-root/public/login');
            exit;
        }
    }

    public function index() {
        // Lấy thống kê tổng quan
        $stats = [
            'total_users' => $this->userModel->getTotalUsers(),
            'total_accounts' => $this->accountModel->getTotalAccounts(),
            'total_games' => $this->gameModel->getTotalGames(),
            'total_sales' => $this->accountModel->getTotalSales()
        ];

        require_once BASE_PATH . '/App/views/admin/dashboard.php';
    }

    public function users() {
        $users = $this->userModel->getAllUsers();
        require_once BASE_PATH . '/App/views/admin/users.php';
    }

    public function accounts() {
        $accounts = $this->accountModel->getAllAccounts();
        require_once BASE_PATH . '/App/views/admin/accounts.php';
    }

    public function games() {
        $games = $this->gameModel->getAllGames();
        require_once BASE_PATH . '/App/views/admin/games.php';
    }

    public function deleteUser($id) {
        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = 'Xóa người dùng thành công';
        } else {
            $_SESSION['error'] = 'Xóa người dùng thất bại';
        }
        header('Location: /project-clone-web-buy-acc/src/project-root/public/admin/users');
        exit;
    }

    public function deleteAccount($id) {
        if ($this->accountModel->deleteAccount($id)) {
            $_SESSION['success'] = 'Xóa tài khoản thành công';
        } else {
            $_SESSION['error'] = 'Xóa tài khoản thất bại';
        }
        header('Location: /project-clone-web-buy-acc/src/project-root/public/admin/accounts');
        exit;
    }

    public function deleteGame($id) {
        if ($this->gameModel->deleteGame($id)) {
            $_SESSION['success'] = 'Xóa game thành công';
        } else {
            $_SESSION['error'] = 'Xóa game thất bại';
        }
        header('Location: /project-clone-web-buy-acc/src/project-root/public/admin/games');
        exit;
    }
} 