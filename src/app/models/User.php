<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected $table = 'users';
    
    // Khai báo thuộc tính để tránh lỗi dynamic property
    public $id;
    public $username;
    public $password;
    public $email;
    public $full_name;
    public $phone;
    public $address;
    public $balance;
    public $role;
    public $status;
    public $created_at;
    public $updated_at;

    // Phương thức đăng nhập
    public function login()
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $this->username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($this->password, $user['password'])) {
            // Lưu thông tin user vào đối tượng hiện tại
            $this->id = $user['id'];
            $this->email = $user['email'];
            $this->full_name = $user['full_name'];
            $this->role = $user['role'];
            $this->status = $user['status'];
            // Các thuộc tính khác nếu cần
            
            return true;
        }
        
        return false;
    }
    
    // Kiểm tra username đã tồn tại chưa
    public function usernameExists()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $this->username);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    // Kiểm tra email đã tồn tại chưa
    public function emailExists()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    // Phương thức đăng ký
    public function register()
    {
        // Hash mật khẩu
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        
        try {
            $data = [
                'username' => $this->username,
                'password' => $hashedPassword,
                'email' => $this->email,
                'full_name' => $this->full_name,
                'phone' => $this->phone,
                'address' => $this->address,
                'role' => 'user', // Mặc định là user
                'status' => 1, // Mặc định là active
                'balance' => 0 // Mặc định là 0
            ];
            
            $this->create($data);
            return true;
        } catch (\Exception $e) {
            // Log lỗi nếu cần
            return false;
        }
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getUsersByRole($role, $limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function searchUsers($keyword, $limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username LIKE :keyword OR email LIKE :keyword OR full_name LIKE :keyword ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $keyword = "%{$keyword}%";
        $stmt->bindValue(':keyword', $keyword);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function updatePassword($id, $password)
    {
        $sql = "UPDATE {$this->table} SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function updateRole($id, $role)
    {
        $sql = "UPDATE {$this->table} SET role = :role WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Đếm tổng số người dùng
     * 
     * @return int Tổng số người dùng
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
     * Lấy danh sách tất cả người dùng
     * 
     * @return array Danh sách người dùng
     */
    public function readAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy danh sách người dùng với phân trang
     * 
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy
     * @return array Danh sách người dùng
     */
    public function readAllPaginate($limit = 10, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Tìm kiếm người dùng với phân trang
     * 
     * @param string $keyword Từ khóa tìm kiếm
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy
     * @return array Danh sách người dùng
     */
    public function searchPaginate($keyword, $limit = 10, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE username LIKE :keyword 
                   OR email LIKE :keyword 
                   OR fullname LIKE :keyword
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Đếm số người dùng thỏa mãn từ khóa tìm kiếm
     * 
     * @param string $keyword Từ khóa tìm kiếm
     * @return int Số lượng người dùng
     */
    public function countBySearch($keyword)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table}
                WHERE username LIKE :keyword 
                   OR email LIKE :keyword 
                   OR fullname LIKE :keyword";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }

    /**
     * Đếm số người dùng theo điều kiện
     *
     * @param string $where Điều kiện WHERE
     * @param array $params Tham số bind
     * @return int Số lượng người dùng
     */
    public function countWithConditions($where = '', $params = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $sql .= $where;
        
        $stmt = $this->db->prepare($sql);
        
        // Bind các tham số
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }
    
    /**
     * Lấy danh sách người dùng theo điều kiện
     *
     * @param string $where Điều kiện WHERE
     * @param array $params Tham số bind
     * @param int $limit Giới hạn số kết quả
     * @param int $offset Vị trí bắt đầu
     * @return array Danh sách người dùng
     */
    public function getWithConditions($where = '', $params = [], $limit = 10, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table}";
        $sql .= $where;
        $sql .= " ORDER BY created_at DESC";
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind các tham số
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Đếm số người dùng theo trạng thái
     *
     * @param string $status Trạng thái người dùng
     * @return int Số lượng người dùng
     */
    public function countByStatus($status)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = :status";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }
    
    /**
     * Đếm số người dùng theo vai trò
     *
     * @param string $role Vai trò người dùng
     * @return int Số lượng người dùng
     */
    public function countByRole($role)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role', $role);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }
    
    /**
     * Lấy số lượng người dùng đăng ký mới theo tháng trong năm hiện tại
     * 
     * @return array Số lượng người dùng đăng ký mới theo tháng
     */
    public function getMonthlyNewUsers()
    {
        $currentYear = date('Y');
        $result = [];
        
        // Khởi tạo mảng cho 12 tháng
        for ($i = 1; $i <= 12; $i++) {
            $result[$i] = 0;
        }
        
        $sql = "SELECT MONTH(created_at) as month, COUNT(*) as user_count 
                FROM {$this->table} 
                WHERE YEAR(created_at) = :year 
                GROUP BY MONTH(created_at)";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':year', $currentYear, PDO::PARAM_STR);
        $stmt->execute();
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Cập nhật số lượng người dùng cho các tháng
        foreach ($data as $row) {
            $month = (int)$row['month'];
            $result[$month] = (int)$row['user_count'];
        }
        
        return $result;
    }
}
?> 