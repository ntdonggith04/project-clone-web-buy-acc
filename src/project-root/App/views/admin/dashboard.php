<?php
$content = '
<div class="dashboard">
    <h1 class="page-title">Dashboard</h1>
    
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Tổng số người dùng</h3>
            <div class="number">' . $stats['total_users'] . '</div>
        </div>
        <div class="stat-card">
            <h3>Tổng số tài khoản</h3>
            <div class="number">' . $stats['total_accounts'] . '</div>
        </div>
        <div class="stat-card">
            <h3>Tổng số game</h3>
            <div class="number">' . $stats['total_games'] . '</div>
        </div>
        <div class="stat-card">
            <h3>Tổng số giao dịch</h3>
            <div class="number">' . $stats['total_sales'] . '</div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Người dùng mới nhất</h2>
            <a href="/project-clone-web-buy-acc/src/project-root/public/admin/users" class="btn btn-primary">Xem tất cả</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Ngày đăng ký</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                ' . $this->userModel->getRecentUsers() . '
            </tbody>
        </table>
    </div>

    <!-- Recent Accounts -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Tài khoản mới nhất</h2>
            <a href="/project-clone-web-buy-acc/src/project-root/public/admin/accounts" class="btn btn-primary">Xem tất cả</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên game</th>
                    <th>Giá</th>
                    <th>Người bán</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                ' . $this->accountModel->getRecentAccounts() . '
            </tbody>
        </table>
    </div>
</div>
';

require_once BASE_PATH . '/App/views/admin/layout.php'; 