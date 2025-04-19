<?php

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Account;

class CheckoutController extends BaseController {
    private $cart;
    private $transactionModel;
    private $accountModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        
        $this->cart = new Cart();
        $this->transactionModel = new Transaction();
        $this->accountModel = new Account();
    }

    public function index() {
        $cartItems = $this->cart->getItems();
        
        if (empty($cartItems)) {
            $this->setFlash('error', 'Giỏ hàng trống');
            $this->redirect('/cart');
        }

        $total = $this->cart->getTotal();
        $user = $this->getUser();

        $this->view('checkout/index', [
            'items' => $cartItems,
            'total' => $total,
            'user' => $user
        ]);
    }

    public function process() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Invalid request method');
            }

            $cartItems = $this->cart->getItems();
            if (empty($cartItems)) {
                throw new \Exception('Giỏ hàng trống');
            }

            $user = $this->getUser();
            $total = $this->cart->getTotal();

            // Kiểm tra số dư
            if ($user['balance'] < $total) {
                throw new \Exception('Số dư không đủ');
            }

            // Bắt đầu transaction
            $this->db->beginTransaction();

            try {
                // Tạo transaction record
                $transactionId = $this->transactionModel->create([
                    'user_id' => $user['id'],
                    'amount' => $total,
                    'status' => 'completed',
                    'type' => 'purchase'
                ]);

                // Cập nhật trạng thái các account
                foreach ($cartItems as $item) {
                    $this->accountModel->update($item['id'], [
                        'status' => 'sold',
                        'buyer_id' => $user['id'],
                        'transaction_id' => $transactionId,
                        'sold_at' => date('Y-m-d H:i:s')
                    ]);
                }

                // Trừ tiền user
                $this->userModel->updateBalance($user['id'], -$total);

                // Commit transaction
                $this->db->commit();

                // Xóa giỏ hàng
                $this->cart->clear();

                $this->setFlash('success', 'Thanh toán thành công');
                $this->redirect('/checkout/success?id=' . $transactionId);

            } catch (\Exception $e) {
                $this->db->rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/checkout');
        }
    }

    public function success() {
        $transactionId = $_GET['id'] ?? null;
        
        if (!$transactionId) {
            $this->redirect('/');
        }

        $transaction = $this->transactionModel->getById($transactionId);
        
        if (!$transaction || $transaction['user_id'] !== $this->getUser()['id']) {
            $this->redirect('/');
        }

        $accounts = $this->accountModel->getByTransactionId($transactionId);

        $this->view('checkout/success', [
            'transaction' => $transaction,
            'accounts' => $accounts
        ]);
    }

    public function cancel() {
        $this->cart->clear();
        $this->setFlash('info', 'Đã hủy thanh toán');
        $this->redirect('/cart');
    }
} 