<?php
$title = "Đăng nhập | Game Account Store";
ob_start();
?>

<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container py-5" style="padding-top: 80px !important;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập</h3>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo BASE_URL; ?>login" method="POST">
                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold"><i class="fas fa-user me-2"></i>Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-circle"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold"><i class="fas fa-lock me-2"></i>Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-2">Chưa có tài khoản? <a href="<?php echo BASE_URL; ?>register" class="text-primary fw-bold">Đăng ký ngay</a></p>
                        <p class="small text-muted"><a href="#" class="text-muted">Quên mật khẩu?</a></p>
                    </div>
                    
                    <div class="separator my-3">
                        <span class="separator-text">hoặc</span>
                    </div>
                    
                    <div class="d-grid">
                        <a href="#" class="btn btn-outline-danger">
                            <i class="fab fa-google me-2"></i>Đăng nhập bằng Google
                        </a>
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
    
    /* Separator style */
    .separator {
        display: flex;
        align-items: center;
        text-align: center;
        color: rgba(255, 255, 255, 0.5);
    }

    .separator::before,
    .separator::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .separator::before {
        margin-right: 1em;
    }

    .separator::after {
        margin-left: 1em;
    }
    
    .separator-text {
        padding: 0 0.5em;
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