<?php
$title = "Sandbox Thanh Toán VNPay";
ob_start();
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">VNPAY Sandbox</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="<?php echo BASE_URL; ?>assets/images/vnpay-logo.png" alt="VNPay Logo" class="img-fluid" style="max-width: 200px;">
                        <h4 class="mt-3">Thanh toán đơn hàng #<?php echo $order->id; ?></h4>
                    </div>
                    
                    <div class="alert alert-info">
                        <p class="mb-0"><i class="fas fa-info-circle me-2"></i> Đây là môi trường sandbox để giả lập thanh toán qua VNPay</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Thông tin đơn hàng</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Mã đơn hàng:</th>
                                    <td>#<?php echo $order->id; ?></td>
                                </tr>
                                <tr>
                                    <th>Mô tả:</th>
                                    <td><?php echo $order->title ?? "Thanh toán tài khoản game"; ?></td>
                                </tr>
                                <tr>
                                    <th>Số tiền:</th>
                                    <td class="fw-bold"><?php echo number_format($order->amount); ?> VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Thời gian:</th>
                                    <td><?php echo date('d/m/Y H:i:s'); ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="mb-3">Thông tin thanh toán</h5>
                            <form id="paymentForm" action="<?php echo $callback_url; ?>" method="POST">
                                <div class="mb-3">
                                    <label for="cardNumber" class="form-label">Số thẻ</label>
                                    <input type="text" class="form-control" id="cardNumber" value="9704 0000 0000 0018" readonly>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="expDate" class="form-label">Ngày hết hạn</label>
                                        <input type="text" class="form-control" id="expDate" value="03/07" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" value="123" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="cardName" class="form-label">Tên chủ thẻ</label>
                                    <input type="text" class="form-control" id="cardName" value="NGUYEN VAN A" readonly>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <p class="mb-0">Đây là thông tin thẻ test, không phải thẻ thật của bạn</p>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Xác nhận thanh toán</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Thông báo giao dịch</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Mã tra cứu:</span>
                                <span>SANDBOX_<?php echo strtoupper(substr(md5(time()), 0, 8)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Thời gian giao dịch:</span>
                                <span><?php echo date('d/m/Y h:i:s A'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <p class="text-muted mb-0">VNPAY - Thanh toán trực tuyến <span class="text-danger">(Sandbox Mode)</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Thêm hiệu ứng quay về trang accounts sau khi thanh toán
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Hiển thị thông báo đang xử lý
        const button = this.querySelector('button[type="submit"]');
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Đang xử lý...';
        
        // Chuyển đến trang callback sau 2 giây
        setTimeout(() => {
            this.submit();
        }, 2000);
    });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?> 