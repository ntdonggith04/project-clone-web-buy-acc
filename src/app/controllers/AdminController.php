<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/GameAccount.php';
require_once __DIR__ . '/../models/Order.php';

use App\Models\User;
use App\Models\GameAccount;
use App\Models\Order;
use PDO;

class AdminController {
    private $user;
    private $gameAccount;
    private $order;

    public function __construct() {
        $this->user = new User();
        $this->gameAccount = new GameAccount();
        $this->order = new Order();
    }

    // Kiểm tra quyền admin
    private function checkAdmin() {
        if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    // Trang chủ admin
    public function index() {
        $this->checkAdmin();

        // Thống kê cơ bản
        $total_users = $this->user->count();
        $total_accounts = $this->gameAccount->count();
        $total_orders = $this->order->count();
        $total_revenue = $this->order->getTotalRevenue();
        
        // Dữ liệu cho biểu đồ doanh thu theo tháng
        $monthly_revenue = $this->order->getMonthlyRevenue();
        
        // Dữ liệu cho biểu đồ trạng thái đơn hàng
        $order_stats = $this->order->getOrderStatusStats();
        
        // Dữ liệu cho biểu đồ phân bố tài khoản game
        $gameModel = new \App\Models\Game();
        $game_account_stats = $this->gameAccount->getAccountDistributionByGame();
        
        // Dữ liệu cho biểu đồ phân bố người dùng theo vai trò
        $user_stats = [
            'admin' => $this->user->countByRole('admin'),
            'user' => $this->user->countByRole('user')
        ];
        
        // Dữ liệu cho biểu đồ người dùng đăng ký mới theo tháng
        $monthly_new_users = $this->user->getMonthlyNewUsers();
        
        require_once __DIR__ . '/../views/admin/index.php';
    }

    // Quản lý tài khoản game
    public function accounts() {
        $this->checkAdmin();

        // Xử lý lọc theo danh mục game
        $game_id = isset($_GET['game_id']) ? (int)$_GET['game_id'] : 0;
        
        // Xử lý lọc theo khoảng giá
        $price_from = isset($_GET['price_from']) && !empty($_GET['price_from']) ? (float)$_GET['price_from'] : 0;
        $price_to = isset($_GET['price_to']) && !empty($_GET['price_to']) ? (float)$_GET['price_to'] : 0;
        
        // Xử lý lọc theo ngày đăng bán
        $date_from = isset($_GET['date_from']) && !empty($_GET['date_from']) ? $_GET['date_from'] : '';
        $date_to = isset($_GET['date_to']) && !empty($_GET['date_to']) ? $_GET['date_to'] : '';
        
        // Xử lý phân trang
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Số bản ghi trên mỗi trang
        $offset = ($current_page - 1) * $limit;
        
        // Lấy danh sách tài khoản game
        if ($game_id > 0 || !empty($date_from) || !empty($date_to) || $price_from > 0 || $price_to > 0) {
            // Nếu có lọc theo danh mục, ngày hoặc giá
            $conditions = [];
            $params = [];
            
            if ($game_id > 0) {
                $conditions[] = "ga.game_id = :game_id";
                $params[':game_id'] = $game_id;
            }
            
            if (!empty($date_from)) {
                $conditions[] = "DATE(ga.created_at) >= :date_from";
                $params[':date_from'] = $date_from;
            }
            
            if (!empty($date_to)) {
                $conditions[] = "DATE(ga.created_at) <= :date_to";
                $params[':date_to'] = $date_to;
            }
            
            if ($price_from > 0) {
                $conditions[] = "ga.price >= :price_from";
                $params[':price_from'] = $price_from;
            }
            
            if ($price_to > 0) {
                $conditions[] = "ga.price <= :price_to";
                $params[':price_to'] = $price_to;
            }
            
            $where = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";
            
            $sql = "SELECT COUNT(*) as total FROM game_accounts ga 
                    JOIN games g ON ga.game_id = g.id" . $where;
            
            $total_records = $this->gameAccount->countWithConditions($sql, $params);
            
            $sql = "SELECT ga.*, g.name as game_name FROM game_accounts ga 
                    JOIN games g ON ga.game_id = g.id" . $where . " 
                    ORDER BY ga.id DESC LIMIT :limit OFFSET :offset";
            
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;
            
            $accounts = $this->gameAccount->queryWithParams($sql, $params);
        } else {
            // Nếu không có lọc
            $total_records = $this->gameAccount->count();
            $accounts = $this->gameAccount->readAllPaginate($limit, $offset);
        }
        
        // Tính tổng số trang
        $total_pages = ceil($total_records / $limit);
        
        // Lấy danh sách game để filter
        $gameModel = new \App\Models\Game();
        $games = $gameModel->getAll();
        
        require_once __DIR__ . '/../views/admin/accounts/index.php';
    }

    // Quản lý đơn hàng
    public function orders() {
        $this->checkAdmin();

        $stmt = $this->order->readAll();
        require_once __DIR__ . '/../views/admin/orders/index.php';
    }

    // Chi tiết đơn hàng
    public function orderDetail($id) {
        $this->checkAdmin();

        $this->order->id = $id;
        $result = $this->order->readOne();
        
        if (!$result) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }
        
        $items = $this->order->readItems();
        $order = $this->order; // Gán biến order để sử dụng trong view
        
        require_once __DIR__ . '/../views/admin/orders/show.php';
    }

