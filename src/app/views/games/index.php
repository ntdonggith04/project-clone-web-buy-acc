<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$title = "Danh mục game";
?>

<section class="games-section py-5" style="padding-top: 7rem !important;">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h1 class="section-title-games display-5 fw-bold mb-2">Danh mục game</h1>
            <hr class="gradient-line mx-auto" style="width: 150px; height: 3px; background: linear-gradient(to right, #007bff, #6610f2); opacity: 1; border: none; border-radius: 3px;">
            <p class="lead text-muted">Khám phá và chọn lựa từ danh mục game đa dạng của chúng tôi</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (!empty($games)): ?>
                <?php foreach ($games as $index => $game): ?>
                    <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>" data-aos-once="true">
                        <div class="card game-card h-100 border-0 rounded-4 shadow-sm overflow-hidden">
                            <div class="position-relative overflow-hidden game-img-container">
                                <?php if (!empty($game['image'])): ?>
                                    <img src="<?= BASE_URL . 'uploads/games/' . $game['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($game['name']) ?>" style="height: 200px; object-fit: cover; transition: all 0.5s ease;">
                                <?php else: ?>
                                    <img src="<?= BASE_URL ?>assets/images/game-placeholder.jpg" class="card-img-top" alt="<?= htmlspecialchars($game['name']) ?>" style="height: 200px; object-fit: cover; transition: all 0.5s ease;">
                                <?php endif; ?>
                                <div class="overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(13, 110, 253, 0.2); opacity: 0; transition: all 0.3s ease;">
                                    <a href="<?= BASE_URL ?>games/<?= htmlspecialchars($game['slug']) ?>" class="btn btn-light">Xem chi tiết</a>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title fw-bold mb-0"><?= htmlspecialchars($game['name']) ?></h5>
                                    <span class="badge bg-primary rounded-pill"><?= $game['account_count'] ?? 0 ?></span>
                                </div>
                                <p class="card-text text-muted small mb-3"><?= htmlspecialchars(substr($game['description'] ?? '', 0, 100)) ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <?php if (isset($game['min_price']) && $game['min_price'] > 0): ?>
                                        <span class="fw-bold text-primary">Từ <?= number_format($game['min_price']) ?>đ</span>
                                    <?php else: ?>
                                        <span class="text-muted">Liên hệ</span>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>games/<?= htmlspecialchars($game['slug']) ?>" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info p-5">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <h4>Chưa có game nào được thêm vào</h4>
                        <p>Vui lòng quay lại sau hoặc liên hệ với quản trị viên.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (!empty($games) && count($games) > 12): ?>
        <div class="mt-5">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Trước</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
    /* Ghi đè định nghĩa section-title */
    .section-title-games {
        position: relative !important;
        display: block !important;
        margin-bottom: 0.5rem !important;
    }
    
    .section-title-games:after {
        display: none !important;
        content: none !important;
    }
    
    /* Game card hover effects */
    .game-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }
    
    .game-card:hover .overlay {
        opacity: 1;
    }
    
    .game-card:hover .card-img-top {
        transform: scale(1.1);
    }
    
    /* Form controls */
    .form-control, .form-select {
        border-radius: 10px;
        padding: 0.7rem 1rem;
        border-color: #e9ecef;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(94, 114, 228, 0.25);
        border-color: rgba(94, 114, 228, 0.5);
    }
    
    .input-group-text {
        border-radius: 10px 0 0 10px;
        border-color: #e9ecef;
    }
    
    /* Badge styling */
    .badge {
        padding: 0.5rem 0.8rem;
        font-weight: 500;
    }
    
    /* Button styling */
    .btn-outline-primary {
        border-radius: 8px;
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 