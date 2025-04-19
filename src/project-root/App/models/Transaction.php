<?php

namespace App\Models;

class Transaction extends BaseModel {
    protected static $table = 'transactions';

    public static function findByUserId($userId, $limit = null) {
        return self::findAll(['user_id' => $userId], 'created_at DESC', $limit);
    }

    public static function findPending($userId = null) {
        $conditions = ['status' => 'pending'];
        if ($userId) {
            $conditions['user_id'] = $userId;
        }
        return self::findAll($conditions, 'created_at DESC');
    }

    public static function findCompleted($userId = null) {
        $conditions = ['status' => 'completed'];
        if ($userId) {
            $conditions['user_id'] = $userId;
        }
        return self::findAll($conditions, 'created_at DESC');
    }

    public function isCompleted() {
        return $this->status === 'completed';
    }

    public function isPending() {
        return $this->status === 'pending';
    }

    public function isFailed() {
        return $this->status === 'failed';
    }

    public function complete() {
        $this->status = 'completed';
        return $this->save();
    }

    public function fail() {
        $this->status = 'failed';
        return $this->save();
    }

    public function getFormattedAmount() {
        return number_format($this->amount) . 'đ';
    }

    public function getFormattedBonus() {
        return number_format($this->bonus) . 'đ';
    }

    public function getFormattedTotalAmount() {
        return number_format($this->total_amount) . 'đ';
    }

    public function getStatusBadgeClass() {
        switch ($this->status) {
            case 'completed':
                return 'badge-success';
            case 'pending':
                return 'badge-warning';
            case 'failed':
                return 'badge-danger';
            default:
                return 'badge-secondary';
        }
    }

    public function getStatusText() {
        switch ($this->status) {
            case 'completed':
                return 'Thành công';
            case 'pending':
                return 'Đang xử lý';
            case 'failed':
                return 'Thất bại';
            default:
                return 'Không xác định';
        }
    }

    public function getPaymentMethodText() {
        switch ($this->payment_method) {
            case 'bank':
                return 'Chuyển khoản ngân hàng';
            case 'momo':
                return 'Ví MoMo';
            case 'zalopay':
                return 'ZaloPay';
            case 'card':
                return 'Thẻ cào';
            default:
                return 'Không xác định';
        }
    }

    public static function getTotalRevenue() {
        $sql = "SELECT COALESCE(SUM(amount), 0) FROM " . static::$table . " WHERE status = 'completed'";
        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log("Error getting total revenue: " . $e->getMessage());
            return 0;
        }
    }

    public static function getTotalRevenueToday() {
        $sql = "SELECT COALESCE(SUM(amount), 0) FROM " . static::$table . " 
                WHERE status = 'completed' AND DATE(created_at) = CURDATE()";
        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log("Error getting today's revenue: " . $e->getMessage());
            return 0;
        }
    }

    public static function getTotalRevenueThisWeek() {
        $sql = "SELECT COALESCE(SUM(amount), 0) FROM " . static::$table . " 
                WHERE status = 'completed' AND YEARWEEK(created_at) = YEARWEEK(NOW())";
        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log("Error getting this week's revenue: " . $e->getMessage());
            return 0;
        }
    }

    public static function getTotalRevenueThisMonth() {
        $sql = "SELECT COALESCE(SUM(amount), 0) FROM " . static::$table . " 
                WHERE status = 'completed' 
                AND MONTH(created_at) = MONTH(NOW()) 
                AND YEAR(created_at) = YEAR(NOW())";
        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log("Error getting this month's revenue: " . $e->getMessage());
            return 0;
        }
    }

    public static function getMonthlyStats() {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count,
                    COALESCE(SUM(amount), 0) as total
                FROM " . static::$table . "
                WHERE status = 'completed'
                AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month DESC";
        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error getting monthly stats: " . $e->getMessage());
            return [];
        }
    }

    public static function getWeeklyStats() {
        $sql = "SELECT 
                    YEARWEEK(created_at) as week,
                    COUNT(*) as count,
                    COALESCE(SUM(amount), 0) as total
                FROM " . static::$table . "
                WHERE status = 'completed'
                AND created_at >= DATE_SUB(NOW(), INTERVAL 12 WEEK)
                GROUP BY YEARWEEK(created_at)
                ORDER BY week DESC";
        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error getting weekly stats: " . $e->getMessage());
            return [];
        }
    }

    public static function getYearlyStats() {
        $sql = "SELECT 
                    YEAR(created_at) as year,
                    COUNT(*) as count,
                    COALESCE(SUM(amount), 0) as total
                FROM " . static::$table . "
                WHERE status = 'completed'
                GROUP BY YEAR(created_at)
                ORDER BY year DESC";
        try {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error getting yearly stats: " . $e->getMessage());
            return [];
        }
    }
}