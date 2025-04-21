<?php
$title = "Thanh toán đơn hàng";
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Thanh Toán Đơn Hàng #<?php echo $order->id; ?></h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Thông tin đơn hàng</h5>
                            <table class="table">
                                <tr>
                                    <th>Mã đơn hàng:</th>
                                    <td>#<?php echo $order->id; ?></td>
                                </tr>
                                <tr>
                                    <th>Ngày đặt:</th>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td>
                                        <span class="badge bg-warning">Chờ thanh toán</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tổng tiền:</th>
                                    <td class="fw-bold"><?php echo number_format($order->amount); ?> VNĐ</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="mb-3">Chọn phương thức thanh toán</h5>
                            <div class="payment-methods">
                                <div class="card mb-2">
                                    <div class="card-body p-3">
                                        <a href="<?php echo BASE_URL; ?>payment/vnpay/<?php echo $order->id; ?>" class="text-decoration-none">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <img src="<?php echo BASE_URL; ?>assets/images/vnpay.png" alt="VNPay" height="40">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Thanh toán qua VNPay</h6>
                                                    <p class="text-muted small mb-0">Thanh toán bằng ATM, VISA, Mastercard, JCB, QR Code,...</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card mb-2">
                                    <div class="card-body p-3">
                                        <a href="#" class="text-decoration-none">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <img src="<?php echo BASE_URL; ?>assets/images/momo.png" alt="MoMo" height="40">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Thanh toán qua MoMo</h6>
                                                    <p class="text-muted small mb-0">Thanh toán bằng ví điện tử MoMo</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card">
                                    <div class="card-body p-3">
                                        <a href="#" class="text-decoration-none">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <img src="<?php echo BASE_URL; ?>assets/images/bank.png" alt="Chuyển khoản" height="40">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Chuyển khoản ngân hàng</h6>
                                                    <p class="text-muted small mb-0">Thanh toán bằng cách chuyển khoản qua ngân hàng</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <p class="mb-0"><i class="fas fa-info-circle me-2"></i> Sau khi chọn phương thức thanh toán, bạn sẽ được chuyển đến trang thanh toán tương ứng.</p>
                    </div>
                    
                    <div class="text-end">
                        <a href="<?php echo BASE_URL; ?>accounts" class="btn btn-outline-secondary">Quay lại</a>
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