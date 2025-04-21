<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Transaction extends Model
{
    protected $table = 'transactions';

    // Properties
    public $user_id;
    public $amount;
    public $type;
    public $status;
    public $description;

    /**
     * Create a new transaction record
     * 
     * @param array|null $data Transaction data (if null, use object properties)
     * @return int|false The ID of the new transaction or false on failure
     */
    public function create($data = null)
    {
        if ($data === null) {
            $data = [
                'user_id' => $this->user_id,
                'amount' => $this->amount,
                'type' => $this->type ?? 'purchase',
                'status' => $this->status ?? 'pending',
                'description' => $this->description ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        
        try {
            error_log("Creating transaction with data: " . print_r($data, true));
            return parent::create($data);
        } catch (\Exception $e) {
            error_log("Error creating transaction: " . $e->getMessage());
            return false;
        }
    }

    public function getTransactionsByUser($userId, $limit = 10)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE user_id = :user_id
                ORDER BY created_at DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactionById($id)
    {
        $sql = "SELECT t.*, u.name as user_name, u.email as user_email
                FROM {$this->table} t
                JOIN users u ON t.user_id = u.id
                WHERE t.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function getTransactionStats($userId = null)
    {
        $sql = "SELECT 
                    COUNT(*) as total_transactions,
                    SUM(CASE WHEN type = 'deposit' THEN 1 ELSE 0 END) as total_deposits,
                    SUM(CASE WHEN type = 'withdraw' THEN 1 ELSE 0 END) as total_withdrawals,
                    SUM(CASE WHEN type = 'deposit' THEN amount ELSE 0 END) as total_deposit_amount,
                    SUM(CASE WHEN type = 'withdraw' THEN amount ELSE 0 END) as total_withdrawal_amount,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_transactions,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_transactions,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_transactions
                FROM {$this->table}";
        
        if ($userId) {
            $sql .= " WHERE user_id = :user_id";
        }
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        }
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 