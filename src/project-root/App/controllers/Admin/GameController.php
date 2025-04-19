<?php
namespace App\Controllers\Admin;

use App\Models\Game;

class GameController {
    private $gameModel;

    public function __construct() {
        $this->gameModel = new Game();
    }

    public function index() {
        $games = $this->gameModel->getAllGames();
        require_once ROOT_PATH . '/App/views/admin/games/index.php';
    }

    public function edit($id) {
        $game = $this->gameModel->getGameById($id);
        if (!$game) {
            $_SESSION['error'] = 'Game không tồn tại';
            header('Location: ' . BASE_PATH . '/admin/games');
            exit;
        }
        require_once ROOT_PATH . '/App/views/admin/games/edit.php';
    }

    private function normalizeImagePath($path) {
        if (empty($path)) {
            return '/images/games/default-game.png';
        }
        
        // Loại bỏ tất cả các phần đường dẫn không cần thiết
        $path = str_replace('/project-clone-web-buy-acc/src/project-root/public', '', $path);
        $path = str_replace('/project-clone-web-buy-acc/src/project-root', '', $path);
        $path = str_replace('/public', '', $path);
        $path = preg_replace('#^/+#', '/', $path); // Loại bỏ các dấu / thừa ở đầu
        
        return $path; // Trả về đường dẫn tương đối
    }

    public function update($id) {
        try {
            $game = $this->gameModel->getGameById($id);
            if (!$game) {
                throw new \Exception('Game không tồn tại');
            }

            // Chuẩn bị dữ liệu cập nhật
            $updateData = $_POST;

            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = ROOT_PATH . '/public/images/games/';  // Thư mục vật lý để lưu file
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = $game['slug'] . '.' . $extension;
                $uploadFile = $uploadDir . $filename;

                // Kiểm tra và tạo thư mục nếu chưa tồn tại
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (!is_writable($uploadDir)) {
                    $_SESSION['error'] = 'Không có quyền ghi vào thư mục upload';
                    header('Location: ' . BASE_PATH . '/admin/games');
                    exit;
                }

                // Di chuyển file tạm sang thư mục đích
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    // Xóa ảnh cũ nếu có
                    $oldImagePath = $this->normalizeImagePath($game['image']);
                    $oldImageFullPath = ROOT_PATH . '/public' . $oldImagePath;
                    error_log("Old image path: " . $game['image']);
                    error_log("Normalized old image path: " . $oldImagePath);
                    error_log("Full old image path: " . $oldImageFullPath);
                    
                    if (!empty($oldImagePath) && file_exists($oldImageFullPath)) {
                        unlink($oldImageFullPath);
                    }
                    
                    // Lưu đường dẫn tương đối vào database
                    $updateData['image'] = '/images/games/' . $filename;
                    error_log("New image path saved: " . $updateData['image']);
                } else {
                    throw new \Exception('Không thể upload ảnh');
                }
            }

            // Cập nhật thông tin game
            if ($this->gameModel->updateGame($id, $updateData)) {
                $_SESSION['success'] = 'Cập nhật game thành công';
            } else {
                throw new \Exception('Không thể cập nhật game');
            }

        } catch (\Exception $e) {
            error_log("Error in update game: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_PATH . '/admin/games/edit/' . $id);
        exit;
    }
} 