    // Quản lý giao dịch
    public function transactions() {
        $this->checkAdmin();
        
        // Khởi tạo model Transaction
        $transactionModel = new \App\Models\Transaction();
        
        // Xử lý các tham số lọc và tìm kiếm
        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Xử lý phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Số giao dịch trên mỗi trang
        $offset = ($page - 1) * $limit;
        
        // Xây dựng câu truy vấn với bộ lọc
        $conditions = [];
        $params = [];
        
        if (!empty($type)) {
            $conditions[] = "t.type = :type";
            $params[':type'] = $type;
        }
        
        if (!empty($status)) {
            $conditions[] = "t.status = :status";
            $params[':status'] = $status;
        }
        
        if (!empty($search)) {
            $conditions[] = "t.id = :search";
            $params[':search'] = $search;
        }
        
        // Tạo WHERE clause nếu có điều kiện
        $where = '';
        if (!empty($conditions)) {
            $where = " WHERE " . implode(" AND ", $conditions);
        }
        
        // Đếm tổng số giao dịch để phân trang
        $countSql = "SELECT COUNT(*) as total FROM transactions t" . $where;
        $total = $transactionModel->countWithConditions($countSql, $params);
        $totalPages = ceil($total / $limit);
        
        // Lấy danh sách giao dịch
        $sql = "SELECT t.*, u.name as user_name, u.email as user_email, u.avatar as user_avatar 
                FROM transactions t
                JOIN users u ON t.user_id = u.id" . $where . "
                ORDER BY t.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;
        
        $transactions = $transactionModel->queryWithParams($sql, $params);
        
        // Lấy thống kê giao dịch
        $stats = $transactionModel->getTransactionStats();
        
        // Thêm tính toán cho tổng giá trị mua bán
        $stats['total_sales'] = $stats['total_deposit_amount'] - $stats['total_withdrawal_amount'];
        
        require_once __DIR__ . '/../views/admin/transactions.php';
    }
    
    // Xem chi tiết giao dịch
    public function transactionDetail($id) {
        $this->checkAdmin();
        
        // Khởi tạo model Transaction
        $transactionModel = new \App\Models\Transaction();
        
        // Lấy thông tin giao dịch
        $transaction = $transactionModel->getTransactionById($id);
        
        if (!$transaction) {
            $_SESSION['error'] = "Không tìm thấy giao dịch";
            header('Location: ' . BASE_URL . 'admin/transactions');
            exit;
        }
        
        require_once __DIR__ . '/../views/admin/transactions/show.php';
    }
    
