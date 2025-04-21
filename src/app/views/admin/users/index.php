<?php
$title = "Quản lý người dùng | " . SITE_NAME;
ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Danh sách người dùng</h6>
                    <div class="d-flex align-items-center">
                        <a href="<?= BASE_URL ?>admin/users/create" class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-user-plus"></i> Thêm người dùng
                        </a>
                        <form class="d-flex" method="GET" action="">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Tìm kiếm người dùng..." value="<?= $search ?? '' ?>">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Bộ lọc -->
                    <div class="px-4 py-3">
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-select form-select-sm" id="roleFilter" name="role">
                                    <option value="">Tất cả vai trò</option>
                                    <option value="admin" <?= isset($_GET['role']) && $_GET['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="user" <?= isset($_GET['role']) && $_GET['role'] == 'user' ? 'selected' : '' ?>>Người dùng</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="applyFilter" class="btn btn-sm btn-info">
                                    <i class="fas fa-filter"></i> Lọc
                                </button>
                                <a href="<?= BASE_URL ?>admin/users" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-sync"></i> Đặt lại
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive p-0">
                        <?php if (empty($users)) : ?>
                            <div class="text-center py-4">
                                <p class="text-muted">Không có người dùng nào</p>
                            </div>
                        <?php else : ?>
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Người dùng</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số điện thoại</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ngày tạo</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vai trò</th>
                                        <th class="text-secondary opacity-7">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user) : ?>
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 px-3"><?= $user['id'] ?></p>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?= $user['username'] ?></h6>
                                                        <p class="text-xs text-secondary mb-0"><?= $user['fullname'] ?? 'Chưa cập nhật' ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $user['email'] ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= isset($user['phone']) && !empty($user['phone']) ? $user['phone'] : 'Chưa cập nhật' ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <?php if ($user['role'] == 'admin') : ?>
                                                    <span class="badge badge-sm bg-gradient-success">Admin</span>
                                                <?php else : ?>
                                                    <span class="badge badge-sm bg-gradient-secondary">Người dùng</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group">
                                                    <a href="<?= BASE_URL ?>admin/users/view/<?= $user['id'] ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>admin/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-danger" href="<?= BASE_URL ?>admin/users/delete/<?= $user['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($users) && $total_pages > 1) : ?>
                <div class="d-flex justify-content-center mt-3">
                    <nav>
                        <ul class="pagination pagination-sm">
                            <?php if ($current_page > 1) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASE_URL ?>admin/users?page=<?= $current_page - 1 ?><?= isset($search) ? '&search=' . $search : '' ?><?= isset($_GET['role']) ? '&role=' . $_GET['role'] : '' ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL ?>admin/users?page=<?= $i ?><?= isset($search) ? '&search=' . $search : '' ?><?= isset($_GET['role']) ? '&role=' . $_GET['role'] : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASE_URL ?>admin/users?page=<?= $current_page + 1 ?><?= isset($search) ? '&search=' . $search : '' ?><?= isset($_GET['role']) ? '&role=' . $_GET['role'] : '' ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>

            <!-- Thống kê -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Thống kê người dùng</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row g-3 p-4">
                        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                            <div class="card bg-gradient-primary">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Tổng người dùng</p>
                                                <h5 class="text-white font-weight-bolder mb-0">
                                                    <?= isset($stats['total_users']) ? $stats['total_users'] : count($users) ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center rounded-circle">
                                                <i class="fas fa-users text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                            <div class="card bg-gradient-success">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Người dùng thường</p>
                                                <h5 class="text-white font-weight-bolder mb-0">
                                                    <?= isset($stats['user_users']) ? $stats['user_users'] : '0' ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center rounded-circle">
                                                <i class="fas fa-user text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="card bg-gradient-info">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Quản trị viên</p>
                                                <h5 class="text-white font-weight-bolder mb-0">
                                                    <?= isset($stats['admin_users']) ? $stats['admin_users'] : '0' ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center rounded-circle">
                                                <i class="fas fa-user-shield text-info"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Xử lý bộ lọc
document.getElementById('applyFilter').addEventListener('click', function() {
    const roleFilter = document.getElementById('roleFilter').value;
    const searchParam = "<?= isset($search) ? $search : '' ?>";
    
    let url = "<?= BASE_URL ?>admin/users?";
    if(roleFilter) url += `role=${roleFilter}&`;
    if(searchParam) url += `search=${searchParam}&`;
    
    // Xóa dấu & cuối cùng nếu có
    if(url.endsWith('&')) url = url.slice(0, -1);
    
    window.location.href = url;
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin.php';
?> 