<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
$title = $game['name'] ?? 'Chi tiết game';
?>

<section class="game-detail-section py-5" style="padding-top: 6.5rem !important;">
    <div class="container">
        <?php if (isset($game)): ?>
            <!-- Thông tin game -->
            <div class="row mb-5">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="game-image-container position-relative rounded-4 overflow-hidden shadow">
                        <?php if (!empty($game['image'])): ?>
                            <img src="<?= BASE_URL . 'uploads/games/' . $game['image'] ?>" class="img-fluid w-100" alt="<?= htmlspecialchars($game['name']) ?>" style="height: 300px; object-fit: cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-gradient-primary text-white" style="height: 300px;">
                                <i class="fas fa-gamepad fa-5x"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="game-info">
                        <h1 class="display-5 fw-bold mb-3"><?= htmlspecialchars($game['name']) ?></h1>
                        <div class="badge bg-success mb-3">Hoạt động</div>
                        <p class="lead mb-4"><?= nl2br(htmlspecialchars($game['description'] ?? '')) ?></p>
                        
                        <div class="row mb-4">
                            <div class="col-sm-4 col-md-3 mb-3">
                                <div class="card h-100 border-0 shadow-sm rounded-4">
                                    <div class="card-body text-center">
                                        <div class="stat-icon mb-2 text-primary">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                        <h3 class="fw-bold mb-0"><?= $stats['total_accounts'] ?? 0 ?></h3>
                                        <p class="text-muted mb-0">Tổng tài khoản</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 mb-3">
                                <div class="card h-100 border-0 shadow-sm rounded-4">
                                    <div class="card-body text-center">
                                        <div class="stat-icon mb-2 text-success">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                        <h3 class="fw-bold mb-0"><?= $stats['available_accounts'] ?? 0 ?></h3>
                                        <p class="text-muted mb-0">Còn trống</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 mb-3">
                                <div class="card h-100 border-0 shadow-sm rounded-4">
                                    <div class="card-body text-center">
                                        <div class="stat-icon mb-2 text-warning">
                                            <i class="fas fa-tag fa-2x"></i>
                                        </div>
                                        <h3 class="fw-bold mb-0"><?= number_format($stats['avg_price'] ?? 0) ?>đ</h3>
                                        <p class="text-muted mb-0">Giá trung bình</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc tài khoản -->
            <div class="card mb-4 border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-filter me-2"></i>Bộ lọc tài khoản</h5>
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="price_min" class="form-label">Giá thấp nhất</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" class="form-control" id="price_min" name="price_min" value="<?= $_GET['price_min'] ?? '' ?>" placeholder="Giá thấp nhất">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="price_max" class="form-label">Giá cao nhất</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" class="form-control" id="price_max" name="price_max" value="<?= $_GET['price_max'] ?? '' ?>" placeholder="Giá cao nhất">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Sắp xếp theo</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="newest" <?= isset($_GET['sort']) && $_GET['sort'] == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                                <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                                <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i>Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách tài khoản -->
            <div class="accounts-list mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0"><i class="fas fa-list-alt me-2"></i>Danh sách tài khoản</h2>
                    <div class="d-flex">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active" id="grid-view">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="list-view">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row g-4" id="accounts-grid">
                    <?php if (!empty($accounts)): ?>
                        <?php foreach ($accounts as $index => $account): ?>
                            <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>" data-aos-once="true">
                                <div class="card h-100 border-0 shadow-sm rounded-4 account-card">
                                    <?php if(isset($account['game_image'])): ?>
                                        <img src="<?= BASE_URL ?>uploads/accounts/<?= $account['game_image'] ?>" 
                                            class="card-img-top rounded-top-4" alt="<?= htmlspecialchars($account['title']) ?>" style="height: 160px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient-primary text-white rounded-top-4" style="height: 160px;">
                                            <i class="fas fa-user-circle fa-4x"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body p-3">
                                        <h5 class="card-title fw-bold"><?= htmlspecialchars($account['title']) ?></h5>
                                        <p class="card-text small text-muted"><?= htmlspecialchars(substr($account['description'] ?? '', 0, 80)) ?>...</p>
                                        
                                        <div class="d-flex mb-2">
                                            <?php if(isset($account['rank'])): ?>
                                            <span class="badge bg-secondary me-2"><?= htmlspecialchars($account['rank']) ?></span>
                                            <?php endif; ?>
                                            <?php if(isset($account['level'])): ?>
                                            <span class="badge bg-info">Level <?= htmlspecialchars($account['level']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-primary fw-bold"><?= number_format($account['price']) ?>đ</span>
                                            <a href="<?= BASE_URL . 'accounts/' . $account['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i> Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info p-4 text-center">
                                <i class="fas fa-info-circle fa-3x mb-3"></i>
                                <h4>Chưa có tài khoản nào được thêm vào</h4>
                                <p>Vui lòng quay lại sau hoặc liên hệ với quản trị viên.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-none" id="accounts-list">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="list-group list-group-flush">
                            <?php if (!empty($accounts)): ?>
                                <?php foreach ($accounts as $account): ?>
                                    <div class="list-group-item p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <?php if(isset($account['game_image'])): ?>
                                                    <img src="<?= BASE_URL ?>uploads/accounts/<?= $account['game_image'] ?>" 
                                                        class="img-fluid rounded" alt="<?= htmlspecialchars($account['title']) ?>" style="height: 80px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="d-flex align-items-center justify-content-center bg-gradient-primary text-white rounded" style="height: 80px; width: 80px;">
                                                        <i class="fas fa-user-circle fa-3x"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-4">
                                                <h5 class="mb-1"><?= htmlspecialchars($account['title']) ?></h5>
                                                <p class="text-muted mb-0 small"><?= htmlspecialchars(substr($account['description'] ?? '', 0, 50)) ?>...</p>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <?php if(isset($account['rank'])): ?>
                                                <span class="badge bg-secondary"><?= htmlspecialchars($account['rank']) ?></span>
                                                <?php endif; ?>
                                                <?php if(isset($account['level'])): ?>
                                                <span class="d-block mt-1">Level <?= htmlspecialchars($account['level']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="text-primary fw-bold"><?= number_format($account['price']) ?>đ</span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <a href="<?= BASE_URL . 'accounts/' . $account['id'] ?>" class="btn btn-primary">
                                                    <i class="fas fa-eye me-1"></i> Xem chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="list-group-item p-4 text-center">
                                    <i class="fas fa-info-circle fa-2x mb-2 text-info"></i>
                                    <p class="mb-0">Chưa có tài khoản nào được thêm vào</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Phân trang -->
            <?php if (!empty($accounts) && count($accounts) > 10): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-danger p-5 text-center rounded-4 shadow-sm">
                <i class="fas fa-exclamation-triangle fa-4x mb-3"></i>
                <h3>Không tìm thấy game này</h3>
                <p class="mb-4">Game bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
                <a href="<?= BASE_URL . 'games' ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách game
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    // Chuyển đổi giữa chế độ xem dạng lưới và dạng danh sách
    document.addEventListener('DOMContentLoaded', function() {
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const accountsGrid = document.getElementById('accounts-grid');
        const accountsList = document.getElementById('accounts-list');
        
        if (gridView && listView && accountsGrid && accountsList) {
            gridView.addEventListener('click', function() {
                gridView.classList.add('active');
                listView.classList.remove('active');
                accountsGrid.classList.remove('d-none');
                accountsList.classList.add('d-none');
            });
            
            listView.addEventListener('click', function() {
                listView.classList.add('active');
                gridView.classList.remove('active');
                accountsList.classList.remove('d-none');
                accountsGrid.classList.add('d-none');
            });
        }
    });
</script>

<style>
    /* Card styling */
    .card {
        transition: all 0.3s ease;
    }
    
    .account-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Badge styling */
    .badge {
        padding: 0.5rem 0.8rem;
        font-weight: 500;
    }
    
    /* Stats styling */
    .stat-icon {
        height: 50px;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border-radius: 50%;
    }
    
    /* Button styling */
    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
    }
    
    /* Form controls */
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.7rem 1rem;
        border-color: #e9ecef;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(94, 114, 228, 0.25);
        border-color: rgba(94, 114, 228, 0.5);
    }
    
    .input-group-text {
        border-radius: 8px 0 0 8px;
        border-color: #e9ecef;
    }
    
    /* Pagination styling */
    .pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
        padding: 8px 16px;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #5e72e4;
        border-color: #5e72e4;
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 