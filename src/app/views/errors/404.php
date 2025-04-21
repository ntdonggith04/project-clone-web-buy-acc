<?php
$title = "404 - Không tìm thấy trang";
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-template">
                <h1>Oops!</h1>
                <h2>404 Không tìm thấy trang</h2>
                <div class="error-details mb-3">
                    Xin lỗi, trang bạn đang tìm kiếm không tồn tại.
                </div>
                <div class="error-actions">
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-template {
    padding: 40px 15px;
    text-align: center;
}
.error-template h1 {
    font-size: 72px;
    margin-bottom: 20px;
    color: #dc3545;
}
.error-template h2 {
    font-size: 36px;
    margin-bottom: 20px;
}
.error-actions {
    margin-top: 30px;
}
.error-actions .btn {
    margin: 0 10px;
}
</style> 