<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Tìm kiếm</h1>

            <form action="/search" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" 
                           name="q" 
                           class="form-control" 
                           placeholder="Nhập từ khóa tìm kiếm..." 
                           value="<?= htmlspecialchars($query) ?>">
                    <select name="type" class="form-select" style="max-width: 150px;">
                        <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>Tất cả</option>
                        <option value="games" <?= $type === 'games' ? 'selected' : '' ?>>Games</option>
                        <option value="accounts" <?= $type === 'accounts' ? 'selected' : '' ?>>Accounts</option>
                    </select>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>

            <?php if (!empty($query)): ?>
                <?php if ($type === 'all' || $type === 'games'): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Games</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($results['games'])): ?>
                                <div class="row">
                                    <?php foreach ($results['games'] as $game): ?>
                                        <div class="col-md-4 mb-4">
                                            <div class="card h-100">
                                                <img src="<?= $game['image'] ?>" 
                                                     class="card-img-top" 
                                                     alt="<?= htmlspecialchars($game['name']) ?>">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <?= htmlspecialchars($game['name']) ?>
                                                    </h5>
                                                    <p class="card-text">
                                                        <?= htmlspecialchars($game['description']) ?>
                                                    </p>
                                                    <a href="/games/<?= $game['id'] ?>" 
                                                       class="btn btn-primary">
                                                        Xem chi tiết
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Không tìm thấy game nào.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($type === 'all' || $type === 'accounts'): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Accounts</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($results['accounts'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Game</th>
                                                <th>Username</th>
                                                <th>Rank</th>
                                                <th>Giá</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($results['accounts'] as $account): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($account['game_name']) ?></td>
                                                    <td><?= htmlspecialchars($account['username']) ?></td>
                                                    <td><?= htmlspecialchars($account['rank']) ?></td>
                                                    <td><?= number_format($account['price']) ?> VNĐ</td>
                                                    <td>
                                                        <a href="/accounts/<?= $account['id'] ?>" 
                                                           class="btn btn-sm btn-primary">
                                                            Chi tiết
                                                        </a>
                                                        <?php if ($account['status'] === 'available'): ?>
                                                            <button onclick="addToCart(<?= $account['id'] ?>)" 
                                                                    class="btn btn-sm btn-success">
                                                                <i class="fas fa-cart-plus"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Không tìm thấy account nào.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function addToCart(accountId) {
    fetch(`/cart/add/${accountId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cập nhật số lượng giỏ hàng
            document.getElementById('cart-count').textContent = data.cartCount;
            
            // Hiển thị thông báo
            alert(data.message);
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