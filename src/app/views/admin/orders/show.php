<?php
$title = "Chi tiết đơn hàng #" . $order->id;
ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Chi tiết đơn hàng #<?= $order->id ?></h6>
                    <a href="<?= BASE_URL ?>admin/orders" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Thông tin đơn hàng</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 40%">Mã đơn hàng</th>
                                        <td>#<?= $order->id ?></td>
                                    </tr>
                                    <tr>
                                        <th>Ngày đặt</th>
                                        <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                                    </tr>
                                    <tr>
                                        <th>ID người mua</th>
                                        <td><?= $order->user_id ?></td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái</th>
                                        <td>
                                            <?php 
                                            $statusClass = '';
                                            $statusText = '';
                                            switch($order->status) {
                                                case 'pending':
                                                    $statusClass = 'badge-warning';
                                                    $statusText = 'Đang xử lý';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'badge-success';
                                                    $statusText = 'Hoàn thành';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'badge-danger';
                                                    $statusText = 'Đã hủy';
                                                    break;
                                                default:
                                                    $statusClass = 'badge-secondary';
                                                    $statusText = $order->status;
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Thông tin tài khoản game</h6>
                            <?php if(!empty($items)): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">Tên game</th>
                                            <td><?= $items[0]['game_name'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tài khoản</th>
                                            <td><?= $items[0]['username'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Mật khẩu</th>
                                            <td><?= $items[0]['password'] ?? 'N/A' ?></td>
                                        </tr>
                                        <?php if(isset($items[0]['game_rank']) && $items[0]['game_rank']): ?>
                                        <tr>
                                            <th>Rank</th>
                                            <td><?= $items[0]['game_rank'] ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if(isset($items[0]['game_server']) && $items[0]['game_server']): ?>
                                        <tr>
                                            <th>Server</th>
                                            <td><?= $items[0]['game_server'] ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th>Mô tả</th>
                                            <td><?= $items[0]['description'] ?? 'N/A' ?></td>
                                        </tr>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">Không có thông tin chi tiết về tài khoản game</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Tóm tắt đơn hàng</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>Tổng tiền</h5>
                                        <h5 class="text-primary"><?= number_format(isset($order->total_amount) ? $order->total_amount : (isset($order->amount) ? $order->amount : 0)) ?> VNĐ</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="<?= BASE_URL ?>admin/orders" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                    </a>
                                </div>
                                <div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                            Cập nhật trạng thái
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?= BASE_URL ?>admin/orders/status/<?= $order->id ?>?status=pending">Đang xử lý</a>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>admin/orders/status/<?= $order->id ?>?status=completed">Hoàn thành</a>
                                            <a class="dropdown-item" href="<?= BASE_URL ?>admin/orders/status/<?= $order->id ?>?status=cancelled">Đã hủy</a>
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

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin.php';
?> 