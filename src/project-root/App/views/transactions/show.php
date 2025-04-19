<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Chi tiết giao dịch</h1>
                <a href="/transactions" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin giao dịch</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Mã giao dịch:</th>
                            <td>#<?= str_pad($transaction['id'], 6, '0', STR_PAD_LEFT) ?></td>
                        </tr>
                        <tr>
                            <th>Loại giao dịch:</th>
                            <td>
                                <?php if ($transaction['type'] === 'purchase'): ?>
                                    <span class="badge bg-success">Mua account</span>
                                <?php elseif ($transaction['type'] === 'deposit'): ?>
                                    <span class="badge bg-primary">Nạp tiền</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <?= ucfirst($transaction['type']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Số tiền:</th>
                            <td>
                                <?php if ($transaction['type'] === 'purchase'): ?>
                                    <span class="text-danger">
                                        -<?= number_format($transaction['amount']) ?> VNĐ
                                    </span>
                                <?php else: ?>
                                    <span class="text-success">
                                        +<?= number_format($transaction['amount']) ?> VNĐ
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <?php if ($transaction['status'] === 'completed'): ?>
                                    <span class="badge bg-success">Hoàn thành</span>
                                <?php elseif ($transaction['status'] === 'pending'): ?>
                                    <span class="badge bg-warning">Đang xử lý</span>
                                <?php elseif ($transaction['status'] === 'failed'): ?>
                                    <span class="badge bg-danger">Thất bại</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <?= ucfirst($transaction['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Thời gian:</th>
                            <td><?= date('d/m/Y H:i:s', strtotime($transaction['created_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if ($transaction['type'] === 'purchase' && !empty($accounts)): ?>
                <div class="card">
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
                                        <th>Rank</th>
                                        <th>Giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($accounts as $account): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($account['game_name']) ?></td>
                                            <td><?= htmlspecialchars($account['username']) ?></td>
                                            <td><?= htmlspecialchars($account['rank']) ?></td>
                                            <td><?= number_format($account['price']) ?> VNĐ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 