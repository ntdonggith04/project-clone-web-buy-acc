<?php
$title = "Chi tiết giao dịch #" . $transaction['id'];
ob_start();
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Chi tiết giao dịch #<?php echo $transaction['id']; ?></h5>
                    <div>
                        <?php if($transaction['status'] == 'pending'): ?>
                            <button type="button" class="btn btn-success btn-sm" onclick="updateStatus('completed')">
                                <i class="fas fa-check"></i> Xác nhận giao dịch
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="updateStatus('cancelled')">
                                <i class="fas fa-times"></i> Hủy giao dịch
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Thông tin người dùng</h6>
                        <div class="d-flex align-items-center mb-2">
                            <img src="<?php echo BASE_URL; ?>assets/images/users/<?php echo $transaction['user_avatar']; ?>" 
                                 class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <div><?php echo $transaction['user_name']; ?></div>
                                <small class="text-muted"><?php echo $transaction['user_email']; ?></small>
                            </div>
                        </div>
                        <p><strong>Điện thoại:</strong> <?php echo $transaction['user_phone']; ?></p>
                        <p><strong>Địa chỉ:</strong> <?php echo $transaction['user_address']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Thông tin giao dịch</h6>
                        <p><strong>Loại giao dịch:</strong> 
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
                        </p>
                        <p><strong>Trạng thái:</strong> 
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
                        </p>
                        <p><strong>Số tiền:</strong> <?php echo number_format($transaction['amount']); ?> VNĐ</p>
                        <p><strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($transaction['created_at'])); ?></p>
                        <p><strong>Ngày cập nhật:</strong> <?php echo date('d/m/Y H:i', strtotime($transaction['updated_at'])); ?></p>
                    </div>
                </div>

                <?php if($transaction['type'] == 'purchase' || $transaction['type'] == 'sale'): ?>
                    <div class="mb-4">
                        <h6 class="text-muted">Thông tin tài khoản</h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="<?php echo BASE_URL; ?>assets/images/games/<?php echo $transaction['game_image']; ?>" 
                                         class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <div><?php echo $transaction['game_name']; ?></div>
                                        <small class="text-muted"><?php echo $transaction['rank_name']; ?></small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Level:</strong> <?php echo $transaction['level']; ?></p>
                                        <p><strong>Giá:</strong> <?php echo number_format($transaction['price']); ?> VNĐ</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Tên đăng nhập:</strong> <?php echo $transaction['username']; ?></p>
                                        <p><strong>Mật khẩu:</strong> <?php echo $transaction['password']; ?></p>
                                    </div>
                                </div>
                                <?php if($transaction['info']): ?>
                                    <div class="mt-3">
                                        <p><strong>Thông tin bổ sung:</strong></p>
                                        <p><?php echo nl2br($transaction['info']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Hình ảnh tài khoản</h6>
                        <div class="row">
                            <?php foreach($transaction['images'] as $image): ?>
                                <div class="col-md-3 mb-2">
                                    <img src="<?php echo BASE_URL; ?>assets/images/accounts/<?php echo $image; ?>" 
                                         class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($transaction['note']): ?>
                    <div class="mb-4">
                        <h6 class="text-muted">Ghi chú</h6>
                        <p><?php echo nl2br($transaction['note']); ?></p>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between">
                    <a href="<?php echo BASE_URL; ?>admin/transactions" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái giao dịch?')) {
        window.location.href = '<?php echo BASE_URL; ?>admin/transactions/<?php echo $transaction['id']; ?>/update-status?status=' + status;
    }
}
</script>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 