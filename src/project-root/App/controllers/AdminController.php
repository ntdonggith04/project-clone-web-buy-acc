<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Game;
use App\Models\Account;
use App\Middleware\AdminMiddleware;

class AdminController {
    private $userModel;
    private $gameModel;
    private $accountModel;

    public function __construct() {
        // Kiểm tra quyền admin trước khi thực hiện bất kỳ action nào
        AdminMiddleware::checkAdminAccess();
        
        $this->userModel = new User();
        $this->gameModel = new Game();
        $this->accountModel = new Account();
    }

    public function index() {
        // Thống kê cơ bản
        $stats = [
            'total_users' => $this->userModel->getTotalUsers(),
            'total_games' => $this->gameModel->getTotalGames(),
            'total_accounts' => $this->accountModel->getTotalAccounts(),
            'total_sales' => $this->accountModel->getTotalSales(),
            
            // Dữ liệu cho biểu đồ phân bố tài khoản theo game
            'accounts_by_game' => $this->accountModel->getAccountsByGame(),
            
            // Dữ liệu cho biểu đồ doanh số theo tháng
            'monthly_sales' => $this->accountModel->getMonthlySales()
        ];

        // Lấy danh sách tài khoản mới nhất
        $accounts = $this->accountModel->getAllAccounts();
        error_log("Dashboard accounts data: " . print_r($accounts, true));

        require_once ROOT_PATH . '/App/views/admin/dashboard.php';
    }

