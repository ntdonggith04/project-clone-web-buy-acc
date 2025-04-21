<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\GameAccount;
use App\Services\VNPayService;
use App\Models\Transaction;

class PaymentController extends Controller {
    private $order;
    private $gameAccount;
    private $game;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->order = new Order();
        $this->gameAccount = new GameAccount();
        $this->game = new \App\Models\Game();
    }
    
    /**
     * Xử lý thanh toán qua VNPay
     * 
     * @param int $id ID đơn hàng
     */
    public function vnpay($order_id = null) {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        // Get order details - prioritize using orderDetails from getOrderById if available
        $orderDetails = null;
        $order = new Order();
        $this->order->id = $order_id;
        
        if (method_exists($order, 'getOrderById')) {
            $orderDetails = $order->getOrderById($order_id);
        }
        
        if (!$orderDetails && !$this->order->readOne()) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        if($this->order->user_id != $_SESSION['user_id'] && $_SESSION['role'] != 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập đơn hàng này";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
        
        // Kiểm tra trạng thái đơn hàng
        if($this->order->status != 'pending') {
            $_SESSION['error'] = "Đơn hàng không ở trạng thái chờ thanh toán";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
        
        // Lấy thông tin thanh toán
        $amount = $orderDetails ? $orderDetails['amount'] : $this->order->amount;
        $orderInfo = "Thanh toan don hang #{$order_id}";
        
        // VNPay configuration
        $vnp_TmnCode = "YOUR_MERCHANT_CODE"; // Replace with your VNPay merchant code
        $vnp_HashSecret = "YOUR_HASH_SECRET"; // Replace with your VNPay hash secret
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = BASE_URL . "payment/vnpay-return";
        
        // Payment request data
        $vnp_TxnRef = $order_id . '-' . time(); // Use order_id and timestamp as transaction reference
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $amount * 100; // Amount in VND, convert to smallest currency unit
        $vnp_Locale = "vn";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        
        // Build payment URL
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $orderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        // Remove last '&' character
        $query = substr($query, 0, -1);
        
        // Create secure hash
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
        }
        
        // Store transaction information if Transaction class is available
        if (class_exists('App\\Models\\Transaction')) {
            $transaction = new \App\Models\Transaction();
            if (method_exists($transaction, 'createTransaction')) {
                $transaction->createTransaction([
                    'order_id' => $order_id,
                    'amount' => $amount,
                    'payment_method' => 'vnpay',
                    'transaction_code' => $vnp_TxnRef,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        // Tạo URL thanh toán VNPay hoặc sử dụng service nếu có
        if (class_exists('App\\Services\\VNPayService') && method_exists('App\\Services\\VNPayService', 'createPaymentUrl')) {
            $vnpayUrl = VNPayService::createPaymentUrl($order_id, $amount, $orderInfo);
            // Chuyển hướng đến trang thanh toán VNPay
            header('Location: ' . $vnpayUrl);
        } else {
            // Chuyển hướng đến trang thanh toán VNPay
            header('Location: ' . $vnp_Url);
        }
        exit;
    }
    
    /**
     * Xử lý callback từ VNPay
     */
    public function vnpayReturn() {
        // Kiểm tra dữ liệu từ VNPay
        if(isset($_GET['vnp_ResponseCode'])) {
            $vnpayData = $_GET;
            $orderId = $_GET['vnp_TxnRef'];
            
            // Xác thực chữ ký từ VNPay
            $isValidSignature = VNPayService::verifyPayment($vnpayData);
            
            if($isValidSignature) {
                // Nếu thanh toán thành công (mã 00)
                if($_GET['vnp_ResponseCode'] == '00') {
                    // Cập nhật trạng thái đơn hàng
                    $this->order->id = $orderId;
                    if($this->order->readOne()) {
                        $this->order->status = 'paid';
                        $this->order->payment_status = 'paid';
                        $transaction_code = $_GET['vnp_TransactionNo'] ?? '';
                        
                        // Cập nhật trạng thái và mã giao dịch
                        if($this->order->updateStatus() && 
                           $this->order->updatePaymentStatus() && 
                           $this->order->updateTransaction($transaction_code)) {
                            $_SESSION['success'] = "Thanh toán thành công";
                        } else {
                            $_SESSION['error'] = "Thanh toán thành công nhưng cập nhật trạng thái đơn hàng thất bại";
                        }
                    } else {
                        $_SESSION['error'] = "Không tìm thấy đơn hàng";
                    }
                } else {
                    // Thanh toán thất bại hoặc bị hủy
                    $_SESSION['error'] = "Thanh toán thất bại hoặc đã bị hủy. Mã lỗi: " . $_GET['vnp_ResponseCode'];
                }
            } else {
                // Chữ ký không hợp lệ
                $_SESSION['error'] = "Dữ liệu không hợp lệ";
            }
            
            // Chuyển hướng về trang chi tiết đơn hàng
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        } else {
            // Không nhận được dữ liệu từ VNPay
            $_SESSION['error'] = "Không nhận được dữ liệu thanh toán";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
    }
    
    /**
     * Trực tiếp đến trang thanh toán
     * 
     * @param int $order_id ID đơn hàng
     */
    public function checkout($order_id = null) {
        // Kiểm tra người dùng đã đăng nhập chưa
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Nếu không có order_id, chuyển về trang chủ
        if(!$order_id) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header('Location: ' . BASE_URL);
            exit;
        }

        // Lấy thông tin đơn hàng
        $this->order->id = $order_id;
        $order = $this->order->getOrderDetails($order_id);

        // Kiểm tra đơn hàng tồn tại và thuộc về người dùng hiện tại
        if(!$order || $order['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Bạn không có quyền truy cập đơn hàng này";
            header('Location: ' . BASE_URL);
            exit;
        }

        // Kiểm tra trạng thái đơn hàng, nếu đã thanh toán thì chuyển đến trang thông báo
        if($order['payment_status'] == 'completed') {
            $_SESSION['success'] = "Đơn hàng đã được thanh toán thành công";
            header('Location: ' . BASE_URL . 'orders');
            exit;
        }

        // Lấy thông tin tài khoản game
        $game_account = null;
        if (isset($order['game_account_id'])) {
            $this->gameAccount->id = $order['game_account_id'];
            $game_account = $this->gameAccount->readOne();
        }

        // Lấy thông tin game
        $game = null;
        if ($game_account && isset($game_account['game_id'])) {
            $this->game->id = $game_account['game_id'];
            $game_id = $game_account['game_id'];
            
            // Use a generic query to get game by ID
            $sql = "SELECT * FROM games WHERE id = :id LIMIT 1";
            $params = [':id' => $game_id];
            $result = $this->game->query($sql, $params);
            $game = !empty($result) ? $result[0] : null;
        }

        // Chuẩn bị dữ liệu cho view
        $data = [
            'order' => $order,
            'game_account' => $game_account,
            'game' => $game,
            'title' => 'Thanh toán',
            'current_page' => 'checkout'
        ];

        // Render the view
        $this->view->render('orders/checkout', $data);
    }
    
    /**
     * Hiển thị trang sandbox thanh toán
     * 
     * @param int $id ID đơn hàng
     */
    public function sandbox($id) {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        $this->order->id = $id;
        if(!$this->order->readOne()) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        if($this->order->user_id != $_SESSION['user_id'] && $_SESSION['role'] != 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập đơn hàng này";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
        
        // Hiển thị trang giả lập thanh toán
        $this->view->render('orders/sandbox', [
            'order' => $this->order,
            'callback_url' => BASE_URL . 'payment/sandbox-complete/' . $id
        ]);
    }
    
    /**
     * Xử lý hoàn thành thanh toán sandbox
     * 
     * @param int $id ID đơn hàng
     */
    public function sandboxComplete($id) {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        $this->order->id = $id;
        if(!$this->order->readOne()) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        if($this->order->user_id != $_SESSION['user_id'] && $_SESSION['role'] != 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập đơn hàng này";
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }
        
        // Cập nhật trạng thái đơn hàng
        $this->order->status = 'paid';
        $this->order->payment_status = 'paid';
        $transaction_code = 'SANDBOX_' . time();
        
        // Cập nhật trạng thái và mã giao dịch
        if($this->order->updateStatus() && 
           $this->order->updatePaymentStatus() && 
           $this->order->updateTransaction($transaction_code)) {
            $_SESSION['success'] = "Thanh toán thành công (Sandbox)";
        } else {
            $_SESSION['error'] = "Thanh toán thành công nhưng cập nhật trạng thái đơn hàng thất bại";
        }
        
        // Chuyển hướng về trang danh sách tài khoản
        header('Location: ' . BASE_URL . 'accounts');
        exit;
    }

    public function process($order_id = null)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Check if order_id is provided
        if (!$order_id) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header('Location: ' . BASE_URL);
            exit;
        }

        // Get order details
        $order = new Order();
        $order->id = $order_id;
        $orderDetails = $order->getOrderDetails($order_id);

        // Verify order exists and belongs to the current user
        if (!$orderDetails || $orderDetails['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng hoặc đơn hàng không thuộc về bạn";
            header('Location: ' . BASE_URL);
            exit;
        }

        // If order is already paid, redirect to orders page
        if ($orderDetails['payment_status'] == 'completed') {
            $_SESSION['success'] = "Đơn hàng này đã được thanh toán";
            header('Location: ' . BASE_URL . 'user/orders');
            exit;
        }

        // Get payment method from POST
        $payment_method = $_POST['payment_method'] ?? 'vnpay';

        // Update the order payment method
        $order->updatePaymentMethod($order_id, $payment_method);

        // Based on payment method, redirect to appropriate payment gateway
        switch ($payment_method) {
            case 'vnpay':
                $this->vnpay($order_id);
                break;
            case 'momo':
                $this->momo($order_id);
                break;
            case 'bank':
                $this->bankTransfer($order_id);
                break;
            default:
                $_SESSION['error'] = "Phương thức thanh toán không hợp lệ";
                header('Location: ' . BASE_URL . 'payment/checkout/' . $order_id);
                exit;
                break;
        }
    }

    private function momo($order_id)
    {
        // Get order details
        $order = new Order();
        $order->id = $order_id;
        $orderDetails = $order->getOrderDetails($order_id);
        
        if (!$orderDetails) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header('Location: ' . BASE_URL);
            exit;
        }

        // For demo purposes, we'll redirect to a simulated MoMo payment page
        // In a real application, this would integrate with the MoMo API
        
        // Store transaction information
        $transaction = new Transaction();
        $transactionCode = 'MOMO-' . $order_id . '-' . time();
        $transaction->createTransaction([
            'order_id' => $order_id,
            'amount' => $orderDetails['amount'],
            'payment_method' => 'momo',
            'transaction_code' => $transactionCode,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Redirect to sandbox page with order information
        $_SESSION['payment_method'] = 'momo';
        $_SESSION['transaction_code'] = $transactionCode;
        header('Location: ' . BASE_URL . 'payment/sandbox/' . $order_id);
        exit;
    }

    private function bankTransfer($order_id)
    {
        // Get order details
        $order = new Order();
        $order->id = $order_id;
        $orderDetails = $order->getOrderDetails($order_id);
        
        if (!$orderDetails) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header('Location: ' . BASE_URL);
            exit;
        }

        // Store transaction information
        $transaction = new Transaction();
        $transactionCode = 'BANK-' . $order_id . '-' . time();
        $transaction->createTransaction([
            'order_id' => $order_id,
            'amount' => $orderDetails['amount'],
            'payment_method' => 'bank',
            'transaction_code' => $transactionCode,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Redirect to bank transfer information page
        $_SESSION['transaction_code'] = $transactionCode;
        header('Location: ' . BASE_URL . 'payment/bank-info/' . $order_id);
        exit;
    }

    public function vnpay_return()
    {
        require_once ROOT_DIR . '/src/config/vnpay_config.php';
        $inputData = array();
        $returnData = array();

        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch thanh toán
        $vnp_Amount = $inputData['vnp_Amount'] / 100; // Số tiền thanh toán
        $orderId = $inputData['vnp_TxnRef']; // ID của order đã được tạo trước đó

        try {
            // Lấy thông tin đơn hàng
            $orderModel = new Order();
            $order = $orderModel->getOrderById($orderId);
            
            if ($order) {
                if ($secureHash == $vnp_SecureHash) {
                    $orderInfo = $order['note'] ?? "Thanh toán cho đơn hàng #$orderId";
                    if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                        // Cập nhật trạng thái đơn hàng
                        $orderModel->updateStatus($orderId, 'paid');
                        $orderModel->updatePaymentStatus($orderId, 'completed');
                        
                        // Tạo giao dịch mới
                        $transactionModel = new Transaction();
                        $transactionData = [
                            'user_id' => $_SESSION['user']['id'],
                            'amount' => $vnp_Amount,
                            'type' => 'purchase',
                            'status' => 'completed',
                            'description' => $orderInfo,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        $transactionId = $transactionModel->create($transactionData);
                        
                        if ($transactionId) {
                            error_log("Transaction created successfully: $transactionId");
                        } else {
                            error_log("Failed to create transaction for order: $orderId");
                        }
                        
                        $returnData['RspCode'] = '00';
                        $returnData['Message'] = 'Thanh toán thành công!';
                    } else {
                        $returnData['RspCode'] = '01';
                        $returnData['Message'] = 'Thanh toán thất bại hoặc đã bị hủy!';
                        $orderModel->updateStatus($orderId, 'cancelled');
                        $orderModel->updatePaymentStatus($orderId, 'failed');
                    }
                } else {
                    $returnData['RspCode'] = '97';
                    $returnData['Message'] = 'Chữ ký không hợp lệ!';
                }
            } else {
                $returnData['RspCode'] = '01';
                $returnData['Message'] = 'Không tìm thấy đơn hàng!';
            }
        } catch (Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Lỗi không xác định!';
            error_log("Payment return error: " . $e->getMessage());
        }

        $this->render('orders/payment_return', [
            'data' => $returnData,
            'code' => $returnData['RspCode'],
            'message' => $returnData['Message'],
            'order_id' => $orderId ?? null
        ]);
    }
}
?> 