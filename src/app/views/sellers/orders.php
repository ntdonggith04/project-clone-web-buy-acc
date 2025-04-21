<?php
$title = "Quản lý đơn hàng";
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Danh sách đơn hàng</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>Chờ thanh toán</option>
                            <option value="paid" <?php echo $status == 'paid' ? 'selected' : ''; ?>>Đã thanh toán</option>
                            <option value="completed" <?php echo $status == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                            <option value="cancelled" <?php echo $status == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tài khoản</th>
                                <th>Người mua</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
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
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo BASE_URL; ?>assets/images/avatars/<?php echo $order['buyer_avatar']; ?>" 
                                                     class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                                <div>
                                                    <div><?php echo $order['buyer_name']; ?></div>
                                                    <small class="text-muted"><?php echo $order['buyer_email']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo number_format($order['total_amount']); ?> VNĐ</td>
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
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo BASE_URL; ?>orders/<?php echo $order['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if($order['status'] == 'paid'): ?>
                                                    <form action="<?php echo BASE_URL; ?>orders/<?php echo $order['id']; ?>/complete" 
                                                          method="POST" class="d-inline">
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                onclick="return confirm('Bạn có chắc chắn muốn hoàn thành đơn hàng này?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
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
                                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&status=<?php echo $status; ?>">Trước</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $status; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&status=<?php echo $status; ?>">Sau</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thống kê</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title">Tổng số đơn hàng</h6>
                                <h3 class="mb-0"><?php echo $stats['total_orders']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6 class="card-title">Đơn hàng chờ thanh toán</h6>
                                <h3 class="mb-0"><?php echo $stats['pending_orders']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-title">Đơn hàng đã thanh toán</h6>
                                <h3 class="mb-0"><?php echo $stats['paid_orders']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title">Đơn hàng hoàn thành</h6>
                                <h3 class="mb-0"><?php echo $stats['completed_orders']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    window.location.href = '?status=' + this.value;
});
</script>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 