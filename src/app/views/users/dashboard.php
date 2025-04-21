<?php
$title = "Đơn đã mua";
ob_start();
?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lịch sử đơn hàng đã mua</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày mua</th>
                                    <th>Sản phẩm</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($recentOrders)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Bạn chưa có đơn hàng nào</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($recentOrders as $order): ?>
                                    <tr>
                                        <td>#<?= $order['id'] ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                        <td><?= $order['game_name'] ?> - <?= $order['account_username'] ?></td>
                                        <td><?= number_format(isset($order['amount']) ? $order['amount'] : 0) ?> VNĐ</td>
                                        <td>
                                            <?php 
                                            $statusClass = '';
                                            switch($order['status']) {
                                                case 'pending':
                                                    $statusClass = 'bg-warning';
                                                    $statusText = 'Đang xử lý';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'bg-success';
                                                    $statusText = 'Hoàn thành';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'bg-danger';
                                                    $statusText = 'Đã hủy';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-secondary';
                                                    $statusText = $order['status'];
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= BASE_URL ?>orders/<?= $order['id'] ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
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
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?> 