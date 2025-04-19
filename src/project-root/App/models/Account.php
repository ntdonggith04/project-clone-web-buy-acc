<?php
namespace App\Models;

use App\Config\Database;

class Account {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function checkTableStructure() {
        try {
            // Kiểm tra bảng có tồn tại
            $sql = "SHOW TABLES LIKE 'accounts'";
            $result = $this->db->query($sql);
            if (!is_array($result) || empty($result)) {
                error_log("Table 'accounts' does not exist");
                return false;
            }
            
            // Kiểm tra cấu trúc bảng
            $sql = "DESCRIBE accounts";
            $columns = $this->db->query($sql);
            error_log("Table structure: " . print_r($columns, true));
            
            // Đếm số bản ghi
            $sql = "SELECT COUNT(*) as total FROM accounts";
            $count = $this->db->query($sql);
            if (is_array($count) && !empty($count)) {
                error_log("Total records: " . $count[0]['total']);
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error checking table structure: " . $e->getMessage());
            return false;
        }
    }

    public function debugTableStructure() {
        try {
            // Kiểm tra cấu trúc bảng
            $sql = "DESCRIBE accounts";
            $columns = $this->db->query($sql);
            error_log("Table structure: " . print_r($columns, true));
            
            // Lấy một số bản ghi đầu tiên
            $sql = "SELECT * FROM accounts LIMIT 1";
            $sample = $this->db->query($sql);
            error_log("Sample record: " . print_r($sample, true));
            
            return true;
        } catch (\Exception $e) {
            error_log("Error in debugTableStructure: " . $e->getMessage());
            return false;
        }
    }

