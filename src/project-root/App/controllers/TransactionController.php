<?php

namespace App\Controllers;

use App\Models\Transaction;
use App\Models\Account;

class TransactionController extends BaseController {
    private $transactionModel;
    private $accountModel;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        
        $this->transactionModel = new Transaction();
        $this->accountModel = new Account();
    }

    public function index() {
        $user = $this->getUser();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;

        $transactions = $this->transactionModel->getByUserId(
            $user['id'],
            $page,
            $limit
        );

        $totalTransactions = $this->transactionModel->getTotalByUserId($user['id']);
        $totalPages = ceil($totalTransactions / $limit);

        $this->view('transactions/index', [
            'transactions' => $transactions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalTransactions' => $totalTransactions
        ]);
    }

    public function show($id) {
        $user = $this->getUser();
        $transaction = $this->transactionModel->getById($id);

        if (!$transaction || $transaction['user_id'] !== $user['id']) {
            $this->setFlash('error', 'Không tìm thấy giao dịch');
            $this->redirect('/transactions');
        }

        $accounts = $this->accountModel->getByTransactionId($id);

        $this->view('transactions/show', [
            'transaction' => $transaction,
            'accounts' => $accounts
        ]);
    }

    // API methods for admin
    public function getStats() {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 403);
        }

        $stats = [
            'total' => $this->transactionModel->getTotal(),
            'today' => $this->transactionModel->getTotalToday(),
            'week' => $this->transactionModel->getTotalThisWeek(),
            'month' => $this->transactionModel->getTotalThisMonth()
        ];

        $this->jsonResponse($stats);
    }

    public function getChartData() {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 403);
        }

        $period = $_GET['period'] ?? 'month';
        $data = [];

        switch ($period) {
            case 'week':
                $data = $this->transactionModel->getWeeklyStats();
                break;
            case 'month':
                $data = $this->transactionModel->getMonthlyStats();
                break;
            case 'year':
                $data = $this->transactionModel->getYearlyStats();
                break;
        }

        $this->jsonResponse($data);
    }

    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 