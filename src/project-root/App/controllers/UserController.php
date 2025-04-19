<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controller;

class UserController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function index() {
        // Kiểm tra quyền admin
        if (!$this->isAdmin()) {
            $this->redirect('/');
            return;
        }

        // Lấy các tham số tìm kiếm từ URL
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $role = isset($_GET['role']) ? trim($_GET['role']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Tính offset cho phân trang
        $offset = ($page - 1) * $limit;

        // Lấy tổng số bản ghi theo điều kiện tìm kiếm
        $totalRecords = $this->userModel->countUsers($search, $role, $status);
        $totalPages = ceil($totalRecords / $limit);

        // Đảm bảo page không vượt quá tổng số trang
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        // Lấy danh sách người dùng theo điều kiện tìm kiếm và phân trang
        $users = $this->userModel->getUsers($search, $role, $status, $limit, $offset);

        // Dữ liệu phân trang
        $pagination = [
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'limit' => $limit,
            'start' => $offset
        ];

        $this->view('admin/users', [
            'users' => $users,
            'pagination' => $pagination
        ]);
    }

    public function edit($id) {
        if (!$this->isAdmin()) {
            $this->redirect('/');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Khởi tạo mảng data chỉ với các trường cơ bản
            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => isset($_POST['role']) ? trim($_POST['role']) : 'user'
            ];

            // Validate dữ liệu
            if (empty($data['username']) || empty($data['email'])) {
                $_SESSION['error'] = 'Username và email không được để trống';
                $this->redirect('/admin/users');
                return;
            }

            // Kiểm tra email hợp lệ
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Email không hợp lệ';
                $this->redirect('/admin/users');
                return;
            }

            // Kiểm tra mật khẩu mới
            if (!empty($_POST['password'])) {
                if (strlen($_POST['password']) < 6) {
                    $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                    $this->redirect('/admin/users');
                    return;
                }
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            try {
                if ($this->userModel->updateUser($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật thông tin người dùng thành công';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thông tin người dùng';
                }
            } catch (\Exception $e) {
                error_log("Error updating user: " . $e->getMessage());
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thông tin người dùng';
            }
        }

        $this->redirect('/admin/users');
    }

    public function get($id) {
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo json_encode(['error' => 'Không có quyền truy cập']);
            return;
        }

        $user = $this->userModel->getUserById($id);
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Không tìm thấy người dùng']);
        }
    }

    public function toggleStatus($id) {
        if (!$this->isAdmin()) {
            $this->redirect('/');
            return;
        }

        $user = $this->userModel->getUserById($id);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy người dùng';
            $this->redirect('/admin/users');
            return;
        }

        // Không cho phép khóa tài khoản admin
        if ($user['role'] === 'admin') {
            $_SESSION['error'] = 'Không thể khóa tài khoản admin';
            $this->redirect('/admin/users');
            return;
        }

        if ($this->userModel->toggleStatus($id)) {
            $_SESSION['success'] = 'Cập nhật trạng thái người dùng thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật trạng thái người dùng';
        }

        $this->redirect('/admin/users');
    }

    public function delete($id) {
        // Kiểm tra AJAX request
        if (!$this->isAjax()) {
            $this->redirect('/admin/users');
            return;
        }

        // Đảm bảo không có output nào trước khi gửi response
        ob_clean();
        
        header('Content-Type: application/json');
        
        try {
            error_log("Delete method called with ID: " . $id);
            
            if (!$this->isAdmin()) {
                error_log("User is not admin");
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không có quyền truy cập'
                ]);
                exit;
            }

            $user = $this->userModel->getUserById($id);
            error_log("User data: " . print_r($user, true));
            
            if (!$user) {
                error_log("User not found");
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ]);
                exit;
            }

            // Không cho phép xóa tài khoản admin
            if ($user['role'] === 'admin') {
                error_log("Cannot delete admin user");
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể xóa tài khoản admin'
                ]);
                exit;
            }

            $result = $this->userModel->deleteUser($id);
            error_log("Delete result: " . ($result ? "success" : "failed"));
            
            if ($result) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Xóa người dùng thành công',
                    'userId' => $id
                ]);
            } else {
                error_log("Delete operation returned false");
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi xóa người dùng'
                ]);
            }
        } catch (\Exception $e) {
            error_log("Exception in delete method: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    private function isAdmin() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
} 