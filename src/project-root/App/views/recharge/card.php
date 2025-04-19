<?php
$title = "Nạp tiền qua thẻ cào";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Nạp tiền bằng thẻ cào</h4>
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

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_PATH; ?>/recharge/card/submit" method="POST" id="cardForm">
                        <div class="form-group mb-3">
                            <label for="telco"><strong>Chọn nhà mạng:</strong></label>
                            <select name="telco" id="telco" class="form-control" required>
                                <option value="">-- Chọn nhà mạng --</option>
                                <option value="VIETTEL">Viettel</option>
                                <option value="MOBIFONE">Mobifone</option>
                                <option value="VINAPHONE">Vinaphone</option>
                                <option value="VIETNAMOBILE">Vietnamobile</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount"><strong>Mệnh giá:</strong></label>
                            <select name="amount" id="amount" class="form-control" required>
                                <option value="">-- Chọn mệnh giá --</option>
                                <option value="10000">10,000đ</option>
                                <option value="20000">20,000đ</option>
                                <option value="50000">50,000đ</option>
                                <option value="100000">100,000đ</option>
                                <option value="200000">200,000đ</option>
                                <option value="500000">500,000đ</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="serial"><strong>Số Serial:</strong></label>
                            <input type="text" class="form-control" id="serial" name="serial" required 
                                placeholder="Nhập số serial thẻ cào">
                        </div>

                        <div class="form-group mb-3">
                            <label for="pin"><strong>Mã thẻ:</strong></label>
                            <input type="text" class="form-control" id="pin" name="pin" required 
                                placeholder="Nhập mã thẻ cào">
                        </div>

                        <div class="alert alert-warning">
                            <h5 class="alert-heading">Lưu ý:</h5>
                            <ul class="mb-0">
                                <li>Vui lòng kiểm tra kỹ thông tin thẻ trước khi nạp</li>
                                <li>Mỗi thẻ chỉ sử dụng được một lần</li>
                                <li>Thẻ cào sai mệnh giá sẽ bị trừ 50% giá trị thẻ</li>
                                <li>Nếu gặp lỗi, vui lòng liên hệ hỗ trợ</li>
                            </ul>
                        </div>

                        <div class="text-center mt-4">
                            <a href="<?php echo BASE_PATH; ?>/recharge" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-paper-plane"></i> Nạp thẻ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('cardForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
});

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert-success, .alert-danger');
    alerts.forEach(function(alert) {
        alert.style.display = 'none';
    });
}, 5000);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 