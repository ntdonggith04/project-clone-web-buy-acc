<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Giỏ hàng</h1>

            <?php if (!empty($items)): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Game</th>
                                        <th>Username</th>
                                        <th>Giá</th>
                                        <th>Thời gian thêm</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['game_name']) ?></td>
                                            <td><?= htmlspecialchars($item['username']) ?></td>
                                            <td><?= number_format($item['price']) ?> VNĐ</td>
                                            <td><?= date('d/m/Y H:i', strtotime($item['added_at'])) ?></td>
                                            <td>
                                                <button onclick="removeFromCart(<?= $item['id'] ?>)" 
                                                        class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Tổng cộng:</td>
                                        <td class="fw-bold"><?= number_format($total) ?> VNĐ</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button onclick="clearCart()" class="btn btn-warning">
                                <i class="fas fa-trash"></i> Xóa giỏ hàng
                            </button>
                            <a href="/checkout" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Thanh toán
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Giỏ hàng trống
                </div>
                <a href="/accounts" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function removeFromCart(accountId) {
    if (!confirm('Bạn có chắc muốn xóa account này khỏi giỏ hàng?')) {
        return;
    }

    fetch(`/cart/remove/${accountId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload trang
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

function clearCart() {
    if (!confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
        return;
    }

    fetch('/cart/clear', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload trang
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 