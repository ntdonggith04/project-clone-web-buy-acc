<?php
$title = "Quản lý giao dịch";
ob_start();
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Danh sách giao dịch</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <select class="form-select" id="typeFilter">
                    <option value="">Tất cả loại</option>
                    <option value="deposit" <?php echo $type == 'deposit' ? 'selected' : ''; ?>>Nạp tiền</option>
                    <option value="withdraw" <?php echo $type == 'withdraw' ? 'selected' : ''; ?>>Rút tiền</option>
                    <option value="purchase" <?php echo $type == 'purchase' ? 'selected' : ''; ?>>Mua tài khoản</option>
                    <option value="sale" <?php echo $type == 'sale' ? 'selected' : ''; ?>>Bán tài khoản</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>Đang chờ xử lý</option>
                    <option value="completed" <?php echo $status == 'completed' ? 'selected' : ''; ?>>Đã hoàn thành</option>
                    <option value="cancelled" <?php echo $status == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo ID giao dịch..." value="<?php echo $search; ?>">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Loại</th>
                        <th>Số tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transactions as $transaction): ?>
                        <tr>
                            <td>#<?php echo $transaction['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo BASE_URL; ?>assets/images/users/<?php echo $transaction['user_avatar']; ?>" 
                                         class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    <div>
                                        <div><?php echo $transaction['user_name']; ?></div>
                                        <small class="text-muted"><?php echo $transaction['user_email']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                $typeClass = '';
                                $typeText = '';
                                switch($transaction['type']) {
                                    case 'deposit':
                                        $typeClass = 'bg-success';
                                        $typeText = 'Nạp tiền';
                                        break;
                                    case 'withdraw':
                                        $typeClass = 'bg-danger';
                                        $typeText = 'Rút tiền';
                                        break;
                                    case 'purchase':
                                        $typeClass = 'bg-info';
                                        $typeText = 'Mua tài khoản';
                                        break;
                                    case 'sale':
                                        $typeClass = 'bg-primary';
                                        $typeText = 'Bán tài khoản';
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $typeClass; ?>"><?php echo $typeText; ?></span>
                            </td>
                            <td><?php echo number_format($transaction['amount']); ?> VNĐ</td>
                            <td>
                                <?php
                                $statusClass = '';
                                $statusText = '';
                                switch($transaction['status']) {
                                    case 'pending':
                                        $statusClass = 'bg-warning';
                                        $statusText = 'Đang chờ xử lý';
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
                            <td><?php echo date('d/m/Y H:i', strtotime($transaction['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>admin/transactions/<?php echo $transaction['id']; ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($transaction['status'] == 'pending'): ?>
                                    <button type="button" class="btn btn-success btn-sm" onclick="updateStatus(<?php echo $transaction['id']; ?>, 'completed')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="updateStatus(<?php echo $transaction['id']; ?>, 'cancelled')">
                                        <i class="fas fa-times"></i>
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
                            <a class="page-link" href="<?php echo BASE_URL; ?>admin/transactions?page=<?php echo $page-1; ?>&type=<?php echo $type; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo BASE_URL; ?>admin/transactions?page=<?php echo $i; ?>&type=<?php echo $type; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo BASE_URL; ?>admin/transactions?page=<?php echo $page+1; ?>&type=<?php echo $type; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">
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
                <h6 class="card-title">Tổng giao dịch</h6>
                <h3 class="mb-0"><?php echo number_format($stats['total_transactions']); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Tổng nạp</h6>
                <h3 class="mb-0"><?php echo number_format($stats['total_deposits']); ?> VNĐ</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6 class="card-title">Tổng rút</h6>
                <h3 class="mb-0"><?php echo number_format($stats['total_withdrawals']); ?> VNĐ</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Tổng mua bán</h6>
                <h3 class="mb-0"><?php echo number_format($stats['total_sales']); ?> VNĐ</h3>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('typeFilter').addEventListener('change', function() {
    updateFilters();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    updateFilters();
});

document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        updateFilters();
    }
});

function updateFilters() {
    const type = document.getElementById('typeFilter').value;
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    window.location.href = `<?php echo BASE_URL; ?>admin/transactions?type=${type}&status=${status}&search=${search}`;
}

function updateStatus(transactionId, status) {
    if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái giao dịch?')) {
        window.location.href = `<?php echo BASE_URL; ?>admin/transactions/${transactionId}/update-status?status=${status}`;
    }
}
</script>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 