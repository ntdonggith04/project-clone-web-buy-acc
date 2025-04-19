<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Lịch sử giao dịch</h1>

            <?php if (!empty($transactions)): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã GD</th>
                                        <th>Loại</th>
                                        <th>Số tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td>#<?= str_pad($transaction['id'], 6, '0', STR_PAD_LEFT) ?></td>
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
                                            <td>
                                                <?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?>
                                            </td>
                                            <td>
                                                <a href="/transactions/<?= $transaction['id'] ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    Chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($totalPages > 1): ?>
                            <nav class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php if ($currentPage > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" 
                                               href="/transactions?page=<?= $currentPage - 1 ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="/transactions?page=<?= $i ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($currentPage < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" 
                                               href="/transactions?page=<?= $currentPage + 1 ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Bạn chưa có giao dịch nào.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 