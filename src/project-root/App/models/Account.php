<?php
namespace App\Models;

class Account {
    private $db;

    public function __construct() {
        try {
            $this->db = new \PDO('mysql:host=localhost;dbname=account_db', 'root', '');
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT a.*, u.username as seller_name 
                                    FROM accounts a 
                                    JOIN users u ON a.seller_id = u.id 
                                    ORDER BY a.created_at DESC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            throw new \Exception("Failed to get accounts: " . $e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT a.*, u.username as seller_name 
                                      FROM accounts a 
                                      JOIN users u ON a.seller_id = u.id 
                                      WHERE a.id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            throw new \Exception("Failed to get account: " . $e->getMessage());
        }
    }

    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("INSERT INTO accounts (title, description, price, seller_id, created_at) 
                                      VALUES (?, ?, ?, ?, NOW())");
            $result = $stmt->execute([
                $data['title'],
                $data['description'],
                $data['price'],
                $data['seller_id']
            ]);

            $this->db->commit();
            return $result;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function __destruct() {
        $this->db = null;
    }
} 