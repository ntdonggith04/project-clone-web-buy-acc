<?php

namespace Core;

use App\Config\Database;
use PDO;

abstract class Model {
    protected $db;
    protected $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Thực hiện truy vấn SQL với prepared statements
     * @param string $sql Câu truy vấn SQL
     * @param array $params Tham số cho prepared statement
     * @return mixed
     */
    protected function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            error_log("Database Query Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Chuẩn bị câu truy vấn
     * @param string $sql Câu truy vấn SQL
     * @return \PDOStatement
     */
    protected function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    /**
     * Lấy một bản ghi
     * @param string $sql Câu truy vấn SQL
     * @param array $params Tham số cho prepared statement
     * @return mixed
     */
    protected function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy nhiều bản ghi
     * @param string $sql Câu truy vấn SQL
     * @param array $params Tham số cho prepared statement
     * @return array
     */
    protected function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thực hiện insert
     * @param string $table Tên bảng
     * @param array $data Dữ liệu cần insert
     * @return int ID của bản ghi mới
     */
    protected function insert($table, $data) {
        $fields = array_keys($data);
        $placeholders = array_map(function($field) {
            return ":{$field}";
        }, $fields);

        $sql = "INSERT INTO {$table} (" . implode(", ", $fields) . ") 
                VALUES (" . implode(", ", $placeholders) . ")";

        $this->query($sql, $data);
        return $this->db->lastInsertId();
    }

    /**
     * Thực hiện update
     * @param string $table Tên bảng
     * @param array $data Dữ liệu cần update
     * @param array $where Điều kiện where
     * @return bool
     */
    protected function update($table, $data, $where) {
        $fields = array_map(function($field) {
            return "{$field} = :{$field}";
        }, array_keys($data));

        $whereClause = array_map(function($field) {
            return "{$field} = :where_{$field}";
        }, array_keys($where));

        $sql = "UPDATE {$table} SET " . implode(", ", $fields) . " WHERE " . implode(" AND ", $whereClause);

        $params = $data;
        foreach ($where as $key => $value) {
            $params["where_{$key}"] = $value;
        }

        return $this->query($sql, $params)->rowCount() > 0;
    }

    /**
     * Thực hiện delete
     * @param string $table Tên bảng
     * @param array $where Điều kiện where
     * @return bool
     */
    protected function delete($table, $where) {
        $whereClause = array_map(function($field) {
            return "{$field} = :{$field}";
        }, array_keys($where));

        $sql = "DELETE FROM {$table} WHERE " . implode(" AND ", $whereClause);

        return $this->query($sql, $where)->rowCount() > 0;
    }

    /**
     * Đếm số bản ghi
     * @param string $table Tên bảng
     * @param array $where Điều kiện where (optional)
     * @return int
     */
    protected function count($table, $where = []) {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        
        if (!empty($where)) {
            $whereClause = array_map(function($field) {
                return "{$field} = :{$field}";
            }, array_keys($where));
            $sql .= " WHERE " . implode(" AND ", $whereClause);
        }

        $result = $this->query($sql, $where)->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }
} 