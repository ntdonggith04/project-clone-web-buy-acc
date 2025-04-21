<?php
namespace App\Models;

use App\Core\Model;

class Order extends Model {
    protected $table = 'orders';
    
    // Add properties to fix dynamic property deprecation
    public $id;
    public $user_id;
    public $payment_method;
    public $total_amount;
    public $game_account_id;
    public $status;
    public $payment_status;
    public $amount;
    public $created_at;
    public $updated_at;
    public $transaction_code;
    
    // Properties from joined tables
    public $title;
    public $rank;
    public $level;
    public $server;
    public $description;
    public $price;
    public $game_name;
    public $game_image;
    public $seller_name;
    public $seller_email;
    public $seller_phone;
    public $seller_created_at;
    public $service_fee;

    /**
     * Lấy danh sách đơn hàng của một người dùng
     * 
     * @param int $userId ID của người dùng
     * @param int $limit Số lượng đơn hàng tối đa
     * @return array Danh sách đơn hàng
     */
    public function getOrdersByUserId($userId, $limit = 10) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết một đơn hàng theo ID
     * 
     * @param int $orderId ID của đơn hàng
     * @return array|false Chi tiết đơn hàng hoặc false nếu không tìm thấy
     */
    public function getOrderDetails($orderId) {
        $sql = "SELECT o.*, g.title, g.price FROM {$this->table} o
                JOIN game_accounts g ON o.game_account_id = g.id
                WHERE o.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $orderId, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Tạo đơn hàng mới
     * 
     * @param array $orderData Dữ liệu đơn hàng
     * @return int|false ID của đơn hàng mới hoặc false nếu thất bại
     */
    public function createOrder($orderData) {
        try {
            return $this->create($orderData);
        } catch (\Exception $e) {
            // Log lỗi nếu cần
            return false;
        }
    }

    /**
     * Tạo đơn hàng từ thuộc tính của đối tượng
     * 
     * @param array|null $data Dữ liệu đơn hàng (nếu null, sử dụng thuộc tính của đối tượng)
     * @return int|false ID của đơn hàng mới hoặc false nếu thất bại
     */
    public function create($data = null)
    {
        if ($data === null) {
            $data = [
                'user_id' => $this->user_id,
                'game_account_id' => $this->game_account_id,
                'amount' => $this->total_amount,
                'payment_method' => $this->payment_method,
                'status' => 'pending',
                'payment_status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        
        try {
            error_log("Creating order with data: " . print_r($data, true));
            return parent::create($data);
        } catch (\Exception $e) {
            error_log("Error creating order: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Thêm sản phẩm vào đơn hàng
     * 
     * @param int $gameAccountId ID của tài khoản game
     * @param float $price Giá của tài khoản game
     * @return bool Kết quả thêm sản phẩm
     */
    public function addItem($gameAccountId, $price) {
        $sql = "INSERT INTO order_items (order_id, game_account_id, price) VALUES (:order_id, :game_account_id, :price)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':order_id', $this->id, \PDO::PARAM_INT);
        $stmt->bindValue(':game_account_id', $gameAccountId, \PDO::PARAM_INT);
        $stmt->bindValue(':price', $price);
        
        if($stmt->execute()) {
            // Cập nhật trạng thái tài khoản game
            $sql = "UPDATE game_accounts SET status = 'sold' WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $gameAccountId, \PDO::PARAM_INT);
            return $stmt->execute();
        }
        
        return false;
    }

    /**
     * Cập nhật trạng thái đơn hàng
     * 
     * @return bool Kết quả cập nhật
     */
    public function updateStatus() {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $this->status);
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Cập nhật trạng thái thanh toán
     * 
     * @return bool Kết quả cập nhật
     */
    public function updatePaymentStatus() {
        $sql = "UPDATE {$this->table} SET payment_status = :payment_status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':payment_status', $this->payment_status);
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Cập nhật mã giao dịch
     * 
     * @param string $transactionCode Mã giao dịch
     * @return bool Kết quả cập nhật
     */
    public function updateTransaction($transactionCode) {
        $sql = "UPDATE {$this->table} SET transaction_code = :transaction_code WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':transaction_code', $transactionCode);
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Đếm số đơn hàng của một người dùng
     * 
     * @param int $userId ID của người dùng
     * @return int Số lượng đơn hàng
     */
    public function countUserOrders($userId) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        
        return (int) $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    }

    /**
     * Đếm số đơn hàng đang chờ xử lý của một người dùng
     * 
     * @param int $userId ID của người dùng
     * @return int Số lượng đơn hàng đang chờ xử lý
     */
    public function countPendingOrders($userId) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = :user_id AND status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }

    /**
     * Tính tổng giá trị đơn hàng của một người dùng
     * 
     * @param int $userId ID của người dùng
     * @return float Tổng giá trị đơn hàng
     */
    public function getTotalSpent($userId) {
        $sql = "SELECT SUM(amount) as total FROM {$this->table} WHERE user_id = :user_id AND status IN ('completed', 'paid')";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return floatval($result['total'] ?? 0);
    }
    
    /**
     * Tính tổng doanh thu từ tất cả đơn hàng đã hoàn thành
     * 
     * @return float Tổng doanh thu
     */
    public function getTotalRevenue() {
        $sql = "SELECT SUM(amount) as total FROM {$this->table} WHERE payment_status = 'paid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total = floatval($result['total'] ?? 0);
        
        // Debug
        error_log("Total revenue query: " . $sql);
        error_log("Total revenue result: " . print_r($result, true));
        
        return $total;
    }
    
    /**
     * Đếm tổng số đơn hàng
     * 
     * @return int Tổng số đơn hàng
     */
    public function count() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }
    
    /**
     * Lấy danh sách đơn hàng theo ID người dùng
     * 
     * @param int $userId ID của người dùng
     * @return array Danh sách đơn hàng
     */
    public function readByUser($userId) {
        $sql = "SELECT o.*, ga.title, ga.game_rank as rank, ga.game_level as level, ga.game_server as server,
                       g.name as game_name, g.image as game_image
                FROM {$this->table} o
                JOIN game_accounts ga ON o.game_account_id = ga.id
                JOIN games g ON ga.game_id = g.id
                WHERE o.user_id = :user_id 
                ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Đọc chi tiết một đơn hàng
     * 
     * @return bool Kết quả đọc
     */
    public function readOne() {
        $sql = "SELECT o.*, ga.title, ga.game_rank as rank, ga.game_level as level, ga.game_server as server, 
                       ga.description, ga.price,
                       g.name as game_name, g.image as game_image,
                       u.username as seller_name, u.email as seller_email, u.phone as seller_phone,
                       u.created_at as seller_created_at
                FROM {$this->table} o
                JOIN game_accounts ga ON o.game_account_id = ga.id
                JOIN games g ON ga.game_id = g.id
                LEFT JOIN users u ON u.id = 1 /* Tạm thời lấy admin là người bán */
                WHERE o.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if($row) {
            foreach($row as $key => $value) {
                $this->$key = $value;
            }
            
            // Tính phí dịch vụ (5%)
            if (isset($row['price']) && $row['price'] > 0) {
                $this->service_fee = $row['price'] * 0.05;
            } else {
                $this->service_fee = 0;
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Đọc danh sách sản phẩm trong đơn hàng
     * 
     * @return array Danh sách sản phẩm
     */
    public function readItems() {
        $sql = "SELECT ga.*, g.name as game_name
                FROM game_accounts ga
                JOIN games g ON ga.game_id = g.id 
                WHERE ga.id = (SELECT game_account_id FROM {$this->table} WHERE id = :order_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':order_id', $this->id, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy doanh thu theo tháng trong năm hiện tại
     * 
     * @return array Doanh thu theo tháng
     */
    public function getMonthlyRevenue() {
        $currentYear = date('Y');
        $result = [];
        
        // Khởi tạo mảng doanh thu cho 12 tháng
        for ($i = 1; $i <= 12; $i++) {
            $result[$i] = 0;
        }
        
        $sql = "SELECT MONTH(created_at) as month, SUM(amount) as revenue 
                FROM {$this->table} 
                WHERE YEAR(created_at) = :year AND payment_status = 'paid' 
                GROUP BY MONTH(created_at)";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':year', $currentYear, \PDO::PARAM_STR);
        $stmt->execute();
        
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Debug
        error_log("Monthly revenue query: " . $sql);
        error_log("Monthly revenue data: " . print_r($data, true));
        
        // Cập nhật doanh thu cho các tháng
        foreach ($data as $row) {
            $month = (int)$row['month'];
            $result[$month] = floatval($row['revenue']);
        }
        
        error_log("Monthly revenue result: " . print_r($result, true));
        
        return $result;
    }
    
    /**
     * Lấy thống kê số lượng đơn hàng theo trạng thái
     * 
     * @return array Số lượng đơn hàng theo trạng thái
     */
    public function getOrderStatusStats() {
        $result = [
            'completed' => 0,
            'pending' => 0,
            'cancelled' => 0
        ];
        
        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($data as $row) {
            $status = $row['status'];
            if ($status == 'completed' || $status == 'paid') {
                $result['completed'] += (int)$row['count'];
            } elseif ($status == 'pending' || $status == 'processing') {
                $result['pending'] += (int)$row['count'];
            } elseif ($status == 'cancelled' || $status == 'failed') {
                $result['cancelled'] += (int)$row['count'];
            }
        }
        
        return $result;
    }

    /**
     * Lấy các đơn hàng gần đây của người dùng với thông tin chi tiết
     * 
     * @param int $userId ID của người dùng
     * @param int $limit Số lượng đơn hàng tối đa
     * @return array Danh sách đơn hàng gần đây
     */
    public function getRecentOrdersByUser($userId, $limit = 5) {
        $sql = "SELECT o.*, g.name as game_name, ga.username as account_username 
                FROM {$this->table} o
                JOIN game_accounts ga ON o.game_account_id = ga.id
                JOIN games g ON ga.game_id = g.id
                WHERE o.user_id = :user_id 
                ORDER BY o.created_at DESC 
                LIMIT :limit";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Đếm số đơn hàng của một người dùng
     * 
     * @param int $userId ID của người dùng
     * @return int Số lượng đơn hàng
     */
    public function countOrdersByUser($userId) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }

    /**
     * Cập nhật phương thức thanh toán cho đơn hàng
     * 
     * @param int $orderId ID của đơn hàng
     * @param string $paymentMethod Phương thức thanh toán mới
     * @return bool Kết quả cập nhật
     */
    public function updatePaymentMethod($orderId, $paymentMethod) {
        $sql = "UPDATE {$this->table} SET payment_method = :payment_method WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':payment_method', $paymentMethod, \PDO::PARAM_STR);
        $stmt->bindValue(':id', $orderId, \PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
?> 