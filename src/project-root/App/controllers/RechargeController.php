<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Transaction;

class RechargeController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện nạp tiền';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $this->view('recharge');
    }

    public function process() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện nạp tiền';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_PATH . '/recharge');
            exit;
        }

        $amount = isset($_POST['custom_amount']) && !empty($_POST['custom_amount']) 
            ? (int)$_POST['custom_amount'] 
            : (int)$_POST['selected_amount'];

        $paymentMethod = $_POST['payment_method'];

        if ($amount < 10000) {
            $_SESSION['error'] = 'Số tiền nạp tối thiểu là 10.000đ';
            header('Location: ' . BASE_PATH . '/recharge');
            exit;
        }

        // Calculate bonus based on amount
        $bonus = $this->calculateBonus($amount);
        $totalAmount = $amount + $bonus;

        // Create transaction record
        $transaction = new Transaction();
        $transaction->user_id = $_SESSION['user_id'];
        $transaction->amount = $amount;
        $transaction->bonus = $bonus;
        $transaction->total_amount = $totalAmount;
        $transaction->payment_method = $paymentMethod;
        $transaction->status = 'pending';
        $transaction->created_at = date('Y-m-d H:i:s');

        if (!$transaction->save()) {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại sau';
            header('Location: ' . BASE_PATH . '/recharge');
            exit;
        }

        // Redirect to appropriate payment gateway based on method
        switch ($paymentMethod) {
            case 'bank':
                header('Location: ' . BASE_PATH . '/recharge/bank/' . $transaction->id);
                break;
            case 'momo':
                header('Location: ' . BASE_PATH . '/recharge/momo/' . $transaction->id);
                break;
            case 'zalopay':
                header('Location: ' . BASE_PATH . '/recharge/zalopay/' . $transaction->id);
                break;
            default:
                $_SESSION['error'] = 'Phương thức thanh toán không hợp lệ';
                header('Location: ' . BASE_PATH . '/recharge');
                break;
        }
        exit;
    }

    public function bank($transactionId) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện nạp tiền';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $transaction = Transaction::findById($transactionId);
        if (!$transaction || $transaction->user_id !== $_SESSION['user_id']) {
            $_SESSION['error'] = 'Giao dịch không tồn tại hoặc không hợp lệ';
            header('Location: ' . BASE_PATH . '/recharge');
            exit;
        }

        $bankInfo = [
            'bank_name' => 'Vietcombank',
            'account_number' => '1234567890',
            'account_name' => 'NGUYEN VAN A',
            'branch' => 'Ho Chi Minh',
            'transfer_content' => 'NAP' . $transaction->id
        ];

        $this->view('recharge/bank', [
            'transaction' => $transaction,
            'bankInfo' => $bankInfo
        ]);
    }

    public function momo($transactionId) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện nạp tiền';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $transaction = Transaction::findById($transactionId);
        if (!$transaction || $transaction->user_id !== $_SESSION['user_id']) {
            $_SESSION['error'] = 'Giao dịch không tồn tại hoặc không hợp lệ';
            header('Location: ' . BASE_PATH . '/recharge');
            exit;
        }

        // TODO: Integrate with MoMo API
        // For now, just show QR code and phone number
        $momoInfo = [
            'phone' => '0123456789',
            'name' => 'NGUYEN VAN A',
            'transfer_content' => 'NAP' . $transaction->id
        ];

        $this->view('recharge/momo', [
            'transaction' => $transaction,
            'momoInfo' => $momoInfo
        ]);
    }

    public function zalopay($transactionId) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện nạp tiền';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $transaction = Transaction::findById($transactionId);
        if (!$transaction || $transaction->user_id !== $_SESSION['user_id']) {
            $_SESSION['error'] = 'Giao dịch không tồn tại hoặc không hợp lệ';
            header('Location: ' . BASE_PATH . '/recharge');
            exit;
        }

        // TODO: Integrate with ZaloPay API
        // For now, just show QR code and phone number
        $zaloPayInfo = [
            'phone' => '0123456789',
            'name' => 'NGUYEN VAN A',
            'transfer_content' => 'NAP' . $transaction->id
        ];

        $this->view('recharge/zalopay', [
            'transaction' => $transaction,
            'zaloPayInfo' => $zaloPayInfo
        ]);
    }

    private function calculateBonus($amount) {
        $bonusRates = [
            2000000 => 0.25, // 25% bonus for 2M+
            1000000 => 0.20, // 20% bonus for 1M+
            500000 => 0.15,  // 15% bonus for 500k+
            200000 => 0.10,  // 10% bonus for 200k+
            100000 => 0.05,  // 5% bonus for 100k+
            0 => 0           // 0% bonus for less than 100k
        ];

        foreach ($bonusRates as $threshold => $rate) {
            if ($amount >= $threshold) {
                return (int)($amount * $rate);
            }
        }

        return 0;
    }

    public function callback() {
        // This method will handle callbacks from payment gateways
        // It should verify the payment and update transaction status
        // Then update user's balance accordingly
        
        // For testing purposes, we'll just update the transaction directly
        if (isset($_GET['transaction_id']) && isset($_GET['status'])) {
            $transaction = Transaction::findById($_GET['transaction_id']);
            if ($transaction) {
                $transaction->status = $_GET['status'];
                if ($transaction->save() && $_GET['status'] === 'completed') {
                    // Update user's balance
                    $user = User::findById($transaction->user_id);
                    if ($user) {
                        $user->balance += $transaction->total_amount;
                        $user->save();
                        
                        $_SESSION['success'] = 'Nạp tiền thành công! Số dư đã được cập nhật.';
                    }
                }
            }
        }

        header('Location: ' . BASE_PATH . '/recharge');
        exit;
    }

    public function card() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện nạp tiền';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $this->view('recharge/card');
    }

    public function processCard() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện nạp tiền';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_PATH . '/recharge/card');
            exit;
        }

        $telco = $_POST['telco'] ?? '';
        $amount = (int)($_POST['amount'] ?? 0);
        $serial = $_POST['serial'] ?? '';
        $pin = $_POST['pin'] ?? '';

        // Validate input
        if (empty($telco) || empty($amount) || empty($serial) || empty($pin)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin thẻ';
            header('Location: ' . BASE_PATH . '/recharge/card');
            exit;
        }

        // TODO: Integrate with card charging API
        // For now, we'll simulate a successful transaction
        
        // Calculate bonus
        $bonus = $this->calculateBonus($amount);
        $totalAmount = $amount + $bonus;

        // Create transaction record
        $transaction = new Transaction();
        $transaction->user_id = $_SESSION['user_id'];
        $transaction->amount = $amount;
        $transaction->bonus = $bonus;
        $transaction->total_amount = $totalAmount;
        $transaction->payment_method = 'card';
        $transaction->status = 'pending';
        $transaction->created_at = date('Y-m-d H:i:s');
        $transaction->details = json_encode([
            'telco' => $telco,
            'serial' => $serial,
            'pin' => $pin
        ]);

        if (!$transaction->save()) {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại sau';
            header('Location: ' . BASE_PATH . '/recharge/card');
            exit;
        }

        // Simulate API call response
        $success = true; // In reality, this would come from the API

        if ($success) {
            $transaction->status = 'completed';
            $transaction->save();

            // Update user's balance
            $user = User::findById($_SESSION['user_id']);
            $user->balance += $totalAmount;
            $user->save();

            $_SESSION['success'] = 'Nạp thẻ thành công! Số dư đã được cộng vào tài khoản.';
        } else {
            $transaction->status = 'failed';
            $transaction->save();
            $_SESSION['error'] = 'Thẻ không hợp lệ hoặc đã được sử dụng';
        }

        header('Location: ' . BASE_PATH . '/recharge/card');
        exit;
    }
} 