    // Cập nhật trạng thái giao dịch
    public function updateTransactionStatus($id) {
        $this->checkAdmin();
        
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        if (empty($status) || !in_array($status, ['pending', 'completed', 'cancelled'])) {
            $_SESSION['error'] = "Trạng thái không hợp lệ";
            header('Location: ' . BASE_URL . 'admin/transactions');
            exit;
        }
        
        // Khởi tạo model Transaction
        $transactionModel = new \App\Models\Transaction();
        
        if ($transactionModel->updateStatus($id, $status)) {
            $_SESSION['success'] = "Cập nhật trạng thái giao dịch thành công";
        } else {
            $_SESSION['error'] = "Cập nhật trạng thái giao dịch thất bại";
        }
        
        // Quay lại trang chi tiết giao dịch
        header('Location: ' . BASE_URL . 'admin/transactions/' . $id);
        exit;
    }

    // Quản lý người dùng
    public function users() {
        $this->checkAdmin();

        // Xử lý tìm kiếm và bộ lọc
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $role = isset($_GET['role']) ? trim($_GET['role']) : '';
        
        // Xử lý phân trang
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Số bản ghi trên mỗi trang
        $offset = ($current_page - 1) * $limit;
        
        // Xây dựng điều kiện tìm kiếm
        $conditions = [];
        $params = [];
        
        if (!empty($search)) {
            $conditions[] = "(username LIKE :search OR email LIKE :search OR fullname LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        if (!empty($role)) {
            $conditions[] = "role = :role";
            $params[':role'] = $role;
        }
        
        // Tạo câu điều kiện WHERE nếu có điều kiện
        $where = '';
        if (!empty($conditions)) {
            $where = " WHERE " . implode(" AND ", $conditions);
        }
        
        // Lấy tổng số người dùng để tính số trang
        $total_records = $this->user->countWithConditions($where, $params);
        $users = $this->user->getWithConditions($where, $params, $limit, $offset);
        
        // Tính tổng số trang
        $total_pages = ceil($total_records / $limit);
        
        // Thống kê
        $stats = [
            'total_users' => $this->user->count(),
            'user_users' => $this->user->countByRole('user'),
            'admin_users' => $this->user->countByRole('admin')
        ];
        
        require_once __DIR__ . '/../views/admin/users/index.php';
    }

    // Quản lý danh mục game
    public function games() {
        $this->checkAdmin();
        
        // Khởi tạo model Game
        $gameModel = new \App\Models\Game();
        
        // Xử lý tìm kiếm
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Lấy danh sách game
        if (!empty($search)) {
            $sql = "SELECT g.*, COUNT(ga.id) as account_count 
                    FROM games g 
                    LEFT JOIN game_accounts ga ON g.id = ga.game_id 
                    WHERE g.name LIKE :search 
                    GROUP BY g.id
                    ORDER BY g.id DESC";
            $games = $gameModel->query($sql, [':search' => "%$search%"]);
        } else {
            $games = $gameModel->getGamesWithAccounts();
        }
        
        // Thống kê
        $total_games = $gameModel->count();
        $total_accounts = $gameModel->countTotalAccounts();
        
        require_once __DIR__ . '/../views/admin/games/index.php';
    }

    // Tạo danh mục game mới
    public function createGame() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate dữ liệu
            $errors = [];
            
            if (empty($_POST['name'])) {
                $errors['name'] = "Vui lòng nhập tên danh mục";
            }
            
            // Xử lý upload hình ảnh
            $imageName = '';
            if (!empty($_FILES['image']['name'])) {
                $uploadDir = 'uploads/games/';
                
                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $imageName = uniqid() . '_' . $_FILES['image']['name'];
                $uploadFile = $uploadDir . $imageName;
                
                // Kiểm tra định dạng file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                    $errors['image'] = "Chỉ chấp nhận file ảnh (JPG, PNG)";
                } else if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $errors['image'] = "Không thể tải lên hình ảnh";
                    $imageName = '';
                }
            }
            
            // Nếu có lỗi, hiển thị form với thông báo lỗi
            if (!empty($errors)) {
                require_once __DIR__ . '/../views/admin/games/create.php';
                return;
            }
            
            // Chuẩn bị dữ liệu để lưu
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? '',
                'image' => $imageName,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Khởi tạo model Game
            $gameModel = new \App\Models\Game();
            
            // Lưu vào database
            if ($gameModel->create($data)) {
                $_SESSION['success'] = "Thêm danh mục game thành công";
                header('Location: ' . BASE_URL . 'admin/games');
                exit;
            } else {
                $_SESSION['error'] = "Thêm danh mục game thất bại";
                require_once __DIR__ . '/../views/admin/games/create.php';
                return;
            }
        }
        
        require_once __DIR__ . '/../views/admin/games/create.php';
    }
    
    // Hiển thị form chỉnh sửa danh mục game
    public function editGame($id) {
        $this->checkAdmin();
        
        // Khởi tạo model Game
        $gameModel = new \App\Models\Game();
        
        // Lấy thông tin game
        $game = $gameModel->find($id);
        
        if (!$game) {
            $_SESSION['error'] = "Không tìm thấy danh mục game";
            header('Location: ' . BASE_URL . 'admin/games');
            exit;
        }
        
        require_once __DIR__ . '/../views/admin/games/edit.php';
    }
    
    // Cập nhật danh mục game
    public function updateGame($id) {
        $this->checkAdmin();
        
        // Khởi tạo model Game
        $gameModel = new \App\Models\Game();
        
        // Lấy thông tin game
        $game = $gameModel->find($id);
        
        if (!$game) {
            $_SESSION['error'] = "Không tìm thấy danh mục game";
            header('Location: ' . BASE_URL . 'admin/games');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate dữ liệu
            $errors = [];
            
            if (empty($_POST['name'])) {
                $errors['name'] = "Vui lòng nhập tên danh mục";
            }
            
            // Xử lý upload hình ảnh mới (nếu có)
            $imageName = $game['image'];
            if (!empty($_FILES['image']['name'])) {
                $uploadDir = 'uploads/games/';
                
                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $imageName = uniqid() . '_' . $_FILES['image']['name'];
                $uploadFile = $uploadDir . $imageName;
                
                // Kiểm tra định dạng file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                    $errors['image'] = "Chỉ chấp nhận file ảnh (JPG, PNG)";
                } else if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $errors['image'] = "Không thể tải lên hình ảnh";
                    $imageName = $game['image']; // Giữ nguyên ảnh cũ nếu không upload được
                } else {
                    // Xóa ảnh cũ nếu tồn tại
                    if (!empty($game['image']) && file_exists($uploadDir . $game['image'])) {
                        unlink($uploadDir . $game['image']);
                    }
                }
            }
            
            // Nếu có lỗi, hiển thị form với thông báo lỗi
            if (!empty($errors)) {
                require_once __DIR__ . '/../views/admin/games/edit.php';
                return;
            }
            
            // Chuẩn bị dữ liệu để cập nhật
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? '',
                'image' => $imageName,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Cập nhật vào database
            if ($gameModel->update($id, $data)) {
                $_SESSION['success'] = "Cập nhật danh mục game thành công";
                header('Location: ' . BASE_URL . 'admin/games');
                exit;
            } else {
                $_SESSION['error'] = "Cập nhật danh mục game thất bại";
                require_once __DIR__ . '/../views/admin/games/edit.php';
                return;
            }
        }
        
        require_once __DIR__ . '/../views/admin/games/edit.php';
    }
    
    // Xóa danh mục game
    public function deleteGame($id) {
        $this->checkAdmin();
        
        // Khởi tạo model Game
        $gameModel = new \App\Models\Game();
        
        // Lấy thông tin game trước khi xóa (để xóa ảnh)
        $game = $gameModel->find($id);
        
        // Xóa game
        if ($gameModel->delete($id)) {
            // Xóa ảnh nếu có
            if (!empty($game['image'])) {
                $imagePath = 'uploads/games/' . $game['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $_SESSION['success'] = "Xóa danh mục game thành công";
        } else {
            $_SESSION['error'] = "Xóa danh mục game thất bại. Có thể còn tài khoản game liên kết đến danh mục này.";
        }
        
        header('Location: ' . BASE_URL . 'admin/games');
        exit;
    }

    // Thêm tài khoản game mới
    public function createAccount() {
        $this->checkAdmin();

        // Lấy danh sách game để hiển thị trong form
        $gameModel = new \App\Models\Game();
        $games = $gameModel->getAll();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->gameAccount->category_id = $_POST['category_id'];
            $this->gameAccount->title = $_POST['title'];
            $this->gameAccount->description = $_POST['description'];
            $this->gameAccount->price = $_POST['price'];
            $this->gameAccount->game_level = $_POST['game_level'];
            $this->gameAccount->game_rank = $_POST['game_rank'];
            $this->gameAccount->game_server = $_POST['game_server'];
            $this->gameAccount->game_character = $_POST['game_character'];
            $this->gameAccount->game_items = $_POST['game_items'];

            if($this->gameAccount->create()) {
                $_SESSION['success'] = "Thêm tài khoản game thành công";
                header('Location: ' . BASE_URL . 'admin/accounts');
                exit;
            } else {
                $error = "Thêm tài khoản game thất bại";
            }
        }

        require_once __DIR__ . '/../views/admin/accounts/create.php';
    }

    // Cập nhật tài khoản game
    public function updateAccount($id) {
        $this->checkAdmin();
        
        // Lấy thông tin tài khoản
        $account = $this->gameAccount->find($id);
        if (!$account) {
            $_SESSION['error'] = "Không tìm thấy tài khoản game";
            header('Location: ' . BASE_URL . 'admin/accounts');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate dữ liệu
            $errors = [];
            
            if(empty($_POST['game_id'])) {
                $errors['game_id'] = "Vui lòng chọn game";
            }
            
            if(empty($_POST['username'])) {
                $errors['username'] = "Vui lòng nhập tài khoản";
            }
            
            if(empty($_POST['password'])) {
                $errors['password'] = "Vui lòng nhập mật khẩu";
            }
            
            if(empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
                $errors['price'] = "Vui lòng nhập giá hợp lệ";
            }
            
            // Kiểm tra hình ảnh mới (nếu có)
            if (!empty($_FILES['screenshots']['name'][0])) {
                // Kiểm tra số lượng ảnh
                if (count($_FILES['screenshots']['name']) > 5) {
                    $errors['screenshots'] = "Chỉ được chọn tối đa 5 ảnh";
                }
                
                // Kiểm tra định dạng file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                foreach ($_FILES['screenshots']['type'] as $type) {
                    if (!in_array($type, $allowedTypes)) {
                        $errors['screenshots'] = "Chỉ chấp nhận file ảnh (JPG, PNG)";
                        break;
                    }
                }
            }
            
            // Nếu có lỗi, hiển thị form với thông báo lỗi
            if (!empty($errors)) {
                // Lấy danh sách game
                $gameModel = new \App\Models\Game();
                $games = $gameModel->getAll();
                
                // Lấy danh sách ảnh chụp màn hình
                $screenshots = $this->gameAccount->getAccountScreenshots($id);
                
                require_once __DIR__ . '/../views/admin/accounts/edit.php';
                return;
            }
            
            // Xử lý xóa ảnh đã chọn
            if (isset($_POST['delete_screenshots']) && !empty($_POST['delete_screenshots'])) {
                foreach ($_POST['delete_screenshots'] as $screenshotId) {
                    // Lấy thông tin ảnh
                    $sql = "SELECT * FROM account_screenshots WHERE id = :id AND account_id = :account_id";
                    $stmt = $this->gameAccount->getConnection()->prepare($sql);
                    $stmt->bindValue(':id', $screenshotId, PDO::PARAM_INT);
                    $stmt->bindValue(':account_id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $screenshot = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($screenshot) {
                        // Xóa file ảnh
                        $filePath = 'uploads/accounts/' . $screenshot['filename'];
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        
                        // Xóa record trong database
                        $sql = "DELETE FROM account_screenshots WHERE id = :id";
                        $stmt = $this->gameAccount->getConnection()->prepare($sql);
                        $stmt->bindValue(':id', $screenshotId, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            }
            
            // Xử lý upload ảnh mới
            $newScreenshots = [];
            if (!empty($_FILES['screenshots']['name'][0])) {
                $uploadDir = 'uploads/accounts/';
                
                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Upload từng file
                for ($i = 0; $i < count($_FILES['screenshots']['name']); $i++) {
                    $fileName = uniqid() . '_' . $_FILES['screenshots']['name'][$i];
                    $uploadFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['screenshots']['tmp_name'][$i], $uploadFile)) {
                        $newScreenshots[] = $fileName;
                    }
                }
            }
            
            // Chuẩn bị dữ liệu để cập nhật
            $data = [
                'game_id' => $_POST['game_id'],
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'price' => $_POST['price'],
                'status' => $_POST['status'],
                'description' => $_POST['details'] ?? ''
            ];
            
            // Cập nhật thông tin tài khoản
            if ($this->gameAccount->update($id, $data)) {
                // Lưu ảnh mới vào bảng account_screenshots
                if (!empty($newScreenshots)) {
                    foreach ($newScreenshots as $screenshot) {
                        $screenshotData = [
                            'account_id' => $id,
                            'filename' => $screenshot,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->saveScreenshot($screenshotData);
                    }
                }
                
                $_SESSION['success'] = "Cập nhật tài khoản game thành công";
                header('Location: ' . BASE_URL . 'admin/accounts');
                exit;
            } else {
                $_SESSION['error'] = "Cập nhật tài khoản game thất bại";
                header('Location: ' . BASE_URL . 'admin/accounts/edit/' . $id);
                exit;
            }
        }
        
        // Lấy danh sách game
        $gameModel = new \App\Models\Game();
        $games = $gameModel->getAll();
        
        // Lấy danh sách ảnh chụp màn hình
        $screenshots = $this->gameAccount->getAccountScreenshots($id);
        
        // Hiển thị form chỉnh sửa
        require_once __DIR__ . '/../views/admin/accounts/edit.php';
    }

    // Xóa tài khoản game
    public function deleteAccount($id) {
        $this->checkAdmin();

        if($this->gameAccount->delete($id)) {
            $_SESSION['success'] = "Xóa tài khoản game thành công";
        } else {
            $_SESSION['error'] = "Xóa tài khoản game thất bại";
        }

        header('Location: ' . BASE_URL . 'admin/accounts');
        exit;
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($id) {
        $this->checkAdmin();

        $this->order->id = $id;
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        if (empty($status) || !in_array($status, ['pending', 'completed', 'cancelled'])) {
            $_SESSION['error'] = "Trạng thái không hợp lệ";
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }
        
        $this->order->status = $status;

        if($this->order->updateStatus()) {
            $_SESSION['success'] = "Cập nhật trạng thái đơn hàng thành công";
        } else {
            $_SESSION['error'] = "Cập nhật trạng thái đơn hàng thất bại";
        }

        header('Location: ' . BASE_URL . 'admin/orders/' . $id);
        exit;
    }

    // Xóa người dùng
    public function deleteUser($id) {
        $this->checkAdmin();

        if($this->user->delete($id)) {
            $_SESSION['success'] = "Xóa người dùng thành công";
        } else {
            $_SESSION['error'] = "Xóa người dùng thất bại";
        }

        header('Location: ' . BASE_URL . 'admin/users');
        exit;
    }

    // Lưu tài khoản game mới
    public function storeAccount() {
        $this->checkAdmin();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate dữ liệu
            $errors = [];
            
            if(empty($_POST['game_id'])) {
                $errors['game_id'] = "Vui lòng chọn game";
            }
            
            if(empty($_POST['username'])) {
                $errors['username'] = "Vui lòng nhập tài khoản";
            }
            
            if(empty($_POST['password'])) {
                $errors['password'] = "Vui lòng nhập mật khẩu";
            }
            
            if(empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
                $errors['price'] = "Vui lòng nhập giá hợp lệ";
            }
            
            // Kiểm tra hình ảnh
            if(empty($_FILES['screenshots']['name'][0])) {
                $errors['screenshots'] = "Vui lòng chọn ít nhất 1 ảnh";
            } else {
                // Kiểm tra số lượng ảnh
                if(count($_FILES['screenshots']['name']) > 5) {
                    $errors['screenshots'] = "Chỉ được chọn tối đa 5 ảnh";
                }
                
                // Kiểm tra định dạng file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                foreach($_FILES['screenshots']['type'] as $type) {
                    if(!in_array($type, $allowedTypes)) {
                        $errors['screenshots'] = "Chỉ chấp nhận file ảnh (JPG, PNG)";
                        break;
                    }
                }
            }
            
            // Nếu có lỗi, hiển thị form với thông báo lỗi
            if(!empty($errors)) {
                // Lấy danh sách game để hiển thị trong form
                $gameModel = new \App\Models\Game();
                $games = $gameModel->getAll();
                
                require_once __DIR__ . '/../views/admin/accounts/create.php';
                return;
            }
            
            // Xử lý upload ảnh
            $screenshots = [];
            if(!empty($_FILES['screenshots']['name'][0])) {
                $uploadDir = 'uploads/accounts/';
                
                // Tạo thư mục nếu chưa tồn tại
                if(!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Upload từng file
                for($i = 0; $i < count($_FILES['screenshots']['name']); $i++) {
                    $fileName = uniqid() . '_' . $_FILES['screenshots']['name'][$i];
                    $uploadFile = $uploadDir . $fileName;
                    
                    if(move_uploaded_file($_FILES['screenshots']['tmp_name'][$i], $uploadFile)) {
                        $screenshots[] = $fileName;
                    }
                }
            }
            
            // Chuẩn bị dữ liệu để lưu
            $data = [
                'game_id' => $_POST['game_id'],
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'price' => $_POST['price'],
                'status' => $_POST['status'],
                'description' => $_POST['details'] ?? '',
                // 'image' đã được xóa vì cột này không tồn tại trong bảng game_accounts
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Lưu vào database
            if($accountId = $this->gameAccount->create($data)) {
                // Nếu tạo tài khoản thành công, lưu các ảnh vào bảng account_screenshots
                if (!empty($screenshots)) {
                    // Kiểm tra xem bảng account_screenshots đã tồn tại chưa
                    $this->createScreenshotsTableIfNotExists();
                    
                    // Lưu từng ảnh vào bảng account_screenshots
                    foreach ($screenshots as $screenshot) {
                        $screenshotData = [
                            'account_id' => $accountId,
                            'filename' => $screenshot,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->saveScreenshot($screenshotData);
                    }
                }
                
                $_SESSION['success'] = "Thêm tài khoản game thành công";
                header('Location: ' . BASE_URL . 'admin/accounts');
                exit;
            } else {
                $_SESSION['error'] = "Thêm tài khoản game thất bại";
                header('Location: ' . BASE_URL . 'admin/accounts/create');
                exit;
            }
        }
        
        // Nếu không phải POST, chuyển hướng về trang create
        header('Location: ' . BASE_URL . 'admin/accounts/create');
        exit;
    }
    
    /**
     * Tạo bảng account_screenshots nếu chưa tồn tại
     */
    private function createScreenshotsTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS account_screenshots (
            id INT(11) NOT NULL AUTO_INCREMENT,
            account_id INT(11) NOT NULL,
            filename VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY account_id (account_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        try {
            $this->gameAccount->getConnection()->exec($sql);
        } catch (\PDOException $e) {
            // Ghi log lỗi
            error_log("Không thể tạo bảng account_screenshots: " . $e->getMessage());
        }
    }
    
    /**
     * Lưu ảnh vào bảng account_screenshots
     */
    private function saveScreenshot($data) {
        try {
            $sql = "INSERT INTO account_screenshots (account_id, filename, created_at) 
                   VALUES (:account_id, :filename, :created_at)";
            
            $stmt = $this->gameAccount->getConnection()->prepare($sql);
            $stmt->bindValue(':account_id', $data['account_id'], PDO::PARAM_INT);
            $stmt->bindValue(':filename', $data['filename'], PDO::PARAM_STR);
            $stmt->bindValue(':created_at', $data['created_at'], PDO::PARAM_STR);
            $stmt->execute();
            
            return $this->gameAccount->getConnection()->lastInsertId();
        } catch (\PDOException $e) {
            // Ghi log lỗi
            error_log("Không thể lưu ảnh: " . $e->getMessage());
            return false;
        }
    }

    // Lấy danh sách rank theo game
    public function getRanks() {
        $this->checkAdmin();
        
        if(isset($_GET['game_id'])) {
            $gameId = $_GET['game_id'];
            
            // Truy vấn ranks của game từ database
            $sql = "SELECT * FROM ranks WHERE game_id = :game_id ORDER BY name ASC";
            $params = [':game_id' => $gameId];
            
            // Sử dụng model Game để truy vấn
            $gameModel = new \App\Models\Game();
            $ranks = $gameModel->query($sql, $params);
            
            // Trả về dạng JSON
            header('Content-Type: application/json');
            echo json_encode($ranks);
            exit;
        }
        
        // Trả về mảng rỗng nếu không có game_id
        header('Content-Type: application/json');
        echo json_encode([]);
        exit;
    }

    // Cập nhật tài khoản game
    public function editAccount($id) {
        $this->checkAdmin();
        
        // Lấy thông tin tài khoản
        $account = $this->gameAccount->find($id);
        if (!$account) {
            $_SESSION['error'] = "Không tìm thấy tài khoản game";
            header('Location: ' . BASE_URL . 'admin/accounts');
            exit;
        }
        
        // Lấy danh sách game
        $gameModel = new \App\Models\Game();
        $games = $gameModel->getAll();
        
        // Lấy danh sách ảnh chụp màn hình
        $screenshots = $this->gameAccount->getAccountScreenshots($id);
        
        // Hiển thị form chỉnh sửa
        require_once __DIR__ . '/../views/admin/accounts/edit.php';
    }

    // Xem chi tiết người dùng
    public function viewUser($id) {
        $this->checkAdmin();
        
        // Lấy thông tin người dùng
        $user = $this->user->find($id);
        if (!$user) {
            $_SESSION['error'] = "Không tìm thấy người dùng";
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }
        
        require_once __DIR__ . '/../views/admin/users/show.php';
    }

    // Chỉnh sửa thông tin người dùng
    public function editUser($id) {
        $this->checkAdmin();
        
        // Lấy thông tin người dùng
        $user = $this->user->find($id);
        if (!$user) {
            $_SESSION['error'] = "Không tìm thấy người dùng";
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }
        
        // Xử lý form submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate dữ liệu
            $errors = [];
            
            if (empty($_POST['username'])) {
                $errors['username'] = "Vui lòng nhập tên đăng nhập";
            }
            
            if (empty($_POST['email'])) {
                $errors['email'] = "Vui lòng nhập email";
            }
            
            // Nếu có lỗi, hiển thị lại form với thông báo lỗi
            if (!empty($errors)) {
                require_once __DIR__ . '/../views/admin/users/edit.php';
                return;
            }
            
            // Chuẩn bị dữ liệu cập nhật
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'fullname' => $_POST['fullname'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'role' => $_POST['role'],
                'status' => $_POST['status']
            ];
            
            // Nếu có mật khẩu mới, cập nhật mật khẩu
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            
            // Cập nhật thông tin người dùng
            if ($this->user->update($id, $data)) {
                $_SESSION['success'] = "Cập nhật thông tin người dùng thành công";
                header('Location: ' . BASE_URL . 'admin/users');
                exit;
            } else {
                $_SESSION['error'] = "Cập nhật thông tin người dùng thất bại";
                require_once __DIR__ . '/../views/admin/users/edit.php';
                return;
            }
        }
        
        require_once __DIR__ . '/../views/admin/users/edit.php';
    }
}
?> 