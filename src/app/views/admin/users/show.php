<?php
$title = "Chi tiết người dùng";
ob_start();
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Thông tin người dùng</h5>
                <div class="btn-group">
                    <?php if(isset($_SESSION['user']) && isset($_SESSION['user']['id']) && $user['id'] != $_SESSION['user']['id']): ?>
                        <form action="<?php echo BASE_URL; ?>admin/users/delete/<?php echo $user['id']; ?>" 
                              method="POST" class="d-inline">
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                <i class="fas fa-trash"></i> Xóa tài khoản
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted">Tên đăng nhập</h6>
                                <p><?php echo isset($user['username']) ? $user['username'] : 'Chưa cập nhật'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Họ tên</h6>
                                <p><?php echo isset($user['fullname']) && $user['fullname'] ? $user['fullname'] : 'Chưa cập nhật'; ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted">Email</h6>
                                <p><?php echo isset($user['email']) ? $user['email'] : 'Chưa cập nhật'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Số điện thoại</h6>
                                <p><?php echo isset($user['phone']) && $user['phone'] ? $user['phone'] : 'Chưa cập nhật'; ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted">Vai trò</h6>
                                <span class="badge bg-<?php 
                                    echo isset($user['role']) ? ($user['role'] == 'admin' ? 'danger' : 
                                        ($user['role'] == 'seller' ? 'warning' : 'info')) : 'secondary'; 
                                ?>">
                                    <?php 
                                    echo isset($user['role']) ? ($user['role'] == 'admin' ? 'Quản trị viên' : 
                                        ($user['role'] == 'seller' ? 'Người bán' : 'Người dùng')) : 'Không xác định'; 
                                    ?>
                                </span>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Ngày tạo</h6>
                                <p><?php echo isset($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : 'Không xác định'; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-muted">Lần cập nhật cuối</h6>
                                <p><?php echo isset($user['updated_at']) ? date('d/m/Y H:i', strtotime($user['updated_at'])) : 'Không xác định'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if(isset($user['role']) && $user['role'] == 'seller' && isset($stats)): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Thống kê người bán</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">Tổng tài khoản</h6>
                                            <h3 class="mb-0"><?php echo isset($stats['total_accounts']) ? $stats['total_accounts'] : 0; ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">Tài khoản đang bán</h6>
                                            <h3 class="mb-0"><?php echo isset($stats['available_accounts']) ? $stats['available_accounts'] : 0; ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">Tài khoản đã bán</h6>
                                            <h3 class="mb-0"><?php echo isset($stats['sold_accounts']) ? $stats['sold_accounts'] : 0; ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">Tổng doanh thu</h6>
                                            <h3 class="mb-0"><?php echo isset($stats['total_revenue']) ? number_format($stats['total_revenue']) : 0; ?> VNĐ</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Lịch sử hoạt động</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Hoạt động</th>
                                        <th>Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!isset($activities) || empty($activities)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Chưa có hoạt động nào</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($activities as $activity): ?>
                                            <tr>
                                                <td><?php echo isset($activity['created_at']) ? date('d/m/Y H:i', strtotime($activity['created_at'])) : ''; ?></td>
                                                <td><?php echo isset($activity['action']) ? $activity['action'] : ''; ?></td>
                                                <td><?php echo isset($activity['details']) ? $activity['details'] : ''; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin.php';
?> 