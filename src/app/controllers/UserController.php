<?php
namespace App\Controllers;

use App\Models\User;

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    // Hiển thị trang dashboard
    public function dashboard() {
        // Lấy thông tin người dùng
        $user = $this->userModel->find($_SESSION['user_id']);
        
        // Lấy đơn hàng gần đây
        $orderModel = new \App\Models\Order();
        $recentOrders = $orderModel->getRecentOrdersByUser($_SESSION['user_id'], 5);
        $orderCount = $orderModel->countOrdersByUser($_SESSION['user_id']);
        
        // Lấy giao dịch gần đây
        $transactionModel = new \App\Models\Transaction();
        $recentTransactions = $transactionModel->getTransactionsByUser($_SESSION['user_id'], 5);
        $transactionCount = count($recentTransactions);
        
        // Hiển thị view
        require_once __DIR__ . '/../views/users/dashboard.php';
    }

    // Hiển thị trang hồ sơ người dùng
    public function profile() {
        // Lấy thông tin người dùng
        $user = $this->userModel->find($_SESSION['user_id']);
        
        // Hiển thị view
        require_once __DIR__ . '/../views/users/profile.php';
    }

    // Cập nhật thông tin cá nhân
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            
            // Xử lý upload avatar nếu có
            $avatar = isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0 
                ? $this->uploadAvatar($_FILES['avatar']) 
                : null;
            
            // Dữ liệu cập nhật
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];
            
            // Chỉ cập nhật avatar nếu có
            if ($avatar) {
                $data['avatar'] = $avatar;
            }
            
            // Cập nhật thông tin
            if ($this->userModel->update($userId, $data)) {
                $_SESSION['success'] = 'Cập nhật thông tin thành công';
            } else {
                $_SESSION['error'] = 'Cập nhật thông tin thất bại';
            }
            
            // Chuyển hướng về trang hồ sơ
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }
    }

    // Cập nhật thông tin thanh toán
    public function updatePayment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            
            $data = [
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];
            
            if ($this->userModel->update($userId, $data)) {
                $_SESSION['success'] = 'Cập nhật thông tin thanh toán thành công';
            } else {
                $_SESSION['error'] = 'Cập nhật thông tin thanh toán thất bại';
            }
            
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }
    }

    // Đổi mật khẩu
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            // Kiểm tra mật khẩu hiện tại
            $user = $this->userModel->find($userId);
            if (!password_verify($currentPassword, $user['password'])) {
                $_SESSION['error'] = 'Mật khẩu hiện tại không đúng';
                header('Location: ' . BASE_URL . 'profile');
                exit;
            }
            
            // Kiểm tra mật khẩu mới và xác nhận mật khẩu
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu mới và xác nhận mật khẩu không khớp';
                header('Location: ' . BASE_URL . 'profile');
                exit;
            }
            
            // Cập nhật mật khẩu
            if ($this->userModel->updatePassword($userId, $newPassword)) {
                $_SESSION['success'] = 'Đổi mật khẩu thành công';
            } else {
                $_SESSION['error'] = 'Đổi mật khẩu thất bại';
            }
            
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }
    }

    // Xử lý upload avatar
    private function uploadAvatar($file) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        
        // Kiểm tra kích thước file
        if ($file['size'] > $maxFileSize) {
            $_SESSION['error'] = 'Kích thước file quá lớn (tối đa 2MB)';
            return false;
        }
        
        // Kiểm tra loại file
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            $_SESSION['error'] = 'Chỉ chấp nhận file hình ảnh (jpg, jpeg, png, gif)';
            return false;
        }
        
        // Tạo tên file mới để tránh trùng lặp
        $newFileName = uniqid() . '.' . $extension;
        $uploadPath = __DIR__ . '/../../public/uploads/avatars/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadPath . $newFileName)) {
            return $newFileName;
        }
        
        $_SESSION['error'] = 'Có lỗi xảy ra khi upload file';
        return false;
    }
}
?> 