<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$title = "Tìm kiếm game: " . htmlspecialchars($keyword);
?>

<div class="container py-4">
    <h1 class="mb-4">Tìm kiếm game: <?= htmlspecialchars($keyword) ?></h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($games)): ?>
            <?php foreach ($games as $game): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($game['image'])): ?>
                            <img src="<?= BASE_URL . 'uploads/' . $game['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($game['name']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($game['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($game['description'] ?? '', 0, 100)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary"><?= $game['account_count'] ?? 0 ?> accounts</span>
                                <a href="<?= BASE_URL . 'games/' . htmlspecialchars($game['slug']) ?>" class="btn btn-outline-primary">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    Không tìm thấy game nào phù hợp với từ khóa "<?= htmlspecialchars($keyword) ?>".
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 