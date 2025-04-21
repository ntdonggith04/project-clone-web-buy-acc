<?php
namespace App\Models;

use App\Core\Model;

class Account extends Model
{
    protected $table = 'accounts';

    public function getFeaturedAccounts($limit = 6)
    {
        $sql = "SELECT a.*, g.name as game_name, r.name as rank_name 
                FROM {$this->table} a 
                JOIN games g ON a.game_id = g.id 
                JOIN ranks r ON a.rank_id = r.id 
                WHERE a.status = 'active' 
                ORDER BY a.created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getAccountById($id)
    {
        $sql = "SELECT a.*, g.name as game_name, r.name as rank_name, u.username as seller_name 
                FROM {$this->table} a 
                JOIN games g ON a.game_id = g.id 
                JOIN ranks r ON a.rank_id = r.id 
                JOIN users u ON a.user_id = u.id 
                WHERE a.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function getAccountsByGame($gameId, $limit = 12)
    {
        $sql = "SELECT a.*, g.name as game_name, r.name as rank_name 
                FROM {$this->table} a 
                JOIN games g ON a.game_id = g.id 
                JOIN ranks r ON a.rank_id = r.id 
                WHERE a.game_id = :game_id AND a.status = 'active' 
                ORDER BY a.created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getAccountsByRank($rankId, $limit = 12)
    {
        $sql = "SELECT a.*, g.name as game_name, r.name as rank_name 
                FROM {$this->table} a 
                JOIN games g ON a.game_id = g.id 
                JOIN ranks r ON a.rank_id = r.id 
                WHERE a.rank_id = :rank_id AND a.status = 'active' 
                ORDER BY a.created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':rank_id', $rankId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function searchAccounts($keyword, $limit = 12)
    {
        $sql = "SELECT a.*, g.name as game_name, r.name as rank_name 
                FROM {$this->table} a 
                JOIN games g ON a.game_id = g.id 
                JOIN ranks r ON a.rank_id = r.id 
                WHERE a.title LIKE :keyword AND a.status = 'active' 
                ORDER BY a.created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $keyword = "%{$keyword}%";
        $stmt->bindValue(':keyword', $keyword);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function getAccountsByUser($userId, $limit = 10)
    {
        $sql = "SELECT a.*, g.name as game_name, r.name as rank_name 
                FROM {$this->table} a 
                JOIN games g ON a.game_id = g.id 
                JOIN ranks r ON a.rank_id = r.id 
                WHERE a.user_id = :user_id 
                ORDER BY a.created_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
} 