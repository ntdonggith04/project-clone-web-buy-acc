<?php

namespace App\Models;

class Cart {
    private $items = [];
    private $total = 0;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['cart'])) {
            $this->items = $_SESSION['cart']['items'] ?? [];
            $this->total = $_SESSION['cart']['total'] ?? 0;
        } else {
            $_SESSION['cart'] = [
                'items' => [],
                'total' => 0
            ];
        }
    }

    public function add($account) {
        $id = $account['id'];

        // Kiểm tra nếu account đã có trong giỏ hàng
        if (isset($this->items[$id])) {
            throw new \Exception('Account đã có trong giỏ hàng');
        }

        // Thêm account vào giỏ hàng
        $this->items[$id] = [
            'id' => $account['id'],
            'game_id' => $account['game_id'],
            'game_name' => $account['game_name'] ?? '',
            'username' => $account['username'],
            'price' => $account['price'],
            'added_at' => date('Y-m-d H:i:s')
        ];

        // Cập nhật tổng tiền
        $this->total += $account['price'];

        // Lưu vào session
        $this->save();
    }

    public function remove($id) {
        if (isset($this->items[$id])) {
            $this->total -= $this->items[$id]['price'];
            unset($this->items[$id]);
            $this->save();
        }
    }

    public function clear() {
        $this->items = [];
        $this->total = 0;
        $this->save();
    }

    public function getItems() {
        return array_values($this->items);
    }

    public function getTotal() {
        return $this->total;
    }

    public function getCount() {
        return count($this->items);
    }

    public function has($id) {
        return isset($this->items[$id]);
    }

    public function isEmpty() {
        return empty($this->items);
    }

    private function save() {
        $_SESSION['cart'] = [
            'items' => $this->items,
            'total' => $this->total
        ];
    }
} 