    public function getAllAccounts() {
        try {
            error_log("=== getAllAccounts() started ===");
            
            // Debug table structure first
            $this->debugTableStructure();
            
            // Kiểm tra kết nối database
            if (!$this->db || !$this->db->getConnection()) {
                error_log("Database connection is not available");
                return [];
            }
            
            $sql = "SELECT 
                        a.id,
                        a.game_id,
                        g.name as game_name,
                        a.seller_id,
                        a.title,
                        a.username,
                        a.password,
                        a.basic_description,
                        a.detailed_description,
                        a.description,
                        a.price,
                        a.status,
                        a.created_at,
                        u.username as seller_name
                    FROM accounts a 
                    LEFT JOIN users u ON a.seller_id = u.id 
                    LEFT JOIN games g ON a.game_id = g.id
                    ORDER BY a.id DESC";
            
            error_log("Executing SQL: " . $sql);
            
            // Thực hiện truy vấn và kiểm tra kết quả
            $accounts = $this->db->query($sql);
            
            if ($accounts === false) {
                error_log("Query failed to execute");
                return [];
            }
            
            error_log("Raw accounts data: " . print_r($accounts, true));
            
            if (!is_array($accounts)) {
                error_log("Query result is not an array");
                return [];
            }
            
            error_log("Number of accounts found: " . count($accounts));
            
            // Format the output
            foreach ($accounts as &$account) {
                // Set default values for required fields
                if (!isset($account['game_id'])) {
                    $account['game_id'] = '';
                }
                if (!isset($account['game_name'])) {
                    $account['game_name'] = '';
                }
                if (!isset($account['title'])) {
                    $account['title'] = '';
                }
                if (!isset($account['username'])) {
                    $account['username'] = '';
                }
                if (!isset($account['password'])) {
                    $account['password'] = '';
                }
                if (!isset($account['basic_description'])) {
                    $account['basic_description'] = '';
                }
                if (!isset($account['detailed_description'])) {
                    $account['detailed_description'] = '';
                }
                if (!isset($account['description'])) {
                    $account['description'] = '';
                }
                if (!isset($account['price'])) {
                    $account['price'] = 0;
                }
                if (!isset($account['status'])) {
                    $account['status'] = 'available';
                }
                if (!isset($account['seller_name'])) {
                    $account['seller_name'] = '';
                }
            }
            
            error_log("=== getAllAccounts() completed ===");
            error_log("Returning " . count($accounts) . " formatted accounts");
            return $accounts;
            
        } catch (\Exception $e) {
            error_log("Error in getAllAccounts: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [];
        }
    }

    public function getTotalAccounts() {
        try {
            $sql = "SELECT COUNT(*) as total FROM accounts";
            $result = $this->db->query($sql);
            error_log("getTotalAccounts result: " . print_r($result, true));
            if (!is_array($result) || empty($result)) {
                return 0;
            }
            return isset($result[0]['total']) ? (int)$result[0]['total'] : 0;
        } catch (\Exception $e) {
            error_log("Error in getTotalAccounts: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalSales() {
        try {
            $sql = "SELECT COUNT(*) as total FROM accounts WHERE status = 'sold'";
            $result = $this->db->query($sql);
            error_log("getTotalSales result: " . print_r($result, true));
            if (!is_array($result) || empty($result)) {
                return 0;
            }
            return isset($result[0]['total']) ? (int)$result[0]['total'] : 0;
        } catch (\Exception $e) {
            error_log("Error in getTotalSales: " . $e->getMessage());
            return 0;
        }
    }

    public function getAccountById($id) {
        try {
            $sql = "SELECT a.*, u.username as seller_name, g.name as game_name 
                    FROM accounts a 
                    LEFT JOIN users u ON a.seller_id = u.id 
                    LEFT JOIN games g ON a.game_id = g.id 
                    WHERE a.id = ?";
            $result = $this->db->query($sql, [$id]);
            error_log("getAccountById result: " . print_r($result, true));
            if (!is_array($result) || empty($result)) {
                return null;
            }
            return $result[0];
        } catch (\Exception $e) {
            error_log("Error in getAccountById: " . $e->getMessage());
            return null;
        }
    }

    public function createAccount($data) {
        try {
            error_log("Creating account with data: " . print_r($data, true));
            
            $sql = "INSERT INTO accounts (
                    game_id, seller_id, title, username, password, 
                    basic_description, detailed_description, description,
                    images, price, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $params = [
                $data['game_id'],
                $data['seller_id'],
                $data['title'],
                $data['username'],
                $data['password'],
                $data['basic_description'],
                $data['detailed_description'] ?? null,
                $data['description'] ?? null,
                $data['images'] ?? null,
                $data['price'],
                $data['status'] ?? 'available'
            ];
            
            error_log("SQL Query: " . $sql);
            error_log("Parameters: " . print_r($params, true));
            
            $result = $this->db->query($sql, $params);
            
            if (!$result) {
                error_log("Failed to create account. Database error.");
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error in createAccount: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function updateAccount($id, $data) {
        try {
            // Validate required fields
            $requiredFields = ['game_id', 'title', 'username', 'password', 'basic_description', 'price'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    throw new \Exception("Trường {$field} không được để trống");
                }
            }

            // Validate price
            if (!is_numeric($data['price']) || $data['price'] <= 0) {
                throw new \Exception("Giá phải là số dương");
            }

            $sql = "UPDATE accounts 
                    SET game_id = ?, 
                        title = ?, 
                        username = ?,
                        password = ?,
                        basic_description = ?,
                        detailed_description = ?,
                        description = ?,
                        images = ?,
                        price = ?,
                        status = ?
                    WHERE id = ?";

            $params = [
                $data['game_id'],
                $data['title'],
                $data['username'],
                $data['password'],
                $data['basic_description'],
                $data['detailed_description'] ?? null,
                $data['description'] ?? null,
                $data['images'] ?? null,
                $data['price'],
                $data['status'] ?? 'available',
                $id
            ];

            error_log("Updating account with data: " . print_r($data, true));
            
            $result = $this->db->query($sql, $params);
            
            if (!$result) {
                throw new \Exception("Không thể cập nhật tài khoản. Vui lòng thử lại.");
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error in updateAccount: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e; // Re-throw exception để controller có thể xử lý
        }
    }

    public function deleteAccount($id) {
        try {
            $sql = "DELETE FROM accounts WHERE id = ?";
            return $this->db->query($sql, [$id]);
        } catch (\Exception $e) {
            error_log("Error in deleteAccount: " . $e->getMessage());
            return false;
        }
    }

    public function getRecentAccounts($limit = 5) {
        try {
            $sql = "SELECT a.*, u.username as seller_name, g.name as game_name 
                    FROM accounts a 
                    LEFT JOIN users u ON a.seller_id = u.id 
                    LEFT JOIN games g ON a.game_id = g.id 
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $accounts = $this->db->query($sql, [$limit]);
            error_log("getRecentAccounts result: " . print_r($accounts, true));
            if (!is_array($accounts)) {
                return '<tr><td colspan="6">Không có dữ liệu</td></tr>';
            }
            
            $html = '';
            foreach ($accounts as $account) {
                $statusClass = $account['status'] === 'available' ? 'text-success' : ($account['status'] === 'sold' ? 'text-danger' : 'text-warning');
                $html .= '<tr>
                    <td>' . htmlspecialchars($account['id']) . '</td>
                    <td>' . htmlspecialchars($account['game_name']) . '</td>
                    <td>' . number_format($account['price'], 0, ',', '.') . ' VNĐ</td>
                    <td>' . htmlspecialchars($account['seller_name']) . '</td>
                    <td><span class="' . $statusClass . '">' . htmlspecialchars($account['status']) . '</span></td>
                    <td>
                        <a href="/project-clone-web-buy-acc/src/project-root/public/admin/accounts/edit/' . $account['id'] . '" class="btn btn-sm btn-primary">Sửa</a>
                        <a href="/project-clone-web-buy-acc/src/project-root/public/admin/accounts/delete/' . $account['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Bạn có chắc chắn muốn xóa?\')">Xóa</a>
                    </td>
                </tr>';
            }
            return $html;
        } catch (\Exception $e) {
            error_log("Error in getRecentAccounts: " . $e->getMessage());
            return '<tr><td colspan="6">Không có dữ liệu</td></tr>';
        }
    }

    public function getAccountsByGame() {
        try {
            $sql = "SELECT g.id as game_id, COUNT(*) as count 
                    FROM accounts a 
                    JOIN games g ON a.game_id = g.id 
                    GROUP BY g.id 
                    ORDER BY count DESC";
            $result = $this->db->query($sql);
            error_log("getAccountsByGame result: " . print_r($result, true));
            
            // Chuyển đổi kết quả thành mảng với game_id làm key
            $accountStats = [];
            if (is_array($result)) {
                foreach ($result as $row) {
                    $accountStats[$row['game_id']] = (int)$row['count'];
                }
            }
            return $accountStats;
        } catch (\Exception $e) {
            error_log("Error in getAccountsByGame: " . $e->getMessage());
            return [];
        }
    }

    public function getMonthlySales() {
        try {
            $sql = "SELECT 
                        DATE_FORMAT(created_at, '%Y-%m') as month,
                        SUM(price) as total
                    FROM accounts 
                    WHERE status = 'sold' 
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY month DESC 
                    LIMIT 12";
            $results = $this->db->query($sql);
            error_log("getMonthlySales result: " . print_r($results, true));
            
            if (!is_array($results)) {
                return [];
            }
            
            // Định dạng tên tháng sang tiếng Việt
            foreach ($results as &$row) {
                $date = date_create_from_format('Y-m', $row['month']);
                $row['month'] = 'Tháng ' . date_format($date, 'm/Y');
            }
            
            return array_reverse($results); // Đảo ngược để hiển thị từ trái sang phải
        } catch (\Exception $e) {
            error_log("Error in getMonthlySales: " . $e->getMessage());
            return [];
        }
    }

    public function getAll() {
        return $this->getAllAccounts();
    }
} 