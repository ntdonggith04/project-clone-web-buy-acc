<?php
$title = "Dashboard Admin | " . SITE_NAME;
ob_start();
?>

<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-primary">
                                Tổng số người dùng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $total_users ?>
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <a href="<?= BASE_URL ?>admin/users" class="text-primary">Chi tiết <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-gradient-primary text-white border-radius-md">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-success">
                                Tổng số tài khoản game
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $total_accounts ?>
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <a href="<?= BASE_URL ?>admin/accounts" class="text-success">Chi tiết <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-gradient-success text-white border-radius-md">
                                <i class="fas fa-gamepad"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-warning">
                                Tổng số đơn hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $total_orders ?>
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <a href="<?= BASE_URL ?>admin/orders" class="text-warning">Chi tiết <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-gradient-warning text-white border-radius-md">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-danger">
                                Tổng doanh thu
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($total_revenue, 0, ',', '.') ?> đ
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <a href="<?= BASE_URL ?>admin/transactions" class="text-danger">Chi tiết <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-gradient-danger text-white border-radius-md">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistical Charts -->
    <div class="row mb-4">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Thống kê doanh thu</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Thống kê đơn hàng</h6>
                </div>
                <div class="card-body">
                    <canvas id="orderChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Phân bố tài khoản game theo danh mục</h6>
                </div>
                <div class="card-body">
                    <canvas id="accountChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Thống kê người dùng</h6>
                </div>
                <div class="card-body">
                    <canvas id="userChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Hoạt động người dùng</h6>
                </div>
                <div class="card-body">
                    <canvas id="userActivityChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h5>Xin chào, <?= $_SESSION['username'] ?? 'Admin' ?>!</h5>
                                <p class="text-sm text-muted">
                                    Đây là trang quản trị của hệ thống. Bạn có thể quản lý tài khoản game, người dùng, đơn hàng và các thông tin khác của hệ thống tại đây.
                                </p>
                            </div>
                            <div class="mb-4">
                                <h6 class="mb-2">Quản lý nhanh:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="<?= BASE_URL ?>admin/accounts" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-gamepad me-1"></i> Quản lý tài khoản game
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/users" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-users me-1"></i> Quản lý người dùng
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/orders" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-shopping-cart me-1"></i> Quản lý đơn hàng
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/games" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-dice me-1"></i> Quản lý game
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-body border-0 shadow-sm h-100">
                                <h6 class="mb-3">Thao tác nhanh</h6>
                                <div class="d-grid gap-2">
                                    <a href="<?= BASE_URL ?>admin/accounts/add" class="btn btn-sm btn-success">
                                        <i class="fas fa-plus me-1"></i> Thêm tài khoản game
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/games/add" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> Thêm game mới
                                    </a>
                                    <a href="<?= BASE_URL ?>admin/orders?status=pending" class="btn btn-sm btn-warning">
                                        <i class="fas fa-clock me-1"></i> Xem đơn hàng đang chờ
                                    </a>
                                    <a href="<?= BASE_URL ?>" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-home me-1"></i> Về trang chủ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart (Line Chart)
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: [
                        <?php 
                        // Dữ liệu doanh thu thực tế theo tháng
                        $months = $monthly_revenue ?? [];
                        for ($i = 1; $i <= 12; $i++) {
                            echo (isset($months[$i]) ? $months[$i] : 0) . ', ';
                        }
                        ?>
                    ],
                    borderColor: 'rgb(245, 54, 92)',
                    backgroundColor: 'rgba(245, 54, 92, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Định dạng số doanh thu với đơn vị VNĐ
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + ' đ';
                            }
                        }
                    }
                }
            }
        });
        
        // Order Chart (Pie Chart)
        const orderCtx = document.getElementById('orderChart').getContext('2d');
        const orderChart = new Chart(orderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Đã thanh toán', 'Đang xử lý', 'Đã hủy'],
                datasets: [{
                    data: [
                        <?php 
                        // Dữ liệu thực tế về trạng thái đơn hàng
                        $stats = $order_stats ?? ['completed' => 0, 'pending' => 0, 'cancelled' => 0];
                        echo $stats['completed'] . ', ' . $stats['pending'] . ', ' . $stats['cancelled'];
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(45, 206, 137, 0.8)',
                        'rgba(251, 99, 64, 0.8)',
                        'rgba(245, 54, 92, 0.8)'
                    ],
                    borderColor: [
                        'rgba(45, 206, 137, 1)',
                        'rgba(251, 99, 64, 1)',
                        'rgba(245, 54, 92, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
        
        // Account Distribution Chart (Bar Chart)
        const accountCtx = document.getElementById('accountChart').getContext('2d');
        const accountChart = new Chart(accountCtx, {
            type: 'bar',
            data: {
                labels: [
                    <?php 
                    // Lấy tên game từ dữ liệu thực tế
                    $game_stats = $game_account_stats ?? [];
                    foreach ($game_stats as $game) {
                        echo "'" . addslashes($game['name']) . "', ";
                    }
                    ?>
                ],
                datasets: [{
                    label: 'Số lượng tài khoản',
                    data: [
                        <?php 
                        // Lấy số lượng tài khoản theo game từ dữ liệu thực tế
                        foreach ($game_stats as $game) {
                            echo $game['count'] . ', ';
                        }
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(94, 114, 228, 0.8)',
                        'rgba(45, 206, 137, 0.8)',
                        'rgba(251, 99, 64, 0.8)',
                        'rgba(17, 205, 239, 0.8)',
                        'rgba(23, 43, 77, 0.8)'
                    ],
                    borderColor: [
                        'rgba(94, 114, 228, 1)',
                        'rgba(45, 206, 137, 1)',
                        'rgba(251, 99, 64, 1)',
                        'rgba(17, 205, 239, 1)',
                        'rgba(23, 43, 77, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // User Statistics Chart (Pie Chart)
        const userCtx = document.getElementById('userChart').getContext('2d');
        const userChart = new Chart(userCtx, {
            type: 'pie',
            data: {
                labels: ['Quản trị viên', 'Người dùng'],
                datasets: [{
                    data: [
                        <?php 
                        // Dữ liệu thực tế về số lượng người dùng theo vai trò
                        $user_stats = $user_stats ?? ['admin' => 0, 'user' => 0];
                        echo $user_stats['admin'] . ', ' . $user_stats['user'];
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(94, 114, 228, 0.8)',
                        'rgba(45, 206, 137, 0.8)'
                    ],
                    borderColor: [
                        'rgba(94, 114, 228, 1)',
                        'rgba(45, 206, 137, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw + ' người dùng';
                                return label;
                            }
                        }
                    }
                }
            }
        });
        
        // User Activity Chart (Line Chart)
        const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
        const userActivityChart = new Chart(userActivityCtx, {
            type: 'line',
            data: {
                labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                datasets: [{
                    label: 'Người dùng mới',
                    data: [
                        <?php 
                        // Dữ liệu thực tế về số lượng người dùng đăng ký mới theo tháng
                        $monthly_users = $monthly_new_users ?? [];
                        for ($i = 1; $i <= 12; $i++) {
                            echo (isset($monthly_users[$i]) ? $monthly_users[$i] : 0) . ', ';
                        }
                        ?>
                    ],
                    borderColor: 'rgb(94, 114, 228)',
                    backgroundColor: 'rgba(94, 114, 228, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/admin.php';
?> 