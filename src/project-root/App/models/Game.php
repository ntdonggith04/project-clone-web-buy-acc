<?php
namespace App\Models;

use App\Core\Database;

class Game {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    private function normalizeImagePath($path) {
        if (empty($path)) {
            return '/images/games/default-game.png';
        }
        
        // Chỉ giữ lại phần đường dẫn tương đối
        $path = preg_replace('#^.*?(/images/games/.*)$#', '$1', $path);
        if (!$path) {
            return '/images/games/default-game.png';
        }
        
        return $path;
    }

    private function getWebPath($path) {
        // Chỉ trả về đường dẫn đã chuẩn hóa
        return $this->normalizeImagePath($path);
    }

    public function getAllGames() {
        try {
            $games = $this->db->query("SELECT id, name, slug, short_description, description, image, created_at, updated_at FROM games ORDER BY name ASC");
            
            // Đảm bảo các trường không null
            return array_map(function($game) {
                $imagePath = $this->normalizeImagePath($game['image']); // Đường dẫn tương đối
                $webPath = $this->getWebPath($game['image']); // Đường dẫn đầy đủ cho web
                
                error_log("Original image path: " . $game['image']);
                error_log("Normalized image path (relative): " . $imagePath);
                error_log("Web display path: " . $webPath);
                
                return [
                    'id' => $game['id'],
                    'name' => $game['name'],
                    'slug' => $game['slug'] ?? strtolower(str_replace(' ', '-', $game['name'])),
                    'short_description' => $game['short_description'] ?? '',
                    'description' => $game['description'] ?? '',
                    'image' => $webPath,
                    'created_at' => $game['created_at'],
                    'updated_at' => $game['updated_at']
                ];
            }, $games);
        } catch (\PDOException $e) {
            error_log("Error in Game::getAllGames: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalGames() {
        try {
            $sql = "SELECT COUNT(*) as total FROM games";
            $result = $this->db->query($sql);
            error_log("getTotalGames result: " . print_r($result, true));
            if (!is_array($result) || empty($result)) {
                return 0;
            }
            return isset($result[0]['total']) ? (int)$result[0]['total'] : 0;
        } catch (\Exception $e) {
            error_log("Error in getTotalGames: " . $e->getMessage());
            return 0;
        }
    }

    public function getGameById($id) {
        try {
            $stmt = $this->db->prepare("SELECT id, name, slug, short_description, description, image, created_at, updated_at FROM games WHERE id = ?");
            $stmt->execute([$id]);
            $game = $stmt->fetch();
            
            if ($game) {
                $imagePath = $this->normalizeImagePath($game['image']); // Đường dẫn tương đối
                $webPath = $this->getWebPath($game['image']); // Đường dẫn đầy đủ cho web
                
                error_log("Original image path: " . $game['image']);
                error_log("Normalized image path (relative): " . $imagePath);
                error_log("Web display path: " . $webPath);
                
                return [
                    'id' => $game['id'],
                    'name' => $game['name'],
                    'slug' => $game['slug'] ?? strtolower(str_replace(' ', '-', $game['name'])),
                    'short_description' => $game['short_description'] ?? '',
                    'description' => $game['description'] ?? '',
                    'image' => $webPath,
                    'created_at' => $game['created_at'],
                    'updated_at' => $game['updated_at']
                ];
            }
            return null;
        } catch (\PDOException $e) {
            error_log("Error in Game::getGameById: " . $e->getMessage());
            return null;
        }
    }

    public function getGameBySlug($slug) {
        try {
            $stmt = $this->db->prepare("SELECT id, name, slug, short_description, description, image, created_at, updated_at FROM games WHERE slug = ?");
            $stmt->execute([$slug]);
            $game = $stmt->fetch();
            
            if ($game) {
                $imagePath = $this->normalizeImagePath($game['image']); // Đường dẫn tương đối
                $webPath = $this->getWebPath($game['image']); // Đường dẫn đầy đủ cho web
                
                error_log("Original image path: " . $game['image']);
                error_log("Normalized image path (relative): " . $imagePath);
                error_log("Web display path: " . $webPath);
                
                return [
                    'id' => $game['id'],
                    'name' => $game['name'],
                    'slug' => $game['slug'] ?? strtolower(str_replace(' ', '-', $game['name'])),
                    'short_description' => $game['short_description'] ?? '',
                    'description' => $game['description'] ?? '',
                    'image' => $webPath,
                    'created_at' => $game['created_at'],
                    'updated_at' => $game['updated_at']
                ];
            }
            return null;
        } catch (\PDOException $e) {
            error_log("Error in Game::getGameBySlug: " . $e->getMessage());
            return null;
        }
    }

    public function createGame($data) {
        try {
            $sql = "INSERT INTO games (name, slug, short_description, description, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            return $this->db->query($sql, [
                $data['name'],
                $data['slug'] ?? strtolower(str_replace(' ', '-', $data['name'])),
                $data['short_description'] ?? '',
                $data['description'] ?? '',
                $data['image'] ?? '/public/images/games/default-game.png'
            ]);
        } catch (\Exception $e) {
            error_log("Error in createGame: " . $e->getMessage());
            return false;
        }
    }

    public function updateGame($id, $data) {
        try {
            $sql = "UPDATE games SET name = ?, slug = ?, short_description = ?, description = ?, image = ?, updated_at = NOW() WHERE id = ?";
            return $this->db->query($sql, [
                $data['name'],
                $data['slug'] ?? strtolower(str_replace(' ', '-', $data['name'])),
                $data['short_description'] ?? '',
                $data['description'] ?? '',
                $data['image'] ?? '/public/images/games/default-game.png',
                $id
            ]);
        } catch (\Exception $e) {
            error_log("Error in updateGame: " . $e->getMessage());
            return false;
        }
    }

    public function deleteGame($id, $force = false) {
        try {
            // Kiểm tra xem game có tồn tại không
            $game = $this->getGameById($id);
            if (!$game) {
                error_log("Cannot delete game: Game with ID {$id} not found");
                return false;
            }

            // Kiểm tra xem có tài khoản nào đang sử dụng game này không
            $sql = "SELECT COUNT(*) as count FROM accounts WHERE game_id = ?";
            $result = $this->db->query($sql, [$id]);
            
            if ($result && isset($result[0]['count']) && $result[0]['count'] > 0) {
                if ($force) {
                    // Xóa tất cả tài khoản liên quan
                    $deleteAccountsSql = "DELETE FROM accounts WHERE game_id = ?";
                    $this->db->query($deleteAccountsSql, [$id]);
                    error_log("Deleted all accounts associated with game ID {$id}");
                } else {
                    error_log("Cannot delete game: Game with ID {$id} has associated accounts");
                    return false;
                }
            }

            // Thực hiện xóa game
            $sql = "DELETE FROM games WHERE id = ?";
            $result = $this->db->query($sql, [$id]);
            
            if ($result) {
                error_log("Successfully deleted game with ID {$id}");
                return true;
            } else {
                error_log("Failed to delete game with ID {$id}");
                return false;
            }
        } catch (\Exception $e) {
            error_log("Error in deleteGame: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function addGame($data) {
        return $this->createGame($data);
    }

    public function updateGameImages() {
        try {
            $gameImages = [
                'genshin-impact' => '/public/images/games/genshin-impact.jpg',
                'honkai-star-rail' => '/public/images/games/honkai-star-rail.jpg',
                'lien-minh' => '/public/images/games/lien-minh.jpg',
                'valorant' => '/public/images/games/valorant.jpg'
            ];

            foreach ($gameImages as $slug => $imagePath) {
                $sql = "UPDATE games SET image = ? WHERE slug = ?";
                $this->db->query($sql, [$imagePath, $slug]);
                error_log("Updated image path for game {$slug}: {$imagePath}");
            }

            return true;
        } catch (\Exception $e) {
            error_log("Error in updateGameImages: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        return $this->getAllGames();
    }
} 