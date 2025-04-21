<?php
$title = "Đăng ký | Game Account Store";
ob_start();
?>

<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5" style="padding-top: 80px !important;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0"><i class="fas fa-user-plus me-2"></i>Đăng ký tài khoản</h3>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo BASE_URL; ?>register" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label fw-bold"><i class="fas fa-user me-1"></i>Tên đăng nhập</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user-circle"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Tên đăng nhập" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold"><i class="fas fa-envelope me-1"></i>Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-at"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label fw-bold"><i class="fas fa-id-card me-1"></i>Họ tên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nguyễn Văn A" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold"><i class="fas fa-phone me-1"></i>Số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone-alt"></i></span>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="0123456789" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold"><i class="fas fa-lock me-1"></i>Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label fw-bold"><i class="fas fa-check-circle me-1"></i>Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">Tôi đồng ý với <a href="#" class="text-primary">điều khoản</a> và <a href="#" class="text-primary">chính sách bảo mật</a></label>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p>Đã có tài khoản? <a href="<?php echo BASE_URL; ?>login" class="text-primary fw-bold">Đăng nhập ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        background: rgba(21, 32, 43, 0.9);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        border: none;
    }
    
    .card-header {
        background: #3a0ca3;
    }
    
    .card-footer {
        background: rgba(30, 40, 60, 0.9);
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .form-control, .input-group-text {
        background-color: rgba(20, 20, 50, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .form-control:focus {
        background-color: rgba(30, 30, 70, 0.5);
        box-shadow: none;
        border-color: #4cc9f0;
        color: white;
    }
    
    .form-label {
        color: #4cc9f0;
        text-shadow: 0 0 5px rgba(76, 201, 240, 0.3);
    }
    
    .form-check-label, .small, .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    .input-group-text {
        color: #4cc9f0;
        border-right: none;
    }
    
    .btn-primary {
        background: #3a0ca3;
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    
    .btn-primary:hover {
        background: #4361ee;
        transform: translateY(-2px);
    }
    
    .btn-outline-primary, .btn-outline-danger, .btn-outline-dark {
        color: white;
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(30, 30, 70, 0.3);
    }
    
    .btn-outline-primary:hover {
        background: rgba(67, 97, 238, 0.2);
        color: #4cc9f0;
        border-color: #4cc9f0;
    }
    
    .btn-outline-danger:hover {
        background: rgba(240, 76, 76, 0.2);
        color: #f07c7c;
        border-color: #f07c7c;
    }
    
    .btn-outline-dark:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-color: white;
    }
    
    a {
        color: #4cc9f0;
        text-decoration: none;
    }
    
    a:hover {
        color: #f72585;
        text-shadow: 0 0 5px rgba(247, 37, 133, 0.5);
    }
    
    .container {
        position: relative;
    }
    
    /* Gaming animated border - hidden */
    .card:before {
        display: none;
    }
    
    .form-check-input {
        background-color: rgba(20, 20, 50, 0.5);
        border-color: rgba(255, 255, 255, 0.2);
    }
    
    .form-check-input:checked {
        background-color: #4cc9f0;
        border-color: #4cc9f0;
    }
</style>

<!-- Hiệu ứng đơn giản -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ giữ lại hiệu ứng click cho nút
    const button = document.querySelector('.btn-primary');
    button.addEventListener('mousedown', function() {
        this.style.transform = 'scale(0.95)';
    });
    button.addEventListener('mouseup', function() {
        this.style.transform = 'scale(1)';
    });
    button.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?> 