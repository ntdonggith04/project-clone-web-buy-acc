<?php
$title = isset($account['title']) ? "Chi tiết tài khoản " . $account['title'] : "Chi tiết tài khoản";
ob_start();
?>

<div class="container py-5 mt-5" style="padding-top: 4.5625rem !important;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold">Thông tin chi tiết tài khoản game</h1>
                <hr class="gradient-line mx-auto" style="width: 200px; height: 3px; background: linear-gradient(to right, #007bff, #6610f2); opacity: 1; border: none; border-radius: 3px;">
            </div>
            
            <div class="card mb-4">
                <?php if(!empty($screenshots)): ?>
                <!-- Carousel hiển thị tất cả ảnh -->
                <div id="accountCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php foreach($screenshots as $index => $screenshot): ?>
                        <button type="button" data-bs-target="#accountCarousel" data-bs-slide-to="<?= $index ?>" 
                                class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" 
                                aria-label="Slide <?= $index + 1 ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-inner">
                        <?php foreach($screenshots as $index => $screenshot): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= BASE_URL ?>uploads/accounts/<?= $screenshot['filename'] ?>" 
                                class="d-block w-100" alt="Ảnh tài khoản <?= $index + 1 ?>" style="height: 300px; object-fit: cover;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#accountCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Trước</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#accountCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Sau</span>
                    </button>
                </div>
                <?php elseif(isset($account['game_image'])): ?>
                <img src="<?= BASE_URL ?>uploads/accounts/<?= $account['game_image']; ?>" 
                    class="card-img-top" alt="<?php echo $account['title'] ?? 'Tài khoản game'; ?>">
                <?php else: ?>
                <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient-primary text-white" style="height: 250px; font-size: 96px; font-weight: bold;">
                    <?php echo substr($account['game_name'] ?? 'G', 0, 1); ?>
                </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <h2 class="card-title text-center"><?php echo $account['game_name'] ?? 'Tài khoản game'; ?></h2>
                    <div class="mb-3 text-center">
                        <h3 class="text-primary"><?php echo number_format($account['price'] ?? 0); ?> VNĐ</h3>
                    </div>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="mb-4">
                            <div class="d-grid">
                                <a href="<?php echo BASE_URL; ?>buy-now/<?php echo $account['id']; ?>" class="btn btn-primary btn-lg fw-bold">
                                    <i class="fas fa-bolt me-2"></i> MUA NGAY
                                </a>
                            </div>
                            <p class="text-center mt-2 text-muted small">Giao dịch an toàn, nhận thông tin ngay</p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center mb-4">
                            <p class="mb-0">Vui lòng <a href="<?php echo BASE_URL; ?>login" class="fw-bold">đăng nhập</a> để mua tài khoản</p>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <h5>Thông tin tài khoản</h5>
                        <table class="table">
                            <?php if(isset($account['level'])): ?>
                            <tr>
                                <th>Level:</th>
                                <td><?php echo $account['level']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($account['rank'])): ?>
                            <tr>
                                <th>Rank:</th>
                                <td><?php echo $account['rank']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($account['server'])): ?>
                            <tr>
                                <th>Server:</th>
                                <td><?php echo $account['server']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($account['game_name'])): ?>
                            <tr>
                                <th>Game:</th>
                                <td><?php echo $account['game_name']; ?></td>
                            </tr>
                            <?php elseif(isset($account['game_id'])): ?>
                            <tr>
                                <th>Game:</th>
                                <td><?php echo $account['game_id']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Ngày đăng:</th>
                                <td><?php echo isset($account['created_at']) ? date('d/m/Y', strtotime($account['created_at'])) : ''; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="mb-3">
                        <h5>Mô tả</h5>
                        <p class="card-text"><?php echo nl2br($account['description'] ?? ''); ?></p>
                    </div>
                </div>
            </div>

            <!-- Thêm phần hiển thị gallery nhỏ bên dưới thông tin tài khoản nếu có nhiều ảnh -->
            <?php if(!empty($screenshots) && count($screenshots) > 1): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Hình ảnh tài khoản</h5>
                    <div class="row">
                        <?php foreach($screenshots as $index => $screenshot): ?>
                        <div class="col-3 mb-3">
                            <a href="#" data-bs-target="#accountCarousel" data-bs-slide-to="<?= $index ?>">
                                <img src="<?= BASE_URL ?>uploads/accounts/<?= $screenshot['filename'] ?>" 
                                    class="img-thumbnail" alt="Ảnh tài khoản <?= $index + 1 ?>" style="height: 80px; object-fit: cover;">
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Thêm phần quay lại trang chi tiết game -->
            <div class="text-center mt-4">
                <?php if(isset($account['game_id'])): ?>
                <a href="<?= BASE_URL ?>games/<?= $game_slug ?? '' ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh mục game
                </a>
                <?php else: ?>
                <a href="<?= BASE_URL ?>games" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh mục game
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/header.php';
echo $content;
require_once __DIR__ . '/../layouts/footer.php';
?> 