    public function users() {
        $users = $this->userModel->getAllUsers();
        require_once ROOT_PATH . '/App/views/admin/users.php';
    }

    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => $_POST['role']
            ];

            if ($this->userModel->create($userData)) {
                $_SESSION['success'] = 'Thêm người dùng thành công';
            } else {
                $_SESSION['error'] = 'Thêm người dùng thất bại';
            }
        }
        header('Location: /project-clone-web-buy-acc/src/project-root/public/admin/users');
        exit;
    }

    public function getUser($id) {
        $user = $this->userModel->getUserById($id);
        header('Content-Type: application/json');
        echo json_encode($user);
        exit;
    }

    public function editUser($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'role' => $_POST['role']
            ];

            // Chỉ cập nhật mật khẩu nếu có nhập mật khẩu mới
            if (!empty($_POST['password'])) {
                $userData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($this->userModel->updateUser($id, $userData)) {
                $_SESSION['success'] = 'Cập nhật người dùng thành công';
            } else {
                $_SESSION['error'] = 'Cập nhật người dùng thất bại';
            }
        }
        header('Location: /project-clone-web-buy-acc/src/project-root/public/admin/users');
        exit;
    }

    public function deleteUser($id) {
        // Kiểm tra người dùng tồn tại
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy người dùng';
            header('Location: ' . BASE_PATH . '/admin/users');
            exit;
        }

        // Không cho phép xóa tài khoản admin
        if ($user['is_admin']) {
            $_SESSION['error'] = 'Không thể xóa tài khoản admin';
            header('Location: ' . BASE_PATH . '/admin/users');
            exit;
        }

        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = 'Xóa người dùng thành công';
        } else {
            $_SESSION['error'] = 'Xóa người dùng thất bại';
        }
        header('Location: ' . BASE_PATH . '/admin/users');
        exit;
    }

    public function banUser($id) {
        if ($this->userModel->banUser($id)) {
            $_SESSION['success'] = 'Khóa tài khoản thành công';
        } else {
            $_SESSION['error'] = 'Khóa tài khoản thất bại';
        }
        header('Location: ' . BASE_PATH . '/admin/users');
        exit;
    }

    public function unbanUser($id) {
        if ($this->userModel->unbanUser($id)) {
            $_SESSION['success'] = 'Mở khóa tài khoản thành công';
        } else {
            $_SESSION['error'] = 'Mở khóa tài khoản thất bại';
        }
        header('Location: ' . BASE_PATH . '/admin/users');
        exit;
    }

    public function games() {
        try {
            // Kiểm tra nếu là request lấy danh sách game cho autocomplete
            if (isset($_GET['get_games'])) {
                header('Content-Type: application/json');
                echo json_encode($this->gameModel->getAllGames());
                exit;
            }

            // Lấy tham số tìm kiếm và lọc
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

            // Lấy tất cả games
            $games = $this->gameModel->getAllGames();

            // Lọc theo tên game nếu có tìm kiếm
            if (!empty($search)) {
                $search = strtolower($search);
                $games = array_filter($games, function($game) use ($search) {
                    return strpos(strtolower($game['name']), $search) !== false ||
                           strpos(strtolower($game['slug']), $search) !== false ||
                           strpos(strtolower($game['short_description'] ?? ''), $search) !== false;
                });
            }

            // Sắp xếp kết quả
            switch ($sort) {
                case 'name_asc':
                    usort($games, function($a, $b) {
                        return strcmp(strtolower($a['name']), strtolower($b['name']));
                    });
                    break;
                case 'name_desc':
                    usort($games, function($a, $b) {
                        return strcmp(strtolower($b['name']), strtolower($a['name']));
                    });
                    break;
                case 'oldest':
                    usort($games, function($a, $b) {
                        return strtotime($a['created_at']) - strtotime($b['created_at']);
                    });
                    break;
                case 'newest':
                default:
                    usort($games, function($a, $b) {
                        return strtotime($b['created_at']) - strtotime($a['created_at']);
                    });
                    break;
            }

            // Tính toán phân trang
            $total = count($games);
            $totalPages = ceil($total / $limit);
            $page = max(1, min($page, $totalPages));
            $offset = ($page - 1) * $limit;

            // Lấy dữ liệu cho trang hiện tại
            $games = array_slice($games, $offset, $limit);

            // Chuẩn bị dữ liệu phân trang
            $pagination = [
                'current' => $page,
                'total' => $totalPages,
                'limit' => $limit,
                'total_records' => $total
            ];

            // Log cho debug
            error_log("Search params: " . json_encode([
                'search' => $search,
                'sort' => $sort,
                'limit' => $limit,
                'page' => $page,
                'total_records' => $total
            ]));

            require_once ROOT_PATH . '/App/views/admin/games.php';
        } catch (\Exception $e) {
            error_log("Error in games(): " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = 'Có lỗi xảy ra khi tải danh sách danh mục';
            $games = [];
            require_once ROOT_PATH . '/App/views/admin/games.php';
        }
    }

    public function addGame() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Phương thức không được hỗ trợ');
            }

            // Validate input
            if (empty($_POST['name'])) {
                throw new \Exception('Tên game không được để trống');
            }

            // Xử lý upload ảnh
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = ROOT_PATH . '/public/images/games/';  // Thư mục vật lý để lưu file
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileInfo = pathinfo($_FILES['image']['name']);
                $extension = strtolower($fileInfo['extension']);
                
                // Kiểm tra định dạng file
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($extension, $allowedTypes)) {
                    throw new \Exception('Định dạng file không được hỗ trợ');
                }

                // Tạo tên file mới
                $newFileName = uniqid() . '.' . $extension;
                $uploadPath = $uploadDir . $newFileName;

                // Di chuyển file upload
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    throw new \Exception('Không thể upload ảnh');
                }

                // Lưu đường dẫn web để hiển thị
                $image = '/public/images/games/' . $newFileName;
                error_log("New image path saved: " . $image);
            }

            // Chuẩn bị dữ liệu
            $gameData = [
                'name' => $_POST['name'],
                'slug' => $this->createSlug($_POST['name']),
                'description' => $_POST['description'] ?? '',
                'short_description' => $_POST['short_description'] ?? '',
                'image' => $image,
                'status' => $_POST['status'] ?? 'active',
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Thêm game mới
            if ($this->gameModel->addGame($gameData)) {
                $_SESSION['success'] = 'Thêm game thành công';
            } else {
                throw new \Exception('Thêm game thất bại');
            }

            // Redirect về trang danh sách game
            header('Location: ' . BASE_PATH . '/admin/games');
            exit;

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_PATH . '/admin/games');
            exit;
        }
    }

    private function createSlug($string) {
        // Chuyển đổi sang chữ thường
        $string = strtolower($string);
        
        // Chuyển đổi tiếng Việt sang không dấu
        $string = $this->removeAccents($string);
        
        // Thay thế các ký tự đặc biệt bằng dấu gạch ngang
        $string = preg_replace('/[^a-z0-9\-]/', '-', $string);
        
        // Xóa các dấu gạch ngang liên tiếp
        $string = preg_replace('/-+/', '-', $string);
        
        // Xóa dấu gạch ngang ở đầu và cuối
        $string = trim($string, '-');
        
        return $string;
    }

    private function removeAccents($string) {
        $search = array(
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ'
        );
        $replace = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        );
        return str_replace($search, $replace, $string);
    }

    public function accounts() {
        try {
            error_log("Starting to fetch accounts...");
            
            // Debug database connection
            if (!$this->accountModel->checkTableStructure()) {
                error_log("Table structure check failed");
            }
            
            // Lấy danh sách games và loại bỏ trùng lặp dựa trên game_id
            $allGames = $this->gameModel->getAllGames();
            error_log("Games data: " . print_r($allGames, true));
            
            $uniqueGames = [];
            $seenIds = [];
            
            foreach ($allGames as $game) {
                if (!isset($seenIds[$game['id']])) {
                    $uniqueGames[] = $game;
                    $seenIds[$game['id']] = true;
                }
            }
            
            // Sắp xếp games theo tên
            usort($uniqueGames, function($a, $b) {
                return strcmp($a['name'] ?? '', $b['name'] ?? '');
            });
            
            $games = $uniqueGames;
            error_log("Unique games: " . print_r($games, true));
            
            // Xử lý các tham số tìm kiếm và lọc
            $filters = [
                'game_id' => $_GET['game_id'] ?? null,
                'status' => $_GET['status'] ?? null,
                'search' => $_GET['search'] ?? null,
                'sort' => $_GET['sort'] ?? 'newest'
            ];
            error_log("Applied filters: " . print_r($filters, true));
            
            // Lấy danh sách tài khoản
            $accounts = $this->accountModel->getAllAccounts();
            error_log("Raw accounts data: " . print_r($accounts, true));
            
            // Xử lý và chuẩn hóa dữ liệu tài khoản
            foreach ($accounts as &$account) {
                // Đảm bảo các trường cơ bản tồn tại
                $account = array_merge([
                    'images' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'price' => 0,
                    'status' => 'available',
                    'title' => 'Không có tiêu đề',
                    'basic_description' => '',
                    'game_id' => 0
                ], $account);

                // Xử lý thông tin hình ảnh
                if (empty($account['images'])) {
                    $account['images'] = json_encode([
                        'main' => '/public/images/default-game.png',
                        'sub' => []
                    ]);
                }
                
                // Decode thông tin hình ảnh
                $imagesData = json_decode($account['images'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Nếu JSON không hợp lệ, sử dụng giá trị mặc định
                    $imagesData = [
                        'main' => '/public/images/default-game.png',
                        'sub' => []
                    ];
                }
                $account['images'] = $imagesData;
                
                // Đảm bảo có ảnh chính
                if (empty($account['images']['main'])) {
                    $account['images']['main'] = '/public/images/default-game.png';
                }
                
                // Đảm bảo mảng ảnh phụ tồn tại
                if (!isset($account['images']['sub']) || !is_array($account['images']['sub'])) {
                    $account['images']['sub'] = [];
                }
                
                // Lấy thông tin game
                $game = $this->gameModel->getGameById($account['game_id']);
                $account['game_name'] = $game ? $game['name'] : 'Unknown Game';
                
                // Format giá
                $account['formatted_price'] = number_format($account['price'], 0, ',', '.') . ' VNĐ';
                
                // Format thời gian
                try {
                    $account['created_at'] = date('d/m/Y H:i', strtotime($account['created_at']));
                    $account['updated_at'] = date('d/m/Y H:i', strtotime($account['updated_at']));
                } catch (\Exception $e) {
                    $account['created_at'] = date('d/m/Y H:i');
                    $account['updated_at'] = date('d/m/Y H:i');
                }
            }
            unset($account);
            
            // Lọc kết quả nếu có điều kiện lọc
            if (!empty($filters['game_id'])) {
                $accounts = array_filter($accounts, function($account) use ($filters) {
                    return $account['game_id'] == $filters['game_id'];
                });
            }
            
            if (!empty($filters['status'])) {
                $accounts = array_filter($accounts, function($account) use ($filters) {
                    return $account['status'] == $filters['status'];
                });
            }
            
            if (!empty($filters['search'])) {
                $search = strtolower($filters['search']);
                $accounts = array_filter($accounts, function($account) use ($search) {
                    return strpos(strtolower($account['username'] ?? ''), $search) !== false ||
                           strpos(strtolower($account['title'] ?? ''), $search) !== false ||
                           strpos(strtolower($account['basic_description'] ?? ''), $search) !== false;
                });
            }
            
            // Sắp xếp kết quả
            switch ($filters['sort']) {
                case 'price_asc':
                    usort($accounts, function($a, $b) {
                        return ($a['price'] ?? 0) - ($b['price'] ?? 0);
                    });
                    break;
                case 'price_desc':
                    usort($accounts, function($a, $b) {
                        return ($b['price'] ?? 0) - ($a['price'] ?? 0);
                    });
                    break;
                case 'oldest':
                    usort($accounts, function($a, $b) {
                        return strtotime($a['created_at'] ?? '0') - strtotime($b['created_at'] ?? '0');
                    });
                    break;
                case 'newest':
                default:
                    usort($accounts, function($a, $b) {
                        return strtotime($b['created_at'] ?? '0') - strtotime($a['created_at'] ?? '0');
                    });
                    break;
            }
            
            error_log("Filtered accounts: " . count($accounts));
            
            // Phân trang
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 10;
            $total = count($accounts);
            $totalPages = ceil($total / $perPage);
            $page = max(1, min($page, $totalPages));
            $offset = ($page - 1) * $perPage;
            
            // Lấy dữ liệu cho trang hiện tại
            $accounts = array_slice($accounts, $offset, $perPage);
            
            // Chuẩn bị dữ liệu phân trang
            $pagination = [
                'current' => $page,
                'total' => $totalPages,
                'offset' => $offset,
                'total_records' => $total
            ];
            
            require_once ROOT_PATH . '/App/views/admin/accounts.php';
            
        } catch (\Exception $e) {
            error_log("Error in accounts(): " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = 'Có lỗi xảy ra khi tải danh sách tài khoản';
            $accounts = [];
            $games = [];
            $pagination = ['current' => 1, 'total' => 1, 'offset' => 0, 'total_records' => 0];
            require_once ROOT_PATH . '/App/views/admin/accounts.php';
        }
    }

    public function deleteAccount($id) {
        if ($this->accountModel->deleteAccount($id)) {
            $_SESSION['success'] = 'Xóa tài khoản thành công';
        } else {
            $_SESSION['error'] = 'Xóa tài khoản thất bại';
        }
        header('Location: ' . BASE_PATH . '/admin/accounts');
        exit;
    }

    public function deleteGame($id) {
        try {
            // Kiểm tra xem game có tồn tại không
            $game = $this->gameModel->getGameById($id);
            if (!$game) {
                $_SESSION['error'] = 'Game không tồn tại';
                header('Location: ' . BASE_PATH . '/admin/games');
                exit;
            }

            // Kiểm tra xem có force delete không
            $force = isset($_GET['force']) && $_GET['force'] === '1';

            // Thử xóa game
            $result = $this->gameModel->deleteGame($id, $force);
            
            if ($result) {
                $_SESSION['success'] = $force ? 
                    'Đã xóa game và tất cả tài khoản liên quan' : 
                    'Xóa game thành công';
            } else {
                if (!$force) {
                    // Nếu không phải force delete và có tài khoản liên quan
                    $_SESSION['warning'] = 'Game này đang có tài khoản. Bạn có muốn xóa cả game và tài khoản?';
                    $_SESSION['game_to_delete'] = $id;
                } else {
                    $_SESSION['error'] = 'Không thể xóa game. Vui lòng thử lại sau';
                }
            }
        } catch (\Exception $e) {
            error_log("Error in deleteGame controller: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa game';
        }
        
        header('Location: ' . BASE_PATH . '/admin/games');
        exit;
    }

    public function addAccount() {
        try {
            error_log("BASE_PATH: " . BASE_PATH);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Log received data for debugging
                error_log('POST data: ' . print_r($_POST, true));
                error_log('FILES data: ' . print_r($_FILES, true));

                // Validate required fields
                $requiredFields = ['game_id', 'title', 'username', 'password', 'basic_description', 'price'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc";
                        header('Location: ' . BASE_PATH . '/admin/accounts');
                        return;
                    }
                }

                // Validate game_id exists
                $game = $this->gameModel->getGameById($_POST['game_id']);
                if (!$game) {
                    $_SESSION['error'] = "Game không tồn tại";
                    header('Location: ' . BASE_PATH . '/admin/accounts');
                    return;
                }

                // Validate price is numeric and non-negative
                if (!is_numeric($_POST['price']) || $_POST['price'] < 0) {
                    $_SESSION['error'] = "Giá phải là số và không được âm";
                    header('Location: ' . BASE_PATH . '/admin/accounts');
                    return;
                }

                // Handle image uploads
                $uploadedImages = [];
                $uploadDir = ROOT_PATH . '/public/images/accounts/';
                $webPath = '/public/images/accounts/'; // Path for web access
                
                // Create upload directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Handle main image upload
                if (!isset($_FILES['main_image']) || $_FILES['main_image']['error'] !== UPLOAD_ERR_OK) {
                    // Use default image if no main image is uploaded
                    $uploadedImages['main'] = 'default/default-account.svg';
                } else {
                    $mainImage = $_FILES['main_image'];
                    $mainImageType = strtolower(pathinfo($mainImage['name'], PATHINFO_EXTENSION));
                    
                    // Validate main image type
                    if (!in_array($mainImageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $_SESSION['error'] = "Ảnh chính phải là định dạng JPG, PNG hoặc GIF";
                        header('Location: ' . BASE_PATH . '/admin/accounts');
                        return;
                    }

                    // Generate unique filename for main image
                    $mainImageName = uniqid('main_') . '.' . $mainImageType;
                    $mainImagePath = $uploadDir . $mainImageName;

                    // Upload main image
                    if (!move_uploaded_file($mainImage['tmp_name'], $mainImagePath)) {
                        $_SESSION['error'] = "Không thể tải lên ảnh chính";
                        header('Location: ' . BASE_PATH . '/admin/accounts');
                        return;
                    }

                    $uploadedImages['main'] = $mainImageName;
                }

                // Handle sub-images upload
                if (isset($_FILES['sub_images']) && !empty($_FILES['sub_images']['name'][0])) {
                    $subImages = $_FILES['sub_images'];
                    $subImageCount = 0;

                    foreach ($subImages['name'] as $key => $name) {
                        if ($subImageCount >= 5) break; // Limit to 5 sub-images

                        if ($subImages['error'][$key] === UPLOAD_ERR_OK) {
                            $imageType = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            
                            // Validate sub-image type
                            if (in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                $imageName = uniqid('sub_') . '.' . $imageType;
                                $imagePath = $uploadDir . $imageName;

                                if (move_uploaded_file($subImages['tmp_name'][$key], $imagePath)) {
                                    $uploadedImages['sub'][] = $imageName;
                                    $subImageCount++;
                                }
                            }
                        }
                    }
                }

                // Store image paths for database
                $imageData = [
                    'main' => $webPath . $uploadedImages['main'],
                    'sub' => isset($uploadedImages['sub']) ? array_map(function($img) use ($webPath) {
                        return $webPath . $img;
                    }, $uploadedImages['sub']) : []
                ];

                // Prepare account data
                $accountData = [
                    'game_id' => $_POST['game_id'],
                    'seller_id' => $_SESSION['user_id'] ?? 1, // Default to admin if session not set
                    'title' => $_POST['title'],
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                    'basic_description' => $_POST['basic_description'],
                    'detailed_description' => $_POST['detailed_description'] ?? null,
                    'description' => $_POST['description'] ?? null,
                    'price' => $_POST['price'],
                    'status' => $_POST['status'] ?? 'available',
                    'images' => json_encode($imageData)
                ];

                // Create account
                $result = $this->accountModel->createAccount($accountData);

                if ($result) {
                    $_SESSION['success'] = "Tài khoản đã được thêm thành công";
                } else {
                    // Delete uploaded images if account creation fails
                    if (isset($uploadedImages['main']) && $uploadedImages['main'] !== 'default/default-account.svg') {
                        @unlink($uploadDir . $uploadedImages['main']);
                    }
                    if (isset($uploadedImages['sub'])) {
                        foreach ($uploadedImages['sub'] as $subImage) {
                            @unlink($uploadDir . $subImage);
                        }
                    }
                    $_SESSION['error'] = "Không thể thêm tài khoản";
                }

            } else {
                $_SESSION['error'] = "Phương thức không hợp lệ";
            }

        } catch (Exception $e) {
            error_log("Error in addAccount: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = "Đã xảy ra lỗi khi thêm tài khoản";
        }

        header('Location: ' . BASE_PATH . '/admin/accounts');
        exit;
    }

    public function getGame($id) {
        try {
            $game = $this->gameModel->getGameById($id);
            if (!$game) {
                http_response_code(404);
                echo json_encode(['error' => 'Game không tồn tại']);
                exit;
            }
            
            header('Content-Type: application/json');
            echo json_encode($game);
            exit;
        } catch (\Exception $e) {
            error_log("Error in getGame: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Có lỗi xảy ra khi lấy thông tin game']);
            exit;
        }
    }

    public function editGame($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $_SESSION['error'] = 'Phương thức không hợp lệ';
                header('Location: ' . BASE_PATH . '/admin/games');
                exit;
            }

            // Kiểm tra game có tồn tại không
            $game = $this->gameModel->getGameById($id);
            if (!$game) {
                $_SESSION['error'] = 'Game không tồn tại';
                header('Location: ' . BASE_PATH . '/admin/games');
                exit;
            }

            // Chuẩn bị dữ liệu cập nhật
            $updateData = [
                'name' => $_POST['name'],
                'slug' => $_POST['slug'],
                'description' => $_POST['description'],
                'short_description' => $_POST['short_description']
            ];

            // Xử lý upload ảnh mới nếu có
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = ROOT_PATH . '/public/images/games/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Kiểm tra quyền ghi file
                if (!is_writable($uploadDir)) {
                    error_log("Upload directory is not writable: " . $uploadDir);
                    $_SESSION['error'] = 'Không có quyền ghi vào thư mục upload';
                    header('Location: ' . BASE_PATH . '/admin/games');
                    exit;
                }

                $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $_SESSION['error'] = 'Chỉ chấp nhận file ảnh JPG, PNG hoặc GIF';
                    header('Location: ' . BASE_PATH . '/admin/games');
                    exit;
                }

                $newFileName = uniqid('game_') . '.' . $imageFileType;
                $targetFile = $uploadDir . $newFileName;

                error_log("Upload directory: " . $uploadDir);
                error_log("New file name: " . $newFileName);
                error_log("Target file: " . $targetFile);

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    // Xóa ảnh cũ nếu có
                    if (!empty($game['image']) && file_exists(ROOT_PATH . $game['image'])) {
                        error_log("Deleting old image: " . ROOT_PATH . $game['image']);
                        unlink(ROOT_PATH . $game['image']);
                    }
                    $updateData['image'] = '/public/images/games/' . $newFileName;
                    error_log("New image path saved to database: " . $updateData['image']);
                } else {
                    error_log("Failed to move uploaded file. Upload error code: " . $_FILES['image']['error']);
                    $_SESSION['error'] = 'Không thể upload ảnh';
                    header('Location: ' . BASE_PATH . '/admin/games');
                    exit;
                }
            }

            // Cập nhật game
            if ($this->gameModel->updateGame($id, $updateData)) {
                $_SESSION['success'] = 'Cập nhật game thành công';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật game';
            }

        } catch (\Exception $e) {
            error_log("Error in editGame: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật game';
        }

        header('Location: ' . BASE_PATH . '/admin/games');
        exit;
    }

    public function editAccount($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate required fields
                $requiredFields = ['game_id', 'title', 'username', 'password', 'basic_description', 'price'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc";
                        header('Location: ' . BASE_PATH . '/admin/accounts');
                        return;
                    }
                }

                // Validate game_id exists
                $game = $this->gameModel->getGameById($_POST['game_id']);
                if (!$game) {
                    $_SESSION['error'] = "Game không tồn tại";
                    header('Location: ' . BASE_PATH . '/admin/accounts');
                    return;
                }

                // Validate price is numeric and non-negative
                if (!is_numeric($_POST['price']) || $_POST['price'] < 0) {
                    $_SESSION['error'] = "Giá phải là số và không được âm";
                    header('Location: ' . BASE_PATH . '/admin/accounts');
                    return;
                }

                // Get current account data
                $currentAccount = $this->accountModel->getAccountById($id);
                if (!$currentAccount) {
                    $_SESSION['error'] = "Tài khoản không tồn tại";
                    header('Location: ' . BASE_PATH . '/admin/accounts');
                    return;
                }

                // Handle image uploads
                $uploadDir = ROOT_PATH . '/public/images/accounts/';
                $webPath = '/public/images/accounts/';
                $imageData = json_decode($currentAccount['images'], true) ?? [
                    'main' => '/public/images/default-game.png',
                    'sub' => []
                ];

                // Handle main image upload if new image is provided
                if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                    $mainImage = $_FILES['main_image'];
                    $mainImageType = strtolower(pathinfo($mainImage['name'], PATHINFO_EXTENSION));
                    
                    if (!in_array($mainImageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $_SESSION['error'] = "Ảnh chính phải là định dạng JPG, PNG hoặc GIF";
                        header('Location: ' . BASE_PATH . '/admin/accounts');
                        return;
                    }

                    $mainImageName = uniqid('main_') . '.' . $mainImageType;
                    $mainImagePath = $uploadDir . $mainImageName;

                    if (move_uploaded_file($mainImage['tmp_name'], $mainImagePath)) {
                        // Delete old main image if it exists and is not the default image
                        if ($imageData['main'] !== '/public/images/default-game.png' 
                            && file_exists(ROOT_PATH . $imageData['main'])) {
                            unlink(ROOT_PATH . $imageData['main']);
                        }
                        $imageData['main'] = $webPath . $mainImageName;
                    }
                }

                // Handle sub-images upload if new images are provided
                if (isset($_FILES['sub_images']) && !empty($_FILES['sub_images']['name'][0])) {
                    $subImages = $_FILES['sub_images'];
                    foreach ($subImages['name'] as $key => $name) {
                        if ($subImages['error'][$key] === UPLOAD_ERR_OK) {
                            $imageType = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            
                            if (in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                $imageName = uniqid('sub_') . '.' . $imageType;
                                $imagePath = $uploadDir . $imageName;

                                if (move_uploaded_file($subImages['tmp_name'][$key], $imagePath)) {
                                    $imageData['sub'][] = $webPath . $imageName;
                                }
                            }
                        }
                    }
                }

                // Remove sub images if requested
                if (isset($_POST['remove_sub_images']) && is_array($_POST['remove_sub_images'])) {
                    foreach ($_POST['remove_sub_images'] as $index) {
                        if (isset($imageData['sub'][$index])) {
                            // Delete the file
                            $filePath = ROOT_PATH . $imageData['sub'][$index];
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                            // Remove from array
                            unset($imageData['sub'][$index]);
                        }
                    }
                    // Reindex array
                    $imageData['sub'] = array_values($imageData['sub']);
                }

                // Prepare account data for update
                $accountData = [
                    'game_id' => $_POST['game_id'],
                    'title' => $_POST['title'],
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                    'basic_description' => $_POST['basic_description'],
                    'detailed_description' => $_POST['detailed_description'] ?? null,
                    'description' => $_POST['description'] ?? null,
                    'price' => $_POST['price'],
                    'status' => $_POST['status'] ?? 'available',
                    'images' => json_encode($imageData)
                ];

                // Update account
                if ($this->accountModel->updateAccount($id, $accountData)) {
                    $_SESSION['success'] = "Cập nhật tài khoản thành công";
                } else {
                    $_SESSION['error'] = "Không thể cập nhật tài khoản";
                }
            } else {
                // GET request - show edit form
                $account = $this->accountModel->getAccountById($id);
                if (!$account) {
                    $_SESSION['error'] = "Tài khoản không tồn tại";
                    header('Location: ' . BASE_PATH . '/admin/accounts');
                    return;
                }
                
                $games = $this->gameModel->getAllGames();
                require_once ROOT_PATH . '/App/views/admin/edit_account.php';
                return;
            }
        } catch (\Exception $e) {
            error_log("Error in editAccount: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = "Đã xảy ra lỗi khi cập nhật tài khoản";
        }

        header('Location: ' . BASE_PATH . '/admin/accounts');
        exit;
    }
} 