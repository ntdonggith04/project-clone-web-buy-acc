<?php
$title = "Quản lý đơn hàng | " . SITE_NAME;
ob_start();
?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Quản lý đơn hàng</h1>
    <p class="mb-4">Quản lý tất cả đơn hàng trong hệ thống</p>

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

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Tài khoản game</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($stmt as $order): ?>
                            <tr>
                                <td><?= $order['id'] ?></td>
                                <td><?= $order['user_id'] ?></td>
                                <td><?= $order['game_account_id'] ?></td>
                                <td><?= number_format(isset($order['total_amount']) && $order['total_amount'] !== null ? $order['total_amount'] : (isset($order['amount']) && $order['amount'] !== null ? $order['amount'] : 0)) ?> VND</td>
                                <td>
                                    <?php if($order['status'] == 'pending'): ?>
                                        <span class="badge badge-warning">Đang xử lý</span>
                                    <?php elseif($order['status'] == 'completed'): ?>
                                        <span class="badge badge-success">Hoàn thành</span>
                                    <?php elseif($order['status'] == 'cancelled'): ?>
                                        <span class="badge badge-danger">Đã hủy</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>admin/orders/<?= $order['id'] ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                            Trạng thái
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?= BASE_URL ?>admin/orders/status/<?= $order['id'] ?>?status=pending">Đang xử lý</a>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>admin/orders/status/<?= $order['id'] ?>?status=completed">Hoàn thành</a>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>admin/orders/status/<?= $order['id'] ?>?status=cancelled">Đã hủy</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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