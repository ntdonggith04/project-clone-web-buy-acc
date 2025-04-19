<?php

namespace App\Controllers;

use App\Models\Game;
use App\Models\Account;
use App\Models\User;
use App\Models\Transaction;

class ApiController extends BaseController {
    private $gameModel;
    private $accountModel;
    private $userModel;
    private $transactionModel;

    public function __construct() {
        parent::__construct();
        
        $this->gameModel = new Game();
        $this->accountModel = new Account();
        $this->userModel = new User();
        $this->transactionModel = new Transaction();
    }

    public function games() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $search = $_GET['search'] ?? '';

        $games = $this->gameModel->getAll($page, $limit, $search);
        $total = $this->gameModel->getTotal($search);

        $this->jsonResponse([
            'success' => true,
            'data' => $games,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }

    public function accounts() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $game_id = $_GET['game_id'] ?? null;
        $status = $_GET['status'] ?? 'available';
        $search = $_GET['search'] ?? '';

        $accounts = $this->accountModel->getAll($page, $limit, [
            'game_id' => $game_id,
            'status' => $status,
            'search' => $search
        ]);

        $total = $this->accountModel->getTotal([
            'game_id' => $game_id,
            'status' => $status,
            'search' => $search
        ]);

        $this->jsonResponse([
            'success' => true,
            'data' => $accounts,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }

    public function stats() {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 403);
        }

        $stats = [
            'users' => [
                'total' => $this->userModel->getTotal(),
                'new_today' => $this->userModel->getTotalToday()
            ],
            'accounts' => [
                'total' => $this->accountModel->getTotal(),
                'available' => $this->accountModel->getTotalByStatus('available'),
                'sold' => $this->accountModel->getTotalByStatus('sold')
            ],
            'transactions' => [
                'total' => $this->transactionModel->getTotal(),
                'today' => $this->transactionModel->getTotalToday(),
                'week' => $this->transactionModel->getTotalThisWeek(),
                'month' => $this->transactionModel->getTotalThisMonth()
            ],
            'revenue' => [
                'total' => $this->transactionModel->getTotalRevenue(),
                'today' => $this->transactionModel->getTotalRevenueToday(),
                'week' => $this->transactionModel->getTotalRevenueThisWeek(),
                'month' => $this->transactionModel->getTotalRevenueThisMonth()
            ]
        ];

        $this->jsonResponse($stats);
    }

    public function validateUsername() {
        $username = $_POST['username'] ?? '';
        
        if (empty($username)) {
            $this->jsonResponse([
                'valid' => false,
                'message' => 'Username không được để trống'
            ]);
        }

        $exists = $this->userModel->usernameExists($username);

        $this->jsonResponse([
            'valid' => !$exists,
            'message' => $exists ? 'Username đã tồn tại' : 'Username hợp lệ'
        ]);
    }

    public function validateEmail() {
        $email = $_POST['email'] ?? '';
        
        if (empty($email)) {
            $this->jsonResponse([
                'valid' => false,
                'message' => 'Email không được để trống'
            ]);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse([
                'valid' => false,
                'message' => 'Email không hợp lệ'
            ]);
        }

        $exists = $this->userModel->emailExists($email);

        $this->jsonResponse([
            'valid' => !$exists,
            'message' => $exists ? 'Email đã tồn tại' : 'Email hợp lệ'
        ]);
    }

    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 