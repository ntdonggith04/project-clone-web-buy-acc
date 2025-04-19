<?php
$title = "Nạp tiền qua chuyển khoản ngân hàng";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Thông tin chuyển khoản</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Thông tin giao dịch:</h5>
                            <p><strong>Mã giao dịch:</strong> #<?php echo $transaction->id; ?></p>
                            <p><strong>Số tiền:</strong> <?php echo number_format($transaction->amount); ?>đ</p>
                            <p><strong>Khuyến mãi:</strong> <?php echo number_format($transaction->bonus); ?>đ</p>
                            <p><strong>Tổng nhận:</strong> <?php echo number_format($transaction->total_amount); ?>đ</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Thông tin ngân hàng:</h5>
                            <p><strong>Ngân hàng:</strong> <?php echo $bankInfo['bank_name']; ?></p>
                            <p><strong>Số tài khoản:</strong> <?php echo $bankInfo['account_number']; ?></p>
                            <p><strong>Tên tài khoản:</strong> <?php echo $bankInfo['account_name']; ?></p>
                            <p><strong>Chi nhánh:</strong> <?php echo $bankInfo['branch']; ?></p>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h5 class="alert-heading">Nội dung chuyển khoản:</h5>
                        <p class="mb-0 font-weight-bold"><?php echo $bankInfo['transfer_content']; ?></p>
                    </div>

                    <div class="alert alert-warning">
                        <h5 class="alert-heading">Lưu ý:</h5>
                        <ul class="mb-0">
                            <li>Vui lòng chuyển khoản đúng số tiền và nội dung như trên</li>
                            <li>Giao dịch sẽ được xử lý trong vòng 5-15 phút sau khi chuyển khoản thành công</li>
                            <li>Nếu sau 30 phút chưa nhận được tiền, vui lòng liên hệ hỗ trợ</li>
                        </ul>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?php echo BASE_PATH; ?>/recharge" class="btn btn-secondary">Quay lại</a>
                        <a href="#" class="btn btn-primary" onclick="copyTransferContent()">
                            <i class="fas fa-copy"></i> Copy nội dung CK
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyTransferContent() {
    const content = '<?php echo $bankInfo['transfer_content']; ?>';
    navigator.clipboard.writeText(content).then(() => {
        alert('Đã copy nội dung chuyển khoản!');
    }).catch(err => {
        console.error('Không thể copy nội dung: ', err);
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 