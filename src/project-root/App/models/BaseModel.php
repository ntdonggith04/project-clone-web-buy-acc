<?php

namespace App\Models;

use PDO;
use PDOException;

abstract class BaseModel {
    protected static $db;
    protected static $table;
    protected $data = [];

    public function __construct() {
        if (!self::$db) {
            $this->connect();
        }
    }

    protected function connect() {
        try {
            $host = 'localhost';
            $dbname = 'game_accounts';
            $username = 'root';
            $password = '';
            
            self::$db = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function __get($name) {
        return $this->data[$name] ?? null;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function save() {
        if (isset($this->data['id'])) {
            return $this->update();
        }
        return $this->insert();
    }

    protected function insert() {
        $fields = array_keys($this->data);
        $values = array_values($this->data);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            static::$table,
            implode(', ', $fields),
            $placeholders
        );

        try {
            $stmt = self::$db->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Insert failed: " . $e->getMessage());
            return false;
        }
    }

    protected function update() {
        $fields = array_keys($this->data);
        $values = array_values($this->data);
        $set = implode('=?, ', $fields) . '=?';
        
        $sql = sprintf(
            "UPDATE %s SET %s WHERE id=?",
            static::$table,
            $set
        );

        try {
            $stmt = self::$db->prepare($sql);
            $values[] = $this->data['id']; // Add ID for WHERE clause
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Update failed: " . $e->getMessage());
            return false;
        }
    }

    public static function findById($id) {
        $sql = sprintf("SELECT * FROM %s WHERE id = ? LIMIT 1", static::$table);
        
        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute([$id]);
            
            if ($row = $stmt->fetch()) {
                $model = new static();
                $model->data = $row;
                return $model;
            }
        } catch (PDOException $e) {
            error_log("Find by ID failed: " . $e->getMessage());
        }
        
        return null;
    }

    public static function findAll($conditions = [], $orderBy = null, $limit = null) {
        $sql = sprintf("SELECT * FROM %s", static::$table);
        $values = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                $where[] = "$field = ?";
                $values[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }

        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute($values);
            
            $results = [];
            while ($row = $stmt->fetch()) {
                $model = new static();
                $model->data = $row;
                $results[] = $model;
            }
            return $results;
        } catch (PDOException $e) {
            error_log("Find all failed: " . $e->getMessage());
            return [];
        }
    }

    public function delete() {
        if (!isset($this->data['id'])) {
            return false;
        }

        $sql = sprintf("DELETE FROM %s WHERE id = ?", static::$table);
        
        try {
            $stmt = self::$db->prepare($sql);
            return $stmt->execute([$this->data['id']]);
        } catch (PDOException $e) {
            error_log("Delete failed: " . $e->getMessage());
            return false;
        }
    }

    public function toArray() {
        return $this->data;
    }
} 