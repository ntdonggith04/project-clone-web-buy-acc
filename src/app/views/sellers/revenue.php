<?php
$title = "Quản lý doanh thu";
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Thống kê doanh thu</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select class="form-select" id="timeFilter">
                            <option value="today" <?php echo $time == 'today' ? 'selected' : ''; ?>>Hôm nay</option>
                            <option value="week" <?php echo $time == 'week' ? 'selected' : ''; ?>>Tuần này</option>
                            <option value="month" <?php echo $time == 'month' ? 'selected' : ''; ?>>Tháng này</option>
                            <option value="year" <?php echo $time == 'year' ? 'selected' : ''; ?>>Năm nay</option>
                            <option value="all" <?php echo $time == 'all' ? 'selected' : ''; ?>>Tất cả</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title">Tổng doanh thu</h6>
                                <h3 class="mb-0"><?php echo number_format($stats['total_revenue']); ?> VNĐ</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title">Doanh thu ròng</h6>
                                <h3 class="mb-0"><?php echo number_format($stats['net_revenue']); ?> VNĐ</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-title">Phí dịch vụ</h6>
                                <h3 class="mb-0"><?php echo number_format($stats['service_fee']); ?> VNĐ</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6 class="card-title">Số đơn hàng</h6>
                                <h3 class="mb-0"><?php echo $stats['total_orders']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Biểu đồ doanh thu</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Danh sách đơn hàng</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tài khoản</th>
                                <th>Giá bán</th>
                                <th>Phí dịch vụ</th>
                                <th>Doanh thu</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($orders)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Chưa có đơn hàng nào</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo BASE_URL; ?>assets/images/games/<?php echo $order['game_image']; ?>" 
                                                     class="rounded me-3" width="40" height="40" alt="<?php echo $order['game_name']; ?>">
                                                <div>
                                                    <div><?php echo $order['game_name']; ?></div>
                                                    <small class="text-muted">
                                                        Level: <?php echo $order['level']; ?> | 
                                                        Rank: <?php echo $order['rank']; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo number_format($order['price']); ?> VNĐ</td>
                                        <td><?php echo number_format($order['service_fee']); ?> VNĐ</td>
                                        <td><?php echo number_format($order['net_amount']); ?> VNĐ</td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            switch($order['status']) {
                                                case 'pending':
                                                    $status_class = 'warning';
                                                    break;
                                                case 'paid':
                                                    $status_class = 'info';
                                                    break;
                                                case 'completed':
                                                    $status_class = 'success';
                                                    break;
                                                case 'cancelled':
                                                    $status_class = 'danger';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $status_class; ?>">
                                                <?php
                                                switch($order['status']) {
                                                    case 'pending':
                                                        echo 'Chờ thanh toán';
                                                        break;
                                                    case 'paid':
                                                        echo 'Đã thanh toán';
                                                        break;
                                                    case 'completed':
                                                        echo 'Hoàn thành';
                                                        break;
                                                    case 'cancelled':
                                                        echo 'Đã hủy';
                                                        break;
                                                }
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&time=<?php echo $time; ?>">Trước</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&time=<?php echo $time; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&time=<?php echo $time; ?>">Sau</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.getElementById('timeFilter').addEventListener('change', function() {
    window.location.href = '?time=' + this.value;
});

// Biểu đồ doanh thu
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($chart['labels']); ?>,
        datasets: [{
            label: 'Doanh thu',
            data: <?php echo json_encode($chart['revenue']); ?>,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + ' VNĐ';
                    }
                }
            }
        }
    }
});
</script>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 