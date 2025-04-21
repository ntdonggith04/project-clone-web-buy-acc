<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Game;
use App\Models\Category;

class HomeController extends Controller {
    private $gameModel;
    private $categoryModel;

    public function __construct($params = []) {
        parent::__construct($params);
        $this->gameModel = new Game();
        $this->categoryModel = new Category();
    }

    public function index() {
        // Lấy danh sách game nổi bật với số lượng tài khoản
        $games = $this->gameModel->getGamesWithAccounts();

        // Lấy danh sách danh mục
        $categories = $this->categoryModel->getAllCategories();

        // Lấy thống kê
        $stats = [
            'total_accounts' => $this->gameModel->countTotalAccounts(),
            'total_games' => $this->gameModel->countTotalGames(),
            'total_users' => 0, // TODO: Thêm model User
            'total_orders' => 0 // TODO: Thêm model Order
        ];

        // Render view với dữ liệu
        $this->view->render('home/index', [
            'games' => $games,
            'categories' => $categories,
            'stats' => $stats
        ]);
    }
} 