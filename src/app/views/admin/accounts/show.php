<?php
$title = "Chi tiết tài khoản #" . $account['id'];
ob_start();
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Chi tiết tài khoản #<?php echo $account['id']; ?></h5>
                    <div>
                        <a href="<?php echo BASE_URL; ?>admin/accounts/<?php echo $account['id']; ?>/edit" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Game</h6>
                        <div class="d-flex align-items-center">
                            <img src="<?php echo BASE_URL; ?>assets/images/games/<?php echo $account['game_image']; ?>" 
                                 class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <span><?php echo $account['game_name']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Rank</h6>
                        <span class="badge bg-primary"><?php echo $account['rank_name']; ?></span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Level</h6>
                        <p><?php echo $account['level']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Giá</h6>
                        <p><?php echo number_format($account['price']); ?> VNĐ</p>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Thông tin đăng nhập</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tên đăng nhập:</strong> <?php echo $account['username']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mật khẩu:</strong> <?php echo $account['password']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Thông tin bổ sung</h6>
                    <p><?php echo nl2br($account['info']); ?></p>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Hình ảnh tài khoản</h6>
                    <div class="row">
                        <?php foreach($account['images'] as $image): ?>
                            <div class="col-md-3 mb-2">
                                <img src="<?php echo BASE_URL; ?>assets/images/accounts/<?php echo $image; ?>" 
                                     class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Trạng thái</h6>
                    <?php
                    $statusClass = '';
                    $statusText = '';
                    switch($account['status']) {
                        case 'available':
                            $statusClass = 'bg-success';
                            $statusText = 'Đang bán';
                            break;
                        case 'pending':
                            $statusClass = 'bg-warning';
                            $statusText = 'Đang xử lý';
                            break;
                        case 'sold':
                            $statusClass = 'bg-danger';
                            $statusText = 'Đã bán';
                            break;
                    }
                    ?>
                    <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Thông tin tạo</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($account['created_at'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ngày cập nhật:</strong> <?php echo date('d/m/Y H:i', strtotime($account['updated_at'])); ?></p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?php echo BASE_URL; ?>admin/accounts" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Bạn có chắc chắn muốn xóa tài khoản này?')) {
        window.location.href = '<?php echo BASE_URL; ?>admin/accounts/<?php echo $account['id']; ?>/delete';
    }
}
</script>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 