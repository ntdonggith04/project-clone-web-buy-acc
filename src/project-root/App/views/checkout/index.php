<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Game</th>
                                    <th>Username</th>
                                    <th class="text-end">Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['game_name']) ?></td>
                                        <td><?= htmlspecialchars($item['username']) ?></td>
                                        <td class="text-end"><?= number_format($item['price']) ?> VNĐ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Tổng cộng:</td>
                                    <td class="text-end fw-bold"><?= number_format($total) ?> VNĐ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Số dư hiện tại:</label>
                        <div class="h4"><?= number_format($user['balance']) ?> VNĐ</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tổng thanh toán:</label>
                        <div class="h4 text-primary"><?= number_format($total) ?> VNĐ</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số dư còn lại:</label>
                        <div class="h4 <?= ($user['balance'] >= $total) ? 'text-success' : 'text-danger' ?>">
                            <?= number_format($user['balance'] - $total) ?> VNĐ
                        </div>
                    </div>

                    <?php if ($user['balance'] >= $total): ?>
                        <form action="/checkout/process" method="POST">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="agree" required>
                                <label class="form-check-label" for="agree">
                                    Tôi đồng ý với các điều khoản mua bán
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-check"></i> Xác nhận thanh toán
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> 
                            Số dư không đủ để thanh toán
                        </div>
                        <a href="/profile/deposit" class="btn btn-success w-100">
                            <i class="fas fa-wallet"></i> Nạp thêm tiền
                        </a>
                    <?php endif; ?>

                    <a href="/cart" class="btn btn-link w-100 mt-2">
                        <i class="fas fa-arrow-left"></i> Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 