<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Game extends Model
{
    protected $table = 'games';
    protected $primaryKey = 'id';
    
    // Add properties to fix dynamic property deprecation
    public $id;
    public $name;
    public $slug;
    public $description;
    public $image;
    public $price_range;
    public $total_accounts;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        parent::__construct();
    }

    public function getGamesWithAccounts()
    {
        $sql = "SELECT g.*, COUNT(ga.id) as account_count 
                FROM {$this->table} g 
                LEFT JOIN game_accounts ga ON g.id = ga.game_id 
                WHERE g.status = 1
                GROUP BY g.id";
        return $this->query($sql);
    }

    public function getActiveGames()
    {
        return $this->where(['status' => 1]);
    }

    public function countTotalAccounts()
    {
        $sql = "SELECT COUNT(*) as total FROM game_accounts WHERE status = 'available'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function countTotalGames()
    {
        return count($this->where(['status' => 1]));
    }

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 1 ORDER BY name ASC";
        return $this->query($sql);
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin game theo slug
     * 
     * @param string $slug Slug của game
     * @return array|null Thông tin game hoặc null nếu không tìm thấy
     */
    public function getGameBySlug($slug)
    {
        error_log("getGameBySlug called with slug: " . $slug);
        
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug AND status = 1 LIMIT 1";
        error_log("SQL query: " . $sql);
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Query result: " . ($result ? json_encode($result) : "No game found"));
        
        return $result ?: null;
    }

    /**
     * Lấy thống kê của game
     * 
     * @param int $gameId ID của game
     * @return array Thống kê của game
     */
    public function getGameStats($gameId)
    {
        // Đếm số lượng tài khoản
        $sql1 = "SELECT COUNT(*) as total_accounts FROM game_accounts WHERE game_id = :game_id";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $stmt1->execute();
        $total = $stmt1->fetch(PDO::FETCH_ASSOC);
        
        // Đếm số lượng tài khoản còn trống
        $sql2 = "SELECT COUNT(*) as available_accounts FROM game_accounts WHERE game_id = :game_id AND status = 'available'";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $stmt2->execute();
        $available = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        // Tính giá trung bình
        $sql3 = "SELECT AVG(price) as avg_price FROM game_accounts WHERE game_id = :game_id AND status = 'available'";
        $stmt3 = $this->db->prepare($sql3);
        $stmt3->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $stmt3->execute();
        $avg = $stmt3->fetch(PDO::FETCH_ASSOC);
        
        return [
            'total_accounts' => $total['total_accounts'] ?? 0,
            'available_accounts' => $available['available_accounts'] ?? 0,
            'avg_price' => $avg['avg_price'] ?? 0
        ];
    }
} 