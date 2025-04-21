<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class GameAccount extends Model
{
    protected $table = 'game_accounts';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Lấy kết nối database
     * 
     * @return PDO
     */
    public function getConnection()
    {
        return $this->db;
    }

    /**
     * Lấy tất cả ảnh chụp màn hình của một tài khoản
     * 
     * @param int $accountId ID của tài khoản
     * @return array Danh sách ảnh
     */
    public function getAccountScreenshots($accountId)
    {
        try {
            $sql = "SELECT * FROM account_screenshots WHERE account_id = :account_id ORDER BY id ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':account_id', $accountId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Lỗi khi lấy ảnh chụp màn hình: " . $e->getMessage());
            return [];
        }
    }

    public function getAvailableAccounts($gameId = null)
    {
        if ($gameId) {
            return $this->where(['game_id' => $gameId, 'status' => 'available']);
        }
        return $this->where(['status' => 'available']);
    }

    public function getAccountsByGame($gameId, $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE game_id = :game_id AND status = 'available' 
                ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':game_id', $gameId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsSold($id)
    {
        return $this->update($id, ['status' => 'sold']);
    }

    public function markAsPending($id)
    {
        return $this->update($id, ['status' => 'pending']);
    }

    public function markAsAvailable($id)
    {
        return $this->update($id, ['status' => 'available']);
    }

    /**
     * Cập nhật trạng thái tài khoản game
     * 
     * @param string $status Trạng thái mới ('available', 'sold', 'pending')
     * @return bool Kết quả cập nhật
     */
    public function updateStatus($status)
    {
        if (!in_array($status, ['available', 'sold', 'pending'])) {
            return false;
        }
        
        return $this->update($this->id, ['status' => $status]);
    }

    public function searchAccounts($keyword, $limit = 10)
    {
        $sql = "SELECT ga.*, g.name as game_name 
                FROM {$this->table} ga
                JOIN games g ON ga.game_id = g.id 
                WHERE (ga.title LIKE :keyword OR g.name LIKE :keyword) 
                AND ga.status = 'available' 
                ORDER BY ga.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
     * Đếm tổng số tài khoản game
     * 
     * @return int Tổng số tài khoản game
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }
    
    /**
     * Lấy tất cả tài khoản game
     * 
     * @return array Danh sách tài khoản game
     */
    public function readAll()
    {
        $sql = "SELECT ga.*, g.name as game_name, g.image as game_image 
                FROM {$this->table} ga
                JOIN games g ON ga.game_id = g.id 
                ORDER BY ga.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy thông tin một tài khoản game
     * 
     * @return array|false Thông tin tài khoản game hoặc false nếu không tìm thấy
     */
    public function readOne()
    {
        $sql = "SELECT ga.*, g.name as game_name 
                FROM {$this->table} ga
                JOIN games g ON ga.game_id = g.id 
                WHERE ga.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả tài khoản game với phân trang
     * 
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy
     * @return array Danh sách tài khoản game
     */
    public function readAllPaginate($limit = 10, $offset = 0)
    {
        $sql = "SELECT ga.*, g.name as game_name, g.image as game_image 
                FROM {$this->table} ga
                JOIN games g ON ga.game_id = g.id 
                ORDER BY ga.created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Tìm kiếm tài khoản game với phân trang
     * 
     * @param string $keyword Từ khóa tìm kiếm
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy
     * @return array Danh sách tài khoản game
     */
    public function searchPaginate($keyword, $limit = 10, $offset = 0)
    {
        $sql = "SELECT ga.*, g.name as game_name, g.image as game_image 
                FROM {$this->table} ga
                JOIN games g ON ga.game_id = g.id 
                WHERE ga.username LIKE :keyword 
                   OR ga.description LIKE :keyword 
                   OR g.name LIKE :keyword
                ORDER BY ga.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Đếm số tài khoản game thỏa mãn từ khóa tìm kiếm
     * 
     * @param string $keyword Từ khóa tìm kiếm
     * @return int Số lượng tài khoản game
     */
    public function countBySearch($keyword)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} ga
                JOIN games g ON ga.game_id = g.id 
                WHERE ga.username LIKE :keyword 
                   OR ga.description LIKE :keyword 
                   OR g.name LIKE :keyword";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }
    
    /**
     * Đếm số lượng bản ghi theo điều kiện tùy chỉnh
     * 
     * @param string $sql Câu SQL để đếm
     * @param array $params Tham số truyền vào câu SQL
     * @return int Số lượng bản ghi
     */
    public function countWithConditions($sql, $params = [])
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
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int) ($result['total'] ?? 0);
    }
    
    /**
     * Truy vấn dữ liệu với tham số
     * 
     * @param string $sql Câu SQL
     * @param array $params Tham số truyền vào câu SQL
     * @return array Dữ liệu trả về
     */
    public function queryWithParams($sql, $params = [])
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
     * Lấy phân bố tài khoản game theo danh mục
     * 
     * @param int $limit Số lượng danh mục hiển thị (lấy các danh mục có nhiều tài khoản nhất)
     * @return array Phân bố tài khoản game theo danh mục
     */
    public function getAccountDistributionByGame($limit = 5)
    {
        $sql = "SELECT g.id, g.name, COUNT(ga.id) as account_count 
                FROM games g
                LEFT JOIN {$this->table} ga ON g.id = ga.game_id 
                GROUP BY g.id
                ORDER BY account_count DESC
                LIMIT :limit";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = [];
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($data as $row) {
            $result[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'count' => (int)$row['account_count']
            ];
        }
        
        return $result;
    }
}
?> 