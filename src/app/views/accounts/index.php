<?php
$title = "Danh sách tài khoản game";
ob_start();
?>

<section class="accounts-section py-5" style="padding-top: 7rem !important;">
    <div class="container">
        <!-- Tiêu đề danh sách -->
        <div class="section-header text-center mb-4">
            <h1 class="section-title-accounts display-5 fw-bold mb-2">Danh sách tài khoản game</h1>
            <hr class="gradient-line mx-auto" style="width: 150px; height: 3px; background: linear-gradient(to right, #007bff, #6610f2); opacity: 1; border: none; border-radius: 3px;">
        </div>

        <!-- Bộ lọc -->
        <div class="filter-bg p-3 mb-4" data-aos="fade-up" data-aos-delay="100" data-aos-once="true" style="background-color: #f8f9fa; border-radius: 8px;">
            <form action="<?php echo BASE_URL; ?>accounts" method="GET" class="m-0 px-2">
                <div class="row g-1 align-items-center">
                    <div class="col-md-2 col-sm-12 mb-1 mb-md-0">
                        <span class="fw-bold small d-block">Bộ lọc:</span>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-1 mb-md-0">
                        <div class="input-group input-group-sm w-100">
                            <span class="input-group-text" style="font-size: 11px; padding: 2px 5px;">Loại game</span>
                            <select class="form-select form-select-sm py-0 no-hover" id="game" name="game" style="height: 24px; font-size: 12px;">
                                <option value="">Tất cả</option>
                                <?php foreach($games as $game): ?>
                                    <option value="<?php echo $game['id']; ?>" <?php echo isset($_GET['game']) && $_GET['game'] == $game['id'] ? 'selected' : ''; ?>>
                                        <?php echo $game['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-1 mb-md-0">
                        <div class="input-group input-group-sm w-100">
                            <span class="input-group-text" style="font-size: 11px; padding: 2px 5px;">Giá từ</span>
                            <select class="form-select form-select-sm py-0 no-hover" id="price_min" name="price_min" style="height: 24px; font-size: 12px;">
                                <option value="">Tất cả</option>
                                <option value="50000" <?php echo isset($_GET['price_min']) && $_GET['price_min'] == '50000' ? 'selected' : ''; ?>>50,000 VNĐ</option>
                                <option value="100000" <?php echo isset($_GET['price_min']) && $_GET['price_min'] == '100000' ? 'selected' : ''; ?>>100,000 VNĐ</option>
                                <option value="200000" <?php echo isset($_GET['price_min']) && $_GET['price_min'] == '200000' ? 'selected' : ''; ?>>200,000 VNĐ</option>
                                <option value="500000" <?php echo isset($_GET['price_min']) && $_GET['price_min'] == '500000' ? 'selected' : ''; ?>>500,000 VNĐ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-1 mb-md-0">
                        <div class="input-group input-group-sm w-100">
                            <span class="input-group-text" style="font-size: 11px; padding: 2px 5px;">Đến</span>
                            <select class="form-select form-select-sm py-0 no-hover" id="price_max" name="price_max" style="height: 24px; font-size: 12px;">
                                <option value="">Tất cả</option>
                                <option value="500000" <?php echo isset($_GET['price_max']) && $_GET['price_max'] == '500000' ? 'selected' : ''; ?>>500,000 VNĐ</option>
                                <option value="1000000" <?php echo isset($_GET['price_max']) && $_GET['price_max'] == '1000000' ? 'selected' : ''; ?>>1,000,000 VNĐ</option>
                                <option value="2000000" <?php echo isset($_GET['price_max']) && $_GET['price_max'] == '2000000' ? 'selected' : ''; ?>>2,000,000 VNĐ</option>
                                <option value="5000000" <?php echo isset($_GET['price_max']) && $_GET['price_max'] == '5000000' ? 'selected' : ''; ?>>5,000,000 VNĐ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <button type="submit" class="btn btn-secondary btn-sm w-100 py-0 no-hover" style="height: 24px; font-size: 12px;">
                            Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <?php if(empty($accounts)): ?>
                <div class="col-12">
                    <div class="alert alert-info">Không tìm thấy tài khoản nào phù hợp.</div>
                </div>
            <?php else: ?>
                <?php foreach($accounts as $index => $account): ?>
                    <div class="col-md-4 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>" data-aos-once="true">
                        <div class="card h-100">
                            <?php if (!empty($account['game_image'])): ?>
                                <img src="<?= BASE_URL ?>uploads/accounts/<?= $account['game_image']; ?>" 
                                     class="card-img-top" alt="<?php echo $account['game_name'] ?? 'Game Account'; ?>">
                            <?php else: ?>
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient-primary text-white" style="height: 180px; font-size: 64px; font-weight: bold;">
                                    <?php echo substr($account['game_name'] ?? 'G', 0, 1); ?>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $account['game_name'] ?? $account['title'] ?? 'Tài khoản game'; ?></h5>
                                <p class="card-text">
                                    <strong>Tài khoản:</strong> <?php echo $account['username'] ?? 'N/A'; ?><br>
                                    <strong>Chi tiết:</strong> <?php echo substr($account['description'] ?? 'N/A', 0, 50) . (strlen($account['description'] ?? '') > 50 ? '...' : ''); ?><br>
                                    <strong>Giá:</strong> <?php echo number_format($account['price'] ?? 0); ?> VNĐ
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo BASE_URL; ?>accounts/<?php echo $account['id']; ?>" 
                                    class="btn btn-primary w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4" data-aos="fade-up" data-aos-once="true">
                <ul class="pagination justify-content-center">
                    <?php if($current_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo BASE_URL; ?>accounts?page=<?php echo $current_page-1; ?><?php echo $query_string; ?>">
                                <i class="fas fa-chevron-left"></i> Trước
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo BASE_URL; ?>accounts?page=<?php echo $i; ?><?php echo $query_string; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if($current_page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo BASE_URL; ?>accounts?page=<?php echo $current_page+1; ?><?php echo $query_string; ?>">
                                Sau <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<style>
    /* Vô hiệu hóa hiệu ứng hover cho bộ lọc */
    .no-hover {
        transition: none !important;
        transform: none !important;
        box-shadow: none !important;
    }
    
    .no-hover:hover, 
    .no-hover:focus, 
    .no-hover:active {
        background-color: inherit !important;
        border-color: #ced4da !important;
        transform: none !important;
        box-shadow: none !important;
        outline: none !important;
    }
    
    .btn.no-hover:hover,
    .btn.no-hover:focus,
    .btn.no-hover:active {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
    }
    
    .card.border:hover {
        transform: none !important;
        box-shadow: none !important;
    }

    /* Tăng khoảng cách giữa header và nội dung trang accounts */
    .accounts-page {
        margin-top: 40px;
    }

    .filter-bg {
        border: 1px solid rgba(0,0,0,0.1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    /* Hiệu ứng hover cho card tài khoản */
    .card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }
    
    .card-img-top {
        transition: transform 0.5s ease;
        height: 180px;
        object-fit: cover;
    }
    
    .card:hover .card-img-top {
        transform: scale(1.03);
    }
    
    .card-footer {
        background-color: transparent;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Ghi đè định nghĩa section-title */
    .section-title-accounts {
        position: relative !important;
        display: block !important;
        margin-bottom: 0.5rem !important;
    }
    
    .section-title-accounts:after {
        display: none !important;
        content: none !important;
    }
</style>

<?php
$content = ob_get_clean();
?>

<?php
require_once __DIR__ . '/../layouts/header.php';
echo $content;
require_once __DIR__ . '/../layouts/footer.php';
?> 