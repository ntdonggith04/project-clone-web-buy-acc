<?php

namespace App\Controllers;

use App\Models\Account;
use App\Models\Cart;

class CartController extends BaseController {
    private $accountModel;
    private $cart;

    public function __construct() {
        parent::__construct();
        $this->accountModel = new Account();
        $this->cart = new Cart();
    }

    public function index() {
        $cartItems = $this->cart->getItems();
        $total = $this->cart->getTotal();

        $this->view('cart/index', [
            'items' => $cartItems,
            'total' => $total
        ]);
    }

    public function add($id) {
        try {
            $account = $this->accountModel->getById($id);
            
            if (!$account) {
                throw new \Exception('Account không tồn tại');
            }

            if ($account['status'] !== 'available') {
                throw new \Exception('Account này không khả dụng');
            }

            $this->cart->add($account);
            
            if ($this->isAjax()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Đã thêm vào giỏ hàng',
                    'cartCount' => $this->cart->getCount()
                ]);
                exit;
            }

            $this->setFlash('success', 'Đã thêm vào giỏ hàng');
            $this->redirect('/cart');

        } catch (\Exception $e) {
            if ($this->isAjax()) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            }

            $this->setFlash('error', $e->getMessage());
            $this->redirect('/accounts/' . $id);
        }
    }

    public function remove($id) {
        $this->cart->remove($id);

        if ($this->isAjax()) {
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa khỏi giỏ hàng',
                'cartCount' => $this->cart->getCount(),
                'total' => $this->cart->getTotal()
            ]);
            exit;
        }

        $this->setFlash('success', 'Đã xóa khỏi giỏ hàng');
        $this->redirect('/cart');
    }

    public function clear() {
        $this->cart->clear();

        if ($this->isAjax()) {
            echo json_encode([
                'success' => true,
                'message' => 'Đã xóa giỏ hàng'
            ]);
            exit;
        }

        $this->setFlash('success', 'Đã xóa giỏ hàng');
        $this->redirect('/cart');
    }

    private function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
} 