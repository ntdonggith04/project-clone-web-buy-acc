<?php
$title = "Quản lý tài khoản game";
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Danh sách tài khoản</h5>
                <a href="<?php echo BASE_URL; ?>admin/accounts/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm tài khoản
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select class="form-select" id="gameFilter">
                            <option value="">Tất cả game</option>
                            <?php foreach($games as $game): ?>
                                <option value="<?php echo $game['id']; ?>" <?php echo $game_id == $game['id'] ? 'selected' : ''; ?>>
                                    <?php echo $game['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="rankFilter">
                            <option value="">Tất cả rank</option>
                            <?php foreach($ranks as $rank): ?>
                                <option value="<?php echo $rank['id']; ?>" <?php echo $rank_id == $rank['id'] ? 'selected' : ''; ?>>
                                    <?php echo $rank['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tất cả trạng thái</option>
                            <option value="available" <?php echo $status == 'available' ? 'selected' : ''; ?>>Đang bán</option>
                            <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>Đang xử lý</option>
                            <option value="sold" <?php echo $status == 'sold' ? 'selected' : ''; ?>>Đã bán</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo level..." value="<?php echo $search; ?>">
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
                                <th>Game</th>
                                <th>Level</th>
                                <th>Rank</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($accounts)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Không tìm thấy tài khoản nào</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($accounts as $account): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo BASE_URL; ?>assets/images/games/<?php echo $account['game_image']; ?>" 
                                                     class="rounded me-2" width="30" height="30" alt="<?php echo $account['game_name']; ?>">
                                                <div><?php echo $account['game_name']; ?></div>
                                            </div>
                                        </td>
                                        <td><?php echo $account['level']; ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo $account['rank_name']; ?></span>
                                        </td>
                                        <td><?php echo number_format($account['price']); ?>đ</td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $account['status'] == 'available' ? 'success' : 
                                                    ($account['status'] == 'pending' ? 'warning' : 'danger'); 
                                            ?>">
                                                <?php 
                                                echo $account['status'] == 'available' ? 'Đang bán' : 
                                                    ($account['status'] == 'pending' ? 'Đang xử lý' : 'Đã bán'); 
                                                ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($account['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo BASE_URL; ?>admin/accounts/<?php echo $account['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>admin/accounts/<?php echo $account['id']; ?>/edit" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo BASE_URL; ?>admin/accounts/<?php echo $account['id']; ?>/delete" 
                                                      method="POST" class="d-inline">
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                                        <i class="fas fa-trash"></i>
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
                                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&game_id=<?php echo $game_id; ?>&rank_id=<?php echo $rank_id; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">Trước</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&game_id=<?php echo $game_id; ?>&rank_id=<?php echo $rank_id; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&game_id=<?php echo $game_id; ?>&rank_id=<?php echo $rank_id; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>">Sau</a>
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
</div>

<script>
document.getElementById('gameFilter').addEventListener('change', function() {
    updateFilters();
});

document.getElementById('rankFilter').addEventListener('change', function() {
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
    const gameId = document.getElementById('gameFilter').value;
    const rankId = document.getElementById('rankFilter').value;
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    window.location.href = `?game_id=${gameId}&rank_id=${rankId}&status=${status}&search=${search}`;
}
</script>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 