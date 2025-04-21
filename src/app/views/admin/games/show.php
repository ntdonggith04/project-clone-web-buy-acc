<?php
$title = "Chi tiết game";
ob_start();
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Chi tiết game</h5>
                <div class="btn-group">
                    <a href="<?php echo BASE_URL; ?>admin/games/<?php echo $game['id']; ?>/edit" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <form action="<?php echo BASE_URL; ?>admin/games/<?php echo $game['id']; ?>/toggle-status" 
                          method="POST" class="d-inline">
                        <button type="submit" class="btn <?php echo $game['status'] == 'active' ? 'btn-danger' : 'btn-success'; ?>" 
                                onclick="return confirm('Bạn có chắc chắn muốn <?php echo $game['status'] == 'active' ? 'ẩn' : 'hiện'; ?> game này?')">
                            <i class="fas <?php echo $game['status'] == 'active' ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                            <?php echo $game['status'] == 'active' ? 'Ẩn game' : 'Hiện game'; ?>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <img src="<?php echo BASE_URL; ?>assets/images/games/<?php echo $game['image']; ?>" 
                             class="rounded" width="200" height="200" alt="<?php echo $game['name']; ?>">
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3"><?php echo $game['name']; ?></h4>
                        <p class="text-muted mb-3"><?php echo $game['description']; ?></p>
                        <div class="mb-3">
                            <span class="badge bg-<?php echo $game['status'] == 'active' ? 'success' : 'danger'; ?>">
                                <?php echo $game['status'] == 'active' ? 'Đang hoạt động' : 'Đã ẩn'; ?>
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Level:</strong> <?php echo $game['min_level']; ?> - <?php echo $game['max_level']; ?>
                        </div>
                        <div class="mb-3">
                            <strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($game['created_at'])); ?>
                        </div>
                        <div class="mb-3">
                            <strong>Cập nhật lần cuối:</strong> <?php echo date('d/m/Y H:i', strtotime($game['updated_at'])); ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Các rank có thể có</h5>
                        <div class="row">
                            <?php foreach($game_ranks as $rank): ?>
                                <div class="col-md-3 mb-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo $rank['name']; ?></h6>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mb-3">Thống kê tài khoản</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Tổng số tài khoản</h6>
                                        <h3 class="mb-0"><?php echo $stats['total_accounts']; ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Tài khoản đang bán</h6>
                                        <h3 class="mb-0"><?php echo $stats['available_accounts']; ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Tài khoản đang xử lý</h6>
                                        <h3 class="mb-0"><?php echo $stats['pending_accounts']; ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Tài khoản đã bán</h6>
                                        <h3 class="mb-0"><?php echo $stats['sold_accounts']; ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo BASE_URL; ?>admin/games" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 