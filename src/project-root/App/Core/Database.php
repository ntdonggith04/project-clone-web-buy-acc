<?php
namespace App\Core;

class Database {
    private $host = 'localhost';
    private $db_name = 'account_db';
    private $username = 'root';
    private $password = '';
    private $conn;
    private static $instance = null;

    public function __construct() {
        try {
            $this->conn = new \PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ]
            );
            $this->conn->exec("SET CHARACTER SET utf8mb4");
            $this->conn->exec("SET NAMES utf8mb4");
        } catch(\PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            throw new \Exception("Database connection failed");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            
            // Nếu là câu lệnh SELECT, trả về kết quả
            if (stripos($sql, 'SELECT') === 0) {
                return $stmt->fetchAll();
            }
            
            // Nếu là INSERT, trả về ID mới
            if (stripos($sql, 'INSERT') === 0) {
                return $this->conn->lastInsertId();
            }
            
            // Các trường hợp khác trả về số hàng bị ảnh hưởng
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            error_log("Query Error: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            throw $e;
        }
    }

    public function prepare($sql) {
        try {
            return $this->conn->prepare($sql);
        } catch (\PDOException $e) {
            error_log("Prepare Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    public function commit() {
        return $this->conn->commit();
    }

    public function rollback() {
        return $this->conn->rollBack();
    }

    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
} 