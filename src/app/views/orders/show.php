<?php
$title = "Chi tiết đơn hàng #" . $order->id;
ob_start();
?>

<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chi tiết đơn hàng #<?= $order->id ?></h5>
                    <a href="<?= BASE_URL ?>dashboard" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
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
                                        <th>Trạng thái</th>
                                        <td>
                                            <?php 
                                            $statusClass = '';
                                            $statusText = '';
                                            switch($order->status) {
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
                                                    $statusText = $order->status;
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái thanh toán</th>
                                        <td>
                                            <?php 
                                            $paymentStatusClass = '';
                                            $paymentStatusText = '';
                                            switch($order->payment_status) {
                                                case 'pending':
                                                    $paymentStatusClass = 'bg-warning';
                                                    $paymentStatusText = 'Chờ thanh toán';
                                                    break;
                                                case 'paid':
                                                    $paymentStatusClass = 'bg-success';
                                                    $paymentStatusText = 'Đã thanh toán';
                                                    break;
                                                case 'failed':
                                                    $paymentStatusClass = 'bg-danger';
                                                    $paymentStatusText = 'Thanh toán thất bại';
                                                    break;
                                                default:
                                                    $paymentStatusClass = 'bg-secondary';
                                                    $paymentStatusText = $order->payment_status;
                                            }
                                            ?>
                                            <span class="badge <?= $paymentStatusClass ?>"><?= $paymentStatusText ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Phương thức thanh toán</th>
                                        <td><?= $order->payment_method ?></td>
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
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Tóm tắt thanh toán</h6>
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
                    
                    <?php if($order->payment_status === 'paid'): ?>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h5><i class="fas fa-check-circle me-2"></i> Thông tin đăng nhập tài khoản game</h5>
                                <div class="mt-3">
                                    <?php if(!empty($items) && isset($items[0]['password']) && $items[0]['password']): ?>
                                        <p>Tài khoản: <strong><?= $items[0]['username'] ?></strong></p>
                                        <p>Mật khẩu: <strong><?= $items[0]['password'] ?></strong></p>
                                    <?php else: ?>
                                        <p>Thông tin đăng nhập sẽ được gửi đến email của bạn trong vòng 24 giờ.</p>
                                        <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua email: support@example.com</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?> 