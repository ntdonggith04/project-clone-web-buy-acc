<?php
namespace App\Models;

use Core\Model;
use PDO;

class User extends Model {
    private $defaultAvatar = '/images/avatars/default.png';
    private $table = 'users';

    public function create($data) {
        $data['avatar'] = $data['avatar'] ?? $this->defaultAvatar;
        $data['role'] = $data['role'] ?? 'user';
        return $this->insert($this->table, $data);
    }

    public function getByEmail($email) {
        try {
            return $this->fetchOne("SELECT * FROM {$this->table} WHERE email = :email", ['email' => $email]);
        } catch (\Exception $e) {
            error_log("Error in getByEmail: " . $e->getMessage());
            return null;
        }
    }

    public function getById($id) {
        try {
            return $this->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
        } catch (\Exception $e) {
            error_log("Error in getById: " . $e->getMessage());
            return null;
        }
    }

    // Admin methods
    public function getAllUsers() {
        try {
            return $this->fetchAll(
                "SELECT id, username, email, role, avatar, status, created_at 
                 FROM {$this->table} 
                 ORDER BY id DESC"
            );
        } catch (\Exception $e) {
            error_log("Error in getAllUsers: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalUsers() {
        try {
            return $this->count($this->table);
        } catch (\Exception $e) {
            error_log("Error in getTotalUsers: " . $e->getMessage());
            return 0;
        }
    }

    public function getRecentUsers($limit = 5) {
        try {
            $sql = "SELECT id, username, email, created_at FROM users ORDER BY created_at DESC LIMIT ?";
            $users = $this->query($sql, [$limit]);
            
            if (empty($users)) {
                return '<tr><td colspan="5">Không có dữ liệu</td></tr>';
            }

            $html = '';
            foreach ($users as $user) {
                $html .= '<tr>
                    <td>' . htmlspecialchars($user['id']) . '</td>
                    <td>' . htmlspecialchars($user['username']) . '</td>
                    <td>' . htmlspecialchars($user['email']) . '</td>
                    <td>' . date('d/m/Y H:i', strtotime($user['created_at'])) . '</td>
                    <td>
                        <a href="/project-clone-web-buy-acc/src/project-root/public/admin/users/edit/' . $user['id'] . '" class="btn btn-sm btn-primary">Sửa</a>
                        <a href="/project-clone-web-buy-acc/src/project-root/public/admin/users/delete/' . $user['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Bạn có chắc chắn muốn xóa?\')">Xóa</a>
                    </td>
                </tr>';
            }
            return $html;
        } catch (\Exception $e) {
            error_log("Error in getRecentUsers: " . $e->getMessage());
            return '<tr><td colspan="5">Không có dữ liệu</td></tr>';
        }
    }

    public function getUserById($id) {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            return null;
        }
    }

    public function updateUser($id, $data) {
        return $this->update($this->table, $data, ['id' => $id]);
    }

    public function deleteUser($id) {
        try {
            error_log("Attempting to delete user with ID: " . $id);
            
            // Kiểm tra user có tồn tại không
            $user = $this->getUserById($id);
            if (!$user) {
                error_log("User not found for deletion");
                return false;
            }

            // Thực hiện xóa
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                error_log("User deleted successfully");
                return true;
            } else {
                error_log("Failed to delete user. PDO error info: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (\PDOException $e) {
            error_log("PDO Exception in deleteUser: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            error_log("General Exception in deleteUser: " . $e->getMessage());
            throw $e;
        }
    }

    public function updatePassword($id, $newPassword) {
        return $this->update($this->table, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ], ['id' => $id]);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->query($sql, [$email])->fetch();
    }

    public function saveResetToken($email, $token, $expiry) {
        return $this->update($this->table, [
            'reset_token' => $token,
            'reset_token_expiry' => $expiry
        ], ['email' => $email]);
    }

    public function verifyResetToken($token) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE reset_token = :token AND reset_token_expiry > NOW()",
            ['token' => $token]
        );
    }

    public function updatePasswordByToken($token, $password) {
        return $this->update($this->table, [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expiry' => null
        ], ['reset_token' => $token]);
    }

    public function getAvatar($userId) {
        try {
            $user = $this->fetchOne(
                "SELECT avatar FROM {$this->table} WHERE id = :id",
                ['id' => $userId]
            );
            return $user['avatar'] ?? $this->defaultAvatar;
        } catch (\Exception $e) {
            error_log("Error in getAvatar: " . $e->getMessage());
            return $this->defaultAvatar;
        }
    }

    public function updateAvatar($userId, $avatarPath) {
        return $this->update($this->table, ['avatar' => $avatarPath], ['id' => $userId]);
    }

    public function banUser($id) {
        $sql = "UPDATE users SET status = 'banned' WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    public function unbanUser($id) {
        $sql = "UPDATE users SET status = 'active' WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    public function getUsers($search = '', $role = '', $status = '', $limit = 10, $offset = 0) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $sql .= " AND (username LIKE :search OR email LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            if (!empty($role)) {
                $sql .= " AND role = :role";
                $params[':role'] = $role;
            }

            if (!empty($status)) {
                $sql .= " AND status = :status";
                $params[':status'] = $status;
            }

            $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $params[':limit'] = (int)$limit;
            $params[':offset'] = (int)$offset;

            $stmt = $this->prepare($sql);
            
            foreach ($params as $key => $value) {
                if ($key === ':limit' || $key === ':offset') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in getUsers: " . $e->getMessage());
            return [];
        }
    }

    public function countUsers($search = '', $role = '', $status = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $sql .= " AND (username LIKE :search OR email LIKE :search)";
                $params[':search'] = "%{$search}%";
            }

            if (!empty($role)) {
                $sql .= " AND role = :role";
                $params[':role'] = $role;
            }

            if (!empty($status)) {
                $sql .= " AND status = :status";
                $params[':status'] = $status;
            }

            $stmt = $this->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total'];
        } catch (\Exception $e) {
            error_log("Error in countUsers: " . $e->getMessage());
            return 0;
        }
    }

    public function toggleStatus($id) {
        try {
            $user = $this->getById($id);
            if (!$user) {
                return false;
            }

            $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
            return $this->update($this->table, ['status' => $newStatus], ['id' => $id]);
        } catch (\Exception $e) {
            error_log("Error in toggleStatus: " . $e->getMessage());
            return false;
        }
    }
}
