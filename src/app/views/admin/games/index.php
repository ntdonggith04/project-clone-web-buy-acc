<?php
$title = "Quản lý danh mục game | " . SITE_NAME;
ob_start();
?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Quản lý danh mục game</h1>
    <p class="mb-4">Quản lý tất cả danh mục game trong hệ thống</p>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Thẻ tổng quan -->
    <div class="row">
        <div class="col-xl-6 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Tổng số danh mục</h5>
                            <h3 class="display-4"><?= $total_games ?></h3>
                        </div>
                        <i class="fas fa-gamepad fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Tổng số tài khoản game</h5>
                            <h3 class="display-4"><?= $total_accounts ?></h3>
                        </div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách danh mục game</h6>
            <a href="<?= BASE_URL ?>admin/games/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
        <div class="card-body">
            <!-- Form tìm kiếm -->
            <div class="mb-3">
                <form method="GET" action="<?= BASE_URL ?>admin/games" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Hình ảnh</th>
                            <th>Trạng thái</th>
                            <th>Số tài khoản</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($games)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có danh mục nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($games as $game): ?>
                                <tr>
                                    <td><?= $game['id'] ?></td>
                                    <td><?= htmlspecialchars($game['name']) ?></td>
                                    <td>
                                        <?php if(!empty($game['image'])): ?>
                                            <img src="<?= BASE_URL ?>uploads/games/<?= $game['image'] ?>" alt="<?= htmlspecialchars($game['name']) ?>" width="50">
                                        <?php else: ?>
                                            <img src="<?= BASE_URL ?>assets/images/no-image.png" alt="No Image" width="50">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($game['status'] == 1): ?>
                                            <span class="badge badge-success">Kích hoạt</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= isset($game['account_count']) ? $game['account_count'] : '0' ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($game['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>admin/games/edit/<?= $game['id'] ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a onclick="return confirm('Bạn có chắc chắn muốn xóa?')" href="<?= BASE_URL ?>admin/games/delete/<?= $game['id'] ?>" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin.php';
?> 