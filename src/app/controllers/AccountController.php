<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/GameAccount.php';
use App\Models\GameAccount;
use App\Core\View;

class AccountController {
    private $gameAccount;
    private $view;

    public function __construct() {
        $this->gameAccount = new GameAccount();
        $this->view = new View();
    }

    // Hiển thị danh sách tài khoản game
    public function index() {
        // Lấy danh sách game cho bộ lọc
        $gameModel = new \App\Models\Game();
        $games = $gameModel->getAll();
        
        // Xây dựng query cơ bản
        $sql = "SELECT ga.*, g.name as game_name, 
                IFNULL((SELECT filename FROM account_screenshots WHERE account_id = ga.id LIMIT 1), g.image) as game_image
                FROM game_accounts ga
                JOIN games g ON ga.game_id = g.id 
                WHERE ga.status = 'available'";
        
        $params = [];
        
        // Áp dụng bộ lọc nếu có
        // Lọc theo game
        if(isset($_GET['game']) && !empty($_GET['game'])) {
            $sql .= " AND ga.game_id = :game_id";
            $params[':game_id'] = $_GET['game'];
        }
        
        // Lọc theo giá tối thiểu
        if(isset($_GET['price_min']) && is_numeric($_GET['price_min']) && $_GET['price_min'] > 0) {
            $sql .= " AND ga.price >= :price_min";
            $params[':price_min'] = $_GET['price_min'];
        }
        
        // Lọc theo giá tối đa
        if(isset($_GET['price_max']) && is_numeric($_GET['price_max']) && $_GET['price_max'] > 0) {
            $sql .= " AND ga.price <= :price_max";
            $params[':price_max'] = $_GET['price_max'];
        }
        
        // Sắp xếp kết quả
        // Nếu có lọc theo giá, sắp xếp theo giá từ thấp đến cao
        if(isset($_GET['price_min']) || isset($_GET['price_max'])) {
            $sql .= " ORDER BY ga.price ASC";
        } else {
            // Mặc định sắp xếp theo thời gian tạo mới nhất
            $sql .= " ORDER BY ga.created_at DESC";
        }
        
        // Thực hiện truy vấn
        $accounts = $this->gameAccount->query($sql, $params);
        
        // Xử lý phân trang
        $total_accounts = count($accounts);
        $accounts_per_page = 12;
        $total_pages = ceil($total_accounts / $accounts_per_page);
        $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($current_page - 1) * $accounts_per_page;
        
        // Build query string cho phân trang
        $query_string = '';
        if (isset($_GET['game'])) {
            $query_string .= '&game=' . $_GET['game'];
        }
        if (isset($_GET['price_min'])) {
            $query_string .= '&price_min=' . $_GET['price_min'];
        }
        if (isset($_GET['price_max'])) {
            $query_string .= '&price_max=' . $_GET['price_max'];
        }
        
        $this->view->render('accounts/index', [
            'accounts' => array_slice($accounts, $offset, $accounts_per_page),
            'games' => $games,
            'total_pages' => $total_pages,
            'current_page' => $current_page,
            'query_string' => $query_string,
            'title' => 'Tài khoản game'
        ]);
    }

    // Hiển thị chi tiết tài khoản game
    public function show($id) {
        // Lấy thông tin tài khoản kèm thông tin game và slug của game
        $sql = "SELECT ga.*, g.name as game_name, g.slug as game_slug,
                IFNULL((SELECT filename FROM account_screenshots WHERE account_id = ga.id LIMIT 1), g.image) as game_image 
                FROM game_accounts ga
                JOIN games g ON ga.game_id = g.id 
                WHERE ga.id = :id";
        
        $params = [':id' => $id];
        $accounts = $this->gameAccount->query($sql, $params);
        
        if (empty($accounts)) {
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
        
        $account = $accounts[0];
        
        // Lấy tất cả ảnh chụp màn hình
        $screenshots = $this->gameAccount->getAccountScreenshots($id);
        
        $this->view->render('accounts/show', [
            'account' => $account,
            'screenshots' => $screenshots,
            'game_slug' => $account['game_slug'] ?? '',
            'title' => $account['title'] ?? 'Chi tiết tài khoản'
        ]);
    }

    // Tìm kiếm tài khoản game
    public function search() {
        if(isset($_GET['keywords'])) {
            $keywords = $_GET['keywords'];
            $accounts = $this->gameAccount->searchAccounts($keywords);
            $this->view->render('accounts/index', [
                'accounts' => $accounts,
                'keywords' => $keywords,
                'title' => 'Kết quả tìm kiếm: ' . $keywords
            ]);
        } else {
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
    }

    // Lọc tài khoản game theo game
    public function game($gameId) {
        $accounts = $this->gameAccount->getAccountsByGame($gameId);
        // Lấy thông tin game từ model Game nếu cần
        // $game = (new Game())->find($gameId);
        $this->view->render('accounts/index', [
            'accounts' => $accounts,
            'title' => 'Tài khoản game'
        ]);
    }

    // Thêm tài khoản game mới (Admin)
    public function create() {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'game_id' => $_POST['game_id'] ?? null,
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'status' => 'available',
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'additional_info' => $_POST['additional_info'] ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ];

            if($this->gameAccount->create($data)) {
                $_SESSION['success'] = "Thêm tài khoản game thành công";
                header('Location: ' . BASE_URL . 'admin/accounts');
                exit;
            } else {
                $error = "Thêm tài khoản game thất bại";
                $this->view->render('admin/accounts/create', [
                    'error' => $error,
                    'title' => 'Thêm tài khoản game'
                ]);
            }
        } else {
            $this->view->render('admin/accounts/create', [
                'title' => 'Thêm tài khoản game'
            ]);
        }
    }

    // Cập nhật tài khoản game (Admin)
    public function update($id) {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $account = $this->gameAccount->find($id);
        if (!$account) {
            header('Location: ' . BASE_URL . 'admin/accounts');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'game_id' => $_POST['game_id'] ?? $account['game_id'],
                'title' => $_POST['title'] ?? $account['title'],
                'description' => $_POST['description'] ?? $account['description'],
                'price' => $_POST['price'] ?? $account['price'],
                'status' => $_POST['status'] ?? $account['status'],
                'username' => $_POST['username'] ?? $account['username'],
                'password' => $_POST['password'] ?? $account['password'],
                'additional_info' => $_POST['additional_info'] ?? $account['additional_info'],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if($this->gameAccount->update($id, $data)) {
                $_SESSION['success'] = "Cập nhật tài khoản game thành công";
                header('Location: ' . BASE_URL . 'admin/accounts');
                exit;
            } else {
                $error = "Cập nhật tài khoản game thất bại";
                $this->view->render('admin/accounts/update', [
                    'account' => $account,
                    'error' => $error,
                    'title' => 'Cập nhật tài khoản game'
                ]);
            }
        } else {
            $this->view->render('admin/accounts/update', [
                'account' => $account,
                'title' => 'Cập nhật tài khoản game'
            ]);
        }
    }

    // Xóa tài khoản game (Admin)
    public function delete($id) {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        if($this->gameAccount->delete($id)) {
            $_SESSION['success'] = "Xóa tài khoản game thành công";
        } else {
            $_SESSION['error'] = "Xóa tài khoản game thất bại";
        }

        header('Location: ' . BASE_URL . 'admin/accounts');
        exit;
    }
}
?> 