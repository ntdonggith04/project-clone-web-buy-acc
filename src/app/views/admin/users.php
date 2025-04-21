<?php
$title = "Quản lý người dùng";
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Danh sách người dùng</h5>
                <a href="<?php echo BASE_URL; ?>admin/users/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm người dùng
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select class="form-select" id="roleFilter">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Quản trị viên</option>
                            <option value="seller" <?php echo $role == 'seller' ? 'selected' : ''; ?>>Người bán</option>
                            <option value="user" <?php echo $role == 'user' ? 'selected' : ''; ?>>Người dùng</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Đang hoạt động</option>
                            <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Đã khóa</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo tên hoặc email..." value="<?php echo $search; ?>">
                            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ảnh đại diện</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($users)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Không tìm thấy người dùng nào</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($users as $user): ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo BASE_URL; ?>assets/images/avatars/<?php echo $user['avatar']; ?>" 
                                                 class="rounded-circle" width="40" height="40" alt="Avatar">
                                        </td>
                                        <td><?php echo $user['name']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td>
                                            <?php
                                            $role_class = '';
                                            switch($user['role']) {
                                                case 'admin':
                                                    $role_class = 'danger';
                                                    break;
                                                case 'seller':
                                                    $role_class = 'warning';
                                                    break;
                                                case 'user':
                                                    $role_class = 'info';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $role_class; ?>">
                                                <?php
                                                switch($user['role']) {
                                                    case 'admin':
                                                        echo 'Quản trị viên';
                                                        break;
                                                    case 'seller':
                                                        echo 'Người bán';
                                                        break;
                                                    case 'user':
                                                        echo 'Người dùng';
                                                        break;
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['status'] == 'active' ? 'success' : 'danger'; ?>">
                                                <?php echo $user['status'] == 'active' ? 'Đang hoạt động' : 'Đã khóa'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo BASE_URL; ?>admin/users/<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>admin/users/<?php echo $user['id']; ?>/edit" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if($user['id'] != $_SESSION['user']['id']): ?>
                                                    <form action="<?php echo BASE_URL; ?>admin/users/<?php echo $user['id']; ?>/toggle-status" 
                                                          method="POST" class="d-inline">
                                                        <button type="submit" class="btn btn-sm <?php echo $user['status'] == 'active' ? 'btn-danger' : 'btn-success'; ?>" 
                                                                onclick="return confirm('Bạn có chắc chắn muốn <?php echo $user['status'] == 'active' ? 'khóa' : 'mở khóa'; ?> tài khoản này?')">
                                                            <i class="fas <?php echo $user['status'] == 'active' ? 'fa-lock' : 'fa-unlock'; ?>"></i>
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
                                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&role=<?php echo $role; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">Trước</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&role=<?php echo $role; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&role=<?php echo $role; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">Sau</a>
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
                                <h6 class="card-title">Tổng số người dùng</h6>
                                <h3 class="mb-0"><?php echo $stats['total_users']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h6 class="card-title">Quản trị viên</h6>
                                <h3 class="mb-0"><?php echo $stats['admin_users']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6 class="card-title">Người bán</h6>
                                <h3 class="mb-0"><?php echo $stats['seller_users']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-title">Người dùng</h6>
                                <h3 class="mb-0"><?php echo $stats['normal_users']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('roleFilter').addEventListener('change', function() {
    updateFilters();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    updateFilters();
});

document.getElementById('searchButton').addEventListener('click', function() {
    updateFilters();
});

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        updateFilters();
    }
});

function updateFilters() {
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    window.location.href = `?role=${role}&status=${status}&search=${search}`;
}
</script>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 