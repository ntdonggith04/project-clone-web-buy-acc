<?php
namespace App\Config;

class Database {
    private $host = 'localhost';
    private $db_name = 'account_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function __construct() {
        try {
            error_log("Attempting to connect to database with settings:");
            error_log("Host: " . $this->host);
            error_log("Database: " . $this->db_name);
            error_log("Username: " . $this->username);
            
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            error_log("DSN: " . $dsn);
            
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false
            ];
            
            $this->conn = new \PDO($dsn, $this->username, $this->password, $options);
            error_log("Database connection successful");
            
        } catch(\PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            error_log("Error Code: " . $e->getCode());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Không thể kết nối đến database. Vui lòng kiểm tra lại cấu hình.");
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            
            // Log the query for debugging
            error_log("Executing SQL: " . $sql);
            if (!empty($params)) {
                error_log("Parameters: " . print_r($params, true));
            }

            $success = $stmt->execute($params);
            if (!$success) {
                error_log("Query failed. Error info: " . print_r($stmt->errorInfo(), true));
                return false;
            }

            // Nếu là SELECT, trả về kết quả fetchAll
            if (stripos(trim($sql), 'SELECT') === 0) {
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                error_log("Query returned " . count($result) . " rows");
                return $result;
            }
            
            // Nếu là INSERT, UPDATE, DELETE, trả về true/false
            return $success;
            
        } catch(\PDOException $e) {
            error_log("Query Error: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Parameters: " . print_r($params, true));
            throw new \Exception("Lỗi truy vấn database.");
        }
    }

    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    public function commit() {
        return $this->conn->commit();
    }

    public function rollBack() {
        return $this->conn->rollBack();
    }

    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
} 