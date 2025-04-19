<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                    </div>
                    <h2 class="card-title mb-4">Thanh toán thành công!</h2>
                    
                    <div class="alert alert-success">
                        <p class="mb-0">
                            Cảm ơn bạn đã mua hàng. Mã giao dịch của bạn là: 
                            <strong>#<?= str_pad($transaction['id'], 6, '0', STR_PAD_LEFT) ?></strong>
                        </p>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Thông tin giao dịch</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Mã giao dịch:</th>
                                    <td>#<?= str_pad($transaction['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                </tr>
                                <tr>
                                    <th>Thời gian:</th>
                                    <td><?= date('d/m/Y H:i:s', strtotime($transaction['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Tổng tiền:</th>
                                    <td><?= number_format($transaction['amount']) ?> VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td>
                                        <span class="badge bg-success">
                                            <?= ucfirst($transaction['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Danh sách account đã mua</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Game</th>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>Giá</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($accounts as $account): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($account['game_name']) ?></td>
                                                <td><?= htmlspecialchars($account['username']) ?></td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="password" 
                                                               class="form-control" 
                                                               value="<?= htmlspecialchars($account['password']) ?>" 
                                                               readonly>
                                                        <button class="btn btn-outline-secondary toggle-password" 
                                                                type="button">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-secondary copy-password"
                                                                type="button"
                                                                data-password="<?= htmlspecialchars($account['password']) ?>">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td><?= number_format($account['price']) ?> VNĐ</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                Vui lòng lưu lại thông tin tài khoản ngay lập tức. 
                                Bạn sẽ không thể xem lại mật khẩu sau khi rời khỏi trang này.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="/profile/purchases" class="btn btn-primary">
                            <i class="fas fa-history"></i> Xem lịch sử mua hàng
                        </a>
                        <a href="/accounts" class="btn btn-success">
                            <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input');
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});

document.querySelectorAll('.copy-password').forEach(button => {
    button.addEventListener('click', function() {
        const password = this.dataset.password;
        navigator.clipboard.writeText(password).then(() => {
            alert('Đã sao chép mật khẩu vào clipboard');
        });
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 