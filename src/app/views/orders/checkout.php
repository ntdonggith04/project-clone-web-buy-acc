<?php
$title = "Thanh toán";
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Thanh Toán</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($order) && isset($game_account)): ?>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Thông tin tài khoản game</h5>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <p><strong>Tên tài khoản:</strong> <?php echo $game_account['title']; ?></p>
                                        <?php if(!empty($game_account['game_rank'])): ?>
                                            <p><strong>Rank:</strong> <?php echo $game_account['game_rank']; ?></p>
                                        <?php endif; ?>
                                        <?php if(!empty($game_account['server'])): ?>
                                            <p><strong>Server:</strong> <?php echo $game_account['server']; ?></p>
                                        <?php endif; ?>
                                        <p><strong>Giá:</strong> <?php echo number_format($order['amount']); ?> VNĐ</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5>Hình thức thanh toán</h5>
                                <form method="POST" action="<?php echo BASE_URL; ?>payment/process/<?php echo $order['id']; ?>">
                                    <div class="mb-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay" <?php echo ($order['payment_method'] == 'vnpay') ? 'checked' : ''; ?>>
                                            <label class="form-check-label d-flex align-items-center" for="vnpay">
                                                <img src="<?php echo BASE_URL; ?>assets/images/vnpay.png" alt="VNPay" height="30" class="me-2">
                                                Thanh toán qua VNPay
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo" <?php echo ($order['payment_method'] == 'momo') ? 'checked' : ''; ?>>
                                            <label class="form-check-label d-flex align-items-center" for="momo">
                                                <img src="<?php echo BASE_URL; ?>assets/images/momo.png" alt="MoMo" height="30" class="me-2">
                                                Thanh toán qua MoMo
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="bank" value="bank" <?php echo ($order['payment_method'] == 'bank') ? 'checked' : ''; ?>>
                                            <label class="form-check-label d-flex align-items-center" for="bank">
                                                <img src="<?php echo BASE_URL; ?>assets/images/bank.png" alt="Bank" height="30" class="me-2">
                                                Chuyển khoản ngân hàng
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <p class="mb-0"><i class="fas fa-shield-alt me-2"></i> Thanh toán an toàn và bảo mật 100%</p>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Thanh Toán Ngay</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Tổng thanh toán</h5>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Tổng tiền sản phẩm:</span>
                                            <span><?php echo number_format($order['amount']); ?> VNĐ</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Phí xử lý:</span>
                                            <span>0 VNĐ</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center fw-bold">
                                            <span>Tổng thanh toán:</span>
                                            <span class="text-primary"><?php echo number_format($order['amount']); ?> VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <p>Không tìm thấy thông tin đơn hàng. Vui lòng quay lại trang sản phẩm.</p>
                            <a href="<?php echo BASE_URL; ?>accounts" class="btn btn-primary mt-2">Xem tài khoản game</a>
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