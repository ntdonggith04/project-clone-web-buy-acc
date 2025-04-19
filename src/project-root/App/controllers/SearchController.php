<?php

namespace App\Controllers;

use App\Models\Game;
use App\Models\Account;

class SearchController extends BaseController {
    private $gameModel;
    private $accountModel;

    public function __construct() {
        parent::__construct();
        $this->gameModel = new Game();
        $this->accountModel = new Account();
    }

    public function index() {
        $query = $_GET['q'] ?? '';
        $type = $_GET['type'] ?? 'all';
        $results = [];

        if (!empty($query)) {
            switch ($type) {
                case 'games':
                    $results = $this->gameModel->search($query);
                    break;
                case 'accounts':
                    $results = $this->accountModel->search($query);
                    break;
                default:
                    $results = [
                        'games' => $this->gameModel->search($query),
                        'accounts' => $this->accountModel->search($query)
                    ];
            }
        }

        $this->view('search/index', [
            'query' => $query,
            'type' => $type,
            'results' => $results
        ]);
    }

    public function search() {
        $query = $_POST['query'] ?? '';
        $type = $_POST['type'] ?? 'all';
        $results = [];

        if (!empty($query)) {
            switch ($type) {
                case 'games':
                    $results = $this->gameModel->search($query);
                    break;
                case 'accounts':
                    $results = $this->accountModel->search($query);
                    break;
                default:
                    $results = [
                        'games' => $this->gameModel->search($query),
                        'accounts' => $this->accountModel->search($query)
                    ];
            }
        }

        // Nếu là AJAX request
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'results' => $results]);
            exit;
        }

        // Nếu là form submit bình thường
        $this->redirect('/search?q=' . urlencode($query) . '&type=' . $type);
    }
} 