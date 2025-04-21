<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Game;
use App\Models\GameAccount;

class GameController extends Controller {
    private $gameModel;
    private $gameAccountModel;

    public function __construct() {
        parent::__construct();
        $this->gameModel = new Game();
        $this->gameAccountModel = new GameAccount();
    }

    public function index() {
        try {
            // Lấy danh sách game với số lượng tài khoản
            $games = $this->gameModel->getGamesWithAccounts();
            
            $this->render('games/index', [
                'games' => $games,
                'title' => 'Danh sách game'
            ]);
        } catch (\Exception $e) {
            // Log error
            error_log("GameController error: " . $e->getMessage());
            
            // Render with empty data
            $this->render('games/index', [
                'games' => [],
                'title' => 'Danh sách game',
                'error' => 'Có lỗi xảy ra khi tải danh sách game.'
            ]);
        }
    }

    public function show($params) {
        try {
            error_log("GameController::show() called with params: " . json_encode($params));
            
            // Đảm bảo tham số có đúng định dạng
            $slug = '';
            if (is_array($params) && isset($params['slug'])) {
                $slug = $params['slug'];
            } elseif (is_string($params)) {
                $slug = $params;
            }
            
            error_log("Slug extracted: " . $slug);
            
            if (empty($slug)) {
                error_log("Slug is empty, redirecting to /games");
                $this->redirect('/games');
                return;
            }

            // Lấy thông tin game
            $game = $this->gameModel->getGameBySlug($slug);
            error_log("Game found: " . ($game ? "Yes (ID: {$game['id']})" : "No"));
            
            if (!$game) {
                error_log("Game not found, redirecting to /games");
                $this->redirect('/games');
                return;
            }

            // Lấy danh sách account của game
            $accounts = $this->gameAccountModel->getAccountsByGame($game['id']);
            error_log("Accounts found: " . count($accounts));

            // Lấy thống kê của game
            $stats = $this->gameModel->getGameStats($game['id']);
            error_log("Stats: " . json_encode($stats));

            $this->render('games/detail', [
                'game' => $game,
                'accounts' => $accounts,
                'stats' => $stats,
                'title' => $game['name']
            ]);
        } catch (\Exception $e) {
            error_log("GameController error in show(): " . $e->getMessage());
            error_log("Exception trace: " . $e->getTraceAsString());
            $this->redirect('/games');
        }
    }

    public function search() {
        try {
            $keyword = $this->getQuery('q', '');
            $games = [];

            if ($keyword) {
                $games = $this->gameModel->searchGames($keyword);
            }

            $this->render('games/search', [
                'games' => $games,
                'keyword' => $keyword,
                'title' => 'Tìm kiếm game: ' . $keyword
            ]);
        } catch (\Exception $e) {
            error_log("GameController error: " . $e->getMessage());
            
            $this->render('games/search', [
                'games' => [],
                'keyword' => $this->getQuery('q', ''),
                'title' => 'Tìm kiếm game',
                'error' => 'Có lỗi xảy ra khi tìm kiếm.'
            ]);
        }
    }
} 