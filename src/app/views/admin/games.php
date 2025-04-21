<?php
$title = "Quản lý game";
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Danh sách game</h5>
                <a href="<?php echo BASE_URL; ?>admin/games/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm game
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Đang hoạt động</option>
                            <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Đã ẩn</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo tên game..." value="<?php echo $search; ?>">
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
                                <th>Ảnh</th>
                                <th>Tên game</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($games)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Không tìm thấy game nào</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($games as $game): ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo BASE_URL; ?>assets/images/games/<?php echo $game['image']; ?>" 
                                                 class="rounded" width="50" height="50" alt="<?php echo $game['name']; ?>">
                                        </td>
                                        <td><?php echo $game['name']; ?></td>
                                        <td><?php echo $game['description']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $game['status'] == 'active' ? 'success' : 'danger'; ?>">
                                                <?php echo $game['status'] == 'active' ? 'Đang hoạt động' : 'Đã ẩn'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($game['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo BASE_URL; ?>admin/games/<?php echo $game['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>admin/games/<?php echo $game['id']; ?>/edit" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo BASE_URL; ?>admin/games/<?php echo $game['id']; ?>/toggle-status" 
                                                      method="POST" class="d-inline">
                                                    <button type="submit" class="btn btn-sm <?php echo $game['status'] == 'active' ? 'btn-danger' : 'btn-success'; ?>" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn <?php echo $game['status'] == 'active' ? 'ẩn' : 'hiện'; ?> game này?')">
                                                        <i class="fas <?php echo $game['status'] == 'active' ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                                                    </button>
                                                </form>
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
                                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">Trước</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">Sau</a>
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
                                <h6 class="card-title">Tổng số game</h6>
                                <h3 class="mb-0"><?php echo $stats['total_games']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title">Game đang hoạt động</h6>
                                <h3 class="mb-0"><?php echo $stats['active_games']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h6 class="card-title">Game đã ẩn</h6>
                                <h3 class="mb-0"><?php echo $stats['inactive_games']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-title">Tổng tài khoản</h6>
                                <h3 class="mb-0"><?php echo $stats['total_accounts']; ?></h3>
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
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    window.location.href = `?status=${status}&search=${search}`;
}
</script>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 