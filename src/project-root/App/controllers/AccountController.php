<?php
namespace App\Controllers;

use App\Models\Account;
use App\Models\Game;

class AccountController {
    private $accountModel;
    private $gameModel;

    public function __construct() {
        $this->accountModel = new Account();
        $this->gameModel = new Game();
    }

    public function index() {
        try {
            // Lấy danh sách game cho filter ngay từ đầu
            $games = [];
            try {
                $games = $this->gameModel->getAllGames();
                // Sắp xếp game theo tên
                usort($games, function($a, $b) {
                    return strcmp($a['name'] ?? '', $b['name'] ?? '');
                });
            } catch (\Exception $e) {
                error_log('Error fetching games: ' . $e->getMessage());
                // Không throw exception ở đây, chỉ log lỗi và tiếp tục với mảng rỗng
            }

            // Nếu là AJAX request, đảm bảo không có output nào trước khi gửi JSON
            if (isset($_GET['ajax'])) {
                ob_clean();
                header('Content-Type: application/json');
            }

            // Lấy các tham số tìm kiếm và lọc
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $game = isset($_GET['game']) ? trim($_GET['game']) : '';
            $price = isset($_GET['price']) ? trim($_GET['price']) : '';
            $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;

            // Lấy tất cả tài khoản
            $accounts = $this->accountModel->getAll();

            // Chuẩn hóa dữ liệu tài khoản
            $accounts = array_map(function($account) {
                // Xử lý hình ảnh
                $images = [];
                if (!empty($account['images'])) {
                    $images = json_decode($account['images'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $images = [];
                    }
                }

                // Xử lý đường dẫn ảnh
                $mainImage = $images['main'] ?? '/img/default-account.jpg';
                $subImages = $images['sub'] ?? [];
                
                // Chuẩn hóa đường dẫn
                $mainImage = $this->normalizeImagePath($mainImage);
                $subImages = array_map([$this, 'normalizeImagePath'], $subImages);

                error_log("Final main image path: " . $mainImage);
                error_log("Final sub images: " . print_r($subImages, true));

                // Đảm bảo có đủ các trường cần thiết
                return [
                    'id' => $account['id'] ?? 0,
                    'title' => $account['title'] ?? 'Không có tiêu đề',
                    'basic_description' => $account['basic_description'] ?? '',
                    'price' => (float)($account['price'] ?? 0),
                    'status' => $account['status'] ?? 'available',
                    'game_name' => $account['game_name'] ?? 'Unknown Game',
                    'game_slug' => strtolower(str_replace(' ', '-', $account['game_name'] ?? 'unknown')),
                    'main_image' => BASE_PATH . $mainImage,
                    'sub_images' => array_map(function($img) {
                        return BASE_PATH . $img;
                    }, $subImages),
                    'created_at' => $account['created_at'] ?? date('Y-m-d H:i:s')
                ];
            }, $accounts);

            // Lọc theo từ khóa tìm kiếm
            if (!empty($search)) {
                $search = strtolower($search);
                $accounts = array_filter($accounts, function($account) use ($search) {
                    return strpos(strtolower($account['title']), $search) !== false ||
                           strpos(strtolower($account['basic_description']), $search) !== false;
                });
            }

            // Lọc theo game
            if (!empty($game)) {
                $accounts = array_filter($accounts, function($account) use ($game) {
                    return $account['game_slug'] === $game;
                });
            }

            // Lọc theo khoảng giá
            if (!empty($price)) {
                list($min, $max) = array_pad(explode('-', $price), 2, null);
                $accounts = array_filter($accounts, function($account) use ($min, $max) {
                    if ($max === null) {
                        return $account['price'] >= (float)$min;
                    }
                    return $account['price'] >= (float)$min && $account['price'] <= (float)$max;
                });
            }

            // Sắp xếp kết quả
            switch ($sort) {
                case 'price_asc':
                    usort($accounts, function($a, $b) {
                        return $a['price'] - $b['price'];
                    });
                    break;
                case 'price_desc':
                    usort($accounts, function($a, $b) {
                        return $b['price'] - $a['price'];
                    });
                    break;
                case 'oldest':
                    usort($accounts, function($a, $b) {
                        return strtotime($a['created_at']) - strtotime($b['created_at']);
                    });
                    break;
                case 'newest':
                default:
                    usort($accounts, function($a, $b) {
                        return strtotime($b['created_at']) - strtotime($a['created_at']);
                    });
                    break;
            }

            // Tính toán phân trang
            $total = count($accounts);
            $totalPages = ceil($total / $limit);
            $page = max(1, min($page, $totalPages));
            $offset = ($page - 1) * $limit;

            // Lấy dữ liệu cho trang hiện tại
            $accounts = array_slice($accounts, $offset, $limit);

            // Nếu là AJAX request, trả về JSON
            if (isset($_GET['ajax'])) {
                $response = [
                    'success' => true,
                    'accounts' => array_values($accounts),
                    'pagination' => [
                        'current' => $page,
                        'total' => $totalPages,
                        'total_records' => $total
                    ],
                    'games' => $games
                ];
                
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Lưu thông báo thành công nếu có
            $success_message = isset($_SESSION['success']) ? $_SESSION['success'] : null;
            unset($_SESSION['success']);

            // Kiểm tra và lưu thông báo nếu không có tài khoản
            if (empty($accounts)) {
                $no_accounts_message = 'Hiện chưa có tài khoản nào được đăng bán.';
            }

            require_once ROOT_PATH . '/App/views/accounts/index.php';

        } catch (\Exception $e) {
            error_log('AccountController::index - Error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            if (isset($_GET['ajax'])) {
                ob_clean();
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'error' => true,
                    'message' => 'Có lỗi xảy ra khi tải dữ liệu',
                    'debug' => DEBUG ? $e->getMessage() : null
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            $_SESSION['error'] = 'Không thể tải danh sách tài khoản. Vui lòng thử lại sau.';
            // Đảm bảo $games được định nghĩa ngay cả khi có lỗi
            $games = [];
            require_once ROOT_PATH . '/App/views/accounts/index.php';
        }
    }

    public function show($id) {
        try {
            $account = $this->accountModel->getById($id);
            if (!$account) {
                $_SESSION['error'] = 'Account not found';
                header('Location: ' . BASE_PATH . '/accounts');
                exit;
            }
            require_once ROOT_PATH . '/App/views/accounts/show.php';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to load account details. Please try again.';
            header('Location: ' . BASE_PATH . '/accounts');
            exit;
        }
    }

    public function sell() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to sell accounts';
            header('Location: ' . BASE_PATH . '/login');
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
                        header('Location: ' . BASE_PATH . '/accounts');
                        exit;
                    }
                } catch (\Exception $e) {
                    $errors['general'] = 'Failed to list account. Please try again.';
                }
            }
        }
        require_once ROOT_PATH . '/App/views/accounts/sell.php';
    }

    public function update($id) {
        try {
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện chức năng này';
                header('Location: ' . BASE_PATH . '/login');
                exit;
            }

            // Kiểm tra phương thức request
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $_SESSION['error'] = 'Phương thức không được hỗ trợ';
                header('Location: ' . BASE_PATH . '/accounts');
                exit;
            }

            // Debug POST data
            error_log("POST data received: " . print_r($_POST, true));
            error_log("FILES data received: " . print_r($_FILES, true));

            // Lấy thông tin tài khoản hiện tại
            $account = $this->accountModel->getAccountById($id);
            if (!$account) {
                $_SESSION['error'] = 'Không tìm thấy tài khoản';
                header('Location: ' . BASE_PATH . '/accounts');
                exit;
            }

            // Debug current account data
            error_log("Current account data: " . print_r($account, true));

            // Kiểm tra quyền (chỉ admin hoặc chủ tài khoản mới được sửa)
            if (!isset($_SESSION['is_admin']) && $account['seller_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'Bạn không có quyền sửa tài khoản này';
                header('Location: ' . BASE_PATH . '/accounts');
                exit;
            }

            // Validate required fields
            $requiredFields = ['game_id', 'title', 'username', 'password', 'basic_description', 'price'];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    throw new \Exception("Trường {$field} không được để trống");
                }
            }

            // Validate price
            if (!is_numeric($_POST['price']) || floatval($_POST['price']) <= 0) {
                throw new \Exception("Giá phải là số dương");
            }

            // Chuẩn bị dữ liệu cập nhật
            $updateData = [
                'game_id' => trim($_POST['game_id']),
                'title' => trim($_POST['title']),
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'basic_description' => trim($_POST['basic_description']),
                'detailed_description' => isset($_POST['detailed_description']) ? trim($_POST['detailed_description']) : null,
                'description' => isset($_POST['description']) ? trim($_POST['description']) : null,
                'price' => floatval($_POST['price']),
                'status' => isset($_POST['status']) ? trim($_POST['status']) : 'available'
            ];

            // Debug update data
            error_log("Prepared update data: " . print_r($updateData, true));

            // Xử lý upload ảnh nếu có
            if (!empty($_FILES['images']['name'][0])) {
                error_log("Processing image upload...");
                $uploadedImages = $this->handleImageUpload($_FILES['images']);
                if (isset($uploadedImages['error'])) {
                    throw new \Exception($uploadedImages['error']);
                }
                $updateData['images'] = $uploadedImages;
                error_log("Image upload successful: " . print_r($uploadedImages, true));
            } else {
                // Giữ lại ảnh cũ nếu không upload ảnh mới
                $updateData['images'] = $account['images'];
                error_log("Keeping existing images: " . $account['images']);
            }

            // Thực hiện cập nhật
            try {
                $result = $this->accountModel->updateAccount($id, $updateData);
                error_log("Update result: " . ($result ? "success" : "failed"));
                
                $_SESSION['success'] = 'Cập nhật tài khoản thành công';
                header('Location: ' . BASE_PATH . '/accounts/show/' . $id);
                exit;
            } catch (\Exception $e) {
                error_log("Database update error: " . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            error_log("Error in AccountController::update - " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_PATH . '/accounts/edit/' . $id);
            exit;
        }
    }

    private function normalizeImagePath($path) {
        // Nếu path trống hoặc null, trả về đường dẫn mặc định
        if (empty($path)) {
            return '/img/default-account.jpg';
        }

        // Debug log
        error_log("Original path from DB: " . $path);

        // Loại bỏ tất cả các phần /public/ và đường dẫn project
        $path = str_replace([
            '/project-clone-web-buy-acc/src/project-root/public/',
            '/project-clone-web-buy-acc/src/project-root/',
            '/public/public/',
            '/public/'
        ], '', $path);

        // Đảm bảo đường dẫn bắt đầu bằng /
        $path = '/' . ltrim($path, '/');

        error_log("Normalized path: " . $path);
        return $path;
    }

    private function handleImageUpload($files) {
        try {
            $uploadDir = ROOT_PATH . '/public/img/accounts/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $images = ['main' => '', 'sub' => []];
            
            // Xử lý ảnh chính
            if (isset($files['main_image']) && $files['main_image']['error'] === UPLOAD_ERR_OK) {
                $extension = pathinfo($files['main_image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('main_') . '.' . $extension;
                $uploadFile = $uploadDir . $filename;
                
                if (move_uploaded_file($files['main_image']['tmp_name'], $uploadFile)) {
                    // Lưu đường dẫn tương đối vào database
                    $images['main'] = '/img/accounts/' . $filename;
                    error_log("New main image path saved to DB: " . $images['main']);
                }
            }
            
            // Xử lý ảnh phụ
            if (isset($files['sub_images'])) {
                foreach ($files['sub_images']['tmp_name'] as $key => $tmp_name) {
                    if ($files['sub_images']['error'][$key] === UPLOAD_ERR_OK) {
                        $extension = pathinfo($files['sub_images']['name'][$key], PATHINFO_EXTENSION);
                        $filename = uniqid('sub_') . '.' . $extension;
                        $uploadFile = $uploadDir . $filename;
                        
                        if (move_uploaded_file($tmp_name, $uploadFile)) {
                            // Lưu đường dẫn tương đối vào database
                            $images['sub'][] = '/img/accounts/' . $filename;
                            error_log("New sub image path saved to DB: " . end($images['sub']));
                        }
                    }
                }
            }
            
            return json_encode($images);
        } catch (\Exception $e) {
            error_log("Error in handleImageUpload: " . $e->getMessage());
            return json_encode(['main' => '/img/default-account.jpg', 'sub' => []]);
        }
    }
} 