<?php
$title = "Quản lý đơn hàng";
ob_start();
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Danh sách đơn hàng</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>Đang chờ xử lý</option>
                    <option value="processing" <?php echo $status == 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                    <option value="completed" <?php echo $status == 'completed' ? 'selected' : ''; ?>>Đã hoàn thành</option>
                    <option value="cancelled" <?php echo $status == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo ID đơn hàng..." value="<?php echo $search; ?>">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tài khoản</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo BASE_URL; ?>assets/images/users/<?php echo $order['user_avatar']; ?>" 
                                         class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    <div>
                                        <div><?php echo $order['user_name']; ?></div>
                                        <small class="text-muted"><?php echo $order['user_email']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo BASE_URL; ?>assets/images/games/<?php echo $order['game_image']; ?>" 
                                         class="rounded me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    <div>
                                        <div><?php echo $order['game_name']; ?></div>
                                        <small class="text-muted"><?php echo $order['rank_name']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo number_format($order['total_amount']); ?> VNĐ</td>
                            <td>
                                <?php
                                $statusClass = '';
                                $statusText = '';
                                switch($order['status']) {
                                    case 'pending':
                                        $statusClass = 'bg-warning';
                                        $statusText = 'Đang chờ xử lý';
                                        break;
                                    case 'processing':
                                        $statusClass = 'bg-info';
                                        $statusText = 'Đang xử lý';
                                        break;
                                    case 'completed':
                                        $statusClass = 'bg-success';
                                        $statusText = 'Đã hoàn thành';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-danger';
                                        $statusText = 'Đã hủy';
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>admin/orders/<?php echo $order['id']; ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($order['status'] == 'pending'): ?>
                                    <button type="button" class="btn btn-success btn-sm" onclick="updateStatus(<?php echo $order['id']; ?>, 'processing')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="updateStatus(<?php echo $order['id']; ?>, 'cancelled')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php elseif($order['status'] == 'processing'): ?>
                                    <button type="button" class="btn btn-success btn-sm" onclick="updateStatus(<?php echo $order['id']; ?>, 'completed')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo BASE_URL; ?>admin/orders?page=<?php echo $page-1; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo BASE_URL; ?>admin/orders?page=<?php echo $i; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo BASE_URL; ?>admin/orders?page=<?php echo $page+1; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Tổng đơn hàng</h6>
                <h3 class="mb-0"><?php echo number_format($stats['total_orders']); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">Đang chờ xử lý</h6>
                <h3 class="mb-0"><?php echo number_format($stats['pending_orders']); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Đang xử lý</h6>
                <h3 class="mb-0"><?php echo number_format($stats['processing_orders']); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Đã hoàn thành</h6>
                <h3 class="mb-0"><?php echo number_format($stats['completed_orders']); ?></h3>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const search = document.getElementById('searchInput').value;
    window.location.href = `<?php echo BASE_URL; ?>admin/orders?status=${status}&search=${search}`;
});

document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        const status = document.getElementById('statusFilter').value;
        const search = this.value;
        window.location.href = `<?php echo BASE_URL; ?>admin/orders?status=${status}&search=${search}`;
    }
});

function updateStatus(orderId, status) {
    if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng?')) {
        window.location.href = `<?php echo BASE_URL; ?>admin/orders/${orderId}/update-status?status=${status}`;
    }
}
</script>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 