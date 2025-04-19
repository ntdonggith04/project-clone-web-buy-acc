<?php
$title = "404 - Không tìm thấy trang";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 text-danger">404</h1>
                <h2 class="mb-4">Không tìm thấy trang</h2>
                <p class="lead mb-4">Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
                <div class="mb-4">
                    <img src="<?php echo BASE_PATH; ?>/images/404.png" alt="404 Error" class="img-fluid" style="max-width: 300px;">
                </div>
                <div>
                    <a href="<?php echo BASE_PATH; ?>/" class="btn btn-primary">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                    <button onclick="history.back()" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 40px 0;
}
.error-page h1 {
    font-size: 120px;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}
.error-page h2 {
    color: #333;
    font-size: 32px;
}
.error-page .lead {
    color: #666;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 