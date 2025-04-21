<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\GameAccount;

class OrderController extends Controller {
    private $order;
    private $gameAccount;

    public function __construct($params = []) {
        parent::__construct($params);
        $this->order = new Order();
        $this->gameAccount = new GameAccount();
    }

    // Mua ngay - Chuyển thẳng đến thanh toán
    public function buyNow($id) {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Lấy thông tin tài khoản game từ database
        $this->gameAccount->id = $id;

        // Truy vấn trực tiếp để debug
        $sql = "SELECT ga.*, g.name as game_name, g.image as game_image 
                FROM game_accounts ga
                JOIN games g ON ga.game_id = g.id 
                WHERE ga.id = :id";
        $stmt = $this->gameAccount->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $account = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$account) {
            $_SESSION['error'] = "Không tìm thấy tài khoản game";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        // Tạo dữ liệu cho đơn hàng
        $title = $account['title'] ?? $account['game_name'] ?? 'Tài khoản game';
        $price = 0;
        
        // Xử lý giá tiền
        if (isset($account['price'])) {
            // Chuyển đổi giá thành số
            $price = str_replace(',', '', $account['price']);
            $price = (float)$price;
            
            // Nếu vẫn không có giá trị, thử đặt giá mặc định
            if ($price <= 0) {
                $price = 100000; // Giá mặc định
            }
        } else {
            $price = 100000; // Giá mặc định
        }

        // Tạo đơn hàng trực tiếp
        $this->order->user_id = $_SESSION['user_id'];
        $this->order->payment_method = 'vnpay'; // Phương thức thanh toán mặc định
        $this->order->total_amount = $price;
        $this->order->game_account_id = $id;

        // Tạo đơn hàng
        $order_id = $this->order->create();
        
        if($order_id) {
            $this->order->id = $order_id;

            // Cập nhật trạng thái tài khoản game thành đã bán
            $this->gameAccount->id = $id;
            $this->gameAccount->updateStatus('sold');

            // Chuyển hướng đến trang thanh toán
            header('Location: ' . BASE_URL . 'payment/checkout/' . $order_id);
            exit;
        } else {
            $_SESSION['error'] = "Mua hàng thất bại";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
    }

    // Thanh toán
    public function checkout() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        if(!isset($_SESSION['buy_now'])) {
            $_SESSION['error'] = "Bạn chưa chọn sản phẩm để mua";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->order->user_id = $_SESSION['user_id'];
            $this->order->payment_method = $_POST['payment_method'];

            // Lấy thông tin sản phẩm từ session
            $item = $_SESSION['buy_now'];
            
            $this->order->total_amount = $item['price'];
            $this->order->game_account_id = $item['id'];

            // Tạo đơn hàng
            $order_id = $this->order->create();
            
            if($order_id) {
                $this->order->id = $order_id;

                // Cập nhật trạng thái tài khoản game thành đã bán
                $this->gameAccount->id = $item['id'];
                $this->gameAccount->updateStatus('sold');

                // Xóa session
                unset($_SESSION['buy_now']);

                // Chuyển hướng đến trang thanh toán
                header('Location: ' . BASE_URL . 'payment/checkout/' . $order_id);
                exit;
            } else {
                $error = "Mua hàng thất bại";
                $this->view->render('orders/checkout', ['error' => $error]);
            }
        } else {
            $this->view->render('orders/checkout');
        }
    }

    // Hiển thị danh sách đơn hàng
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $stmt = $this->order->readByUser($_SESSION['user_id']);
        
        // Thêm thông tin phân trang
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $total_pages = 1;  // Mặc định 1 trang nếu không có nhiều dữ liệu
        $query_string = '';
        
        if(isset($_GET['status'])) {
            $query_string = '&status=' . $_GET['status'];
        }
        
        $this->view->render('orders/index', [
            'orders' => $stmt,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'query_string' => $query_string
        ]);
    }

    // Hiển thị chi tiết đơn hàng
    public function show($id) {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $this->order->id = $id;
        $this->order->readOne();

        // Kiểm tra quyền truy cập
        if($this->order->user_id != $_SESSION['user_id'] && $_SESSION['role'] != 'admin') {
            header('Location: ' . BASE_URL . 'orders');
            exit;
        }

        $items = $this->order->readItems();
        $this->view->render('orders/show', ['order' => $this->order, 'items' => $items]);
    }

    // Cập nhật trạng thái đơn hàng (Admin)
    public function updateStatus($id) {
        if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $this->order->id = $id;
        $this->order->status = $_POST['status'];

        if($this->order->updateStatus()) {
            $_SESSION['success'] = "Cập nhật trạng thái đơn hàng thành công";
        } else {
            $_SESSION['error'] = "Cập nhật trạng thái đơn hàng thất bại";
        }

        header('Location: ' . BASE_URL . 'admin/orders');
        exit;
    }

    // Cập nhật trạng thái thanh toán (Admin)
    public function updatePaymentStatus($id) {
        if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $this->order->id = $id;
        $this->order->payment_status = $_POST['payment_status'];

        if($this->order->updatePaymentStatus()) {
            $_SESSION['success'] = "Cập nhật trạng thái thanh toán thành công";
        } else {
            $_SESSION['error'] = "Cập nhật trạng thái thanh toán thất bại";
        }

        header('Location: ' . BASE_URL . 'admin/orders');
        exit;
    }
}
?> 