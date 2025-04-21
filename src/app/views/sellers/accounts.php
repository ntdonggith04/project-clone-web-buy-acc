<?php
$title = "Quản lý tài khoản game";
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Danh sách tài khoản game</h5>
                <a href="<?php echo BASE_URL; ?>accounts/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm tài khoản
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Game</th>
                                <th>Level/Rank</th>
                                <th>Server</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($accounts)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Chưa có tài khoản nào</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($accounts as $account): ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo BASE_URL; ?>assets/images/games/<?php echo $account['image']; ?>" 
                                                 class="rounded" width="50" height="50" alt="<?php echo $account['game_name']; ?>">
                                        </td>
                                        <td><?php echo $account['game_name']; ?></td>
                                        <td>
                                            Level: <?php echo $account['level']; ?><br>
                                            Rank: <?php echo $account['rank']; ?>
                                        </td>
                                        <td><?php echo $account['server']; ?></td>
                                        <td><?php echo number_format($account['price']); ?> VNĐ</td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            switch($account['status']) {
                                                case 'available':
                                                    $status_class = 'success';
                                                    break;
                                                case 'sold':
                                                    $status_class = 'danger';
                                                    break;
                                                case 'pending':
                                                    $status_class = 'warning';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $status_class; ?>">
                                                <?php
                                                switch($account['status']) {
                                                    case 'available':
                                                        echo 'Có sẵn';
                                                        break;
                                                    case 'sold':
                                                        echo 'Đã bán';
                                                        break;
                                                    case 'pending':
                                                        echo 'Đang xử lý';
                                                        break;
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($account['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo BASE_URL; ?>accounts/<?php echo $account['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>accounts/<?php echo $account['id']; ?>/edit" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo BASE_URL; ?>accounts/<?php echo $account['id']; ?>/delete" 
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
                                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>">Trước</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Sau</a>
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
                                <h6 class="card-title">Tài khoản có sẵn</h6>
                                <h3 class="mb-0"><?php echo $stats['available_accounts']; ?></h3>
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
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6 class="card-title">Tài khoản đang xử lý</h6>
                                <h3 class="mb-0"><?php echo $stats['pending_accounts']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'layouts/main.php';
?> 