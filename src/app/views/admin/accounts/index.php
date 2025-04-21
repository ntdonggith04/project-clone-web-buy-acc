<?php
$title = "Quản lý tài khoản game | " . SITE_NAME;
ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Danh sách tài khoản game</h6>
                    <a href="<?= BASE_URL ?>admin/accounts/create" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Thêm tài khoản mới
                    </a>
                </div>
                <div class="card-body">
                    <!-- Bộ lọc -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="" class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label small">Danh mục game</label>
                                    <select name="game_id" class="form-control form-control-sm">
                                        <option value="0">Tất cả danh mục</option>
                                        <?php foreach ($games as $game): ?>
                                            <option value="<?= $game['id'] ?>" <?= isset($game_id) && $game_id == $game['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($game['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Giá từ</label>
                                    <input type="number" name="price_from" class="form-control form-control-sm" value="<?= $price_from ?? '' ?>" placeholder="VNĐ">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Giá đến</label>
                                    <input type="number" name="price_to" class="form-control form-control-sm" value="<?= $price_to ?? '' ?>" placeholder="VNĐ">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Từ ngày</label>
                                    <input type="date" name="date_from" class="form-control form-control-sm" value="<?= $date_from ?? '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">Đến ngày</label>
                                    <input type="date" name="date_to" class="form-control form-control-sm" value="<?= $date_to ?? '' ?>">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        <i class="fas fa-filter"></i> Lọc
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-responsive p-0">
                        <?php if (empty($accounts)) : ?>
                            <div class="text-center py-4">
                                <p class="text-muted">Không có tài khoản game nào</p>
                            </div>
                        <?php else : ?>
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tên game</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tài khoản</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Giá bán</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ngày tạo</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trạng thái</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($accounts as $account) : ?>
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 px-3"><?= $account['id'] ?></p>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <div class="avatar avatar-sm me-3 bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center">
                                                            <?= substr($account['game_name'], 0, 1) ?>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?= $account['game_name'] ?></h6>
                                                        <p class="text-xs text-secondary mb-0">ID: <?= $account['game_id'] ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">Tài khoản: <?= $account['username'] ?></p>
                                                <p class="text-xs text-secondary mb-0">Mật khẩu: <?= $account['password'] ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= number_format($account['price'], 0, ',', '.') ?>đ</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= date('d/m/Y', strtotime($account['created_at'])) ?></span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <?php if ($account['status'] == 'available') : ?>
                                                    <span class="badge badge-sm bg-gradient-success">Còn hàng</span>
                                                <?php elseif ($account['status'] == 'sold') : ?>
                                                    <span class="badge badge-sm bg-gradient-danger">Đã bán</span>
                                                <?php else : ?>
                                                    <span class="badge badge-sm bg-gradient-warning">Đang chờ</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-icon-only text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="<?= BASE_URL ?>admin/accounts/edit/<?= $account['id'] ?>">
                                                                <i class="fas fa-edit text-info me-2"></i> Chỉnh sửa
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= BASE_URL ?>admin/accounts/delete/<?= $account['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?')">
                                                                <i class="fas fa-trash text-danger me-2"></i> Xóa
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($accounts) && $total_pages > 1) : ?>
                <div class="d-flex justify-content-center mt-3">
                    <nav>
                        <ul class="pagination pagination-sm">
                            <?php if ($current_page > 1) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASE_URL ?>admin/accounts?page=<?= $current_page - 1 ?><?= isset($game_id) && $game_id > 0 ? '&game_id=' . $game_id : '' ?><?= !empty($date_from) ? '&date_from=' . $date_from : '' ?><?= !empty($date_to) ? '&date_to=' . $date_to : '' ?><?= $price_from > 0 ? '&price_from=' . $price_from : '' ?><?= $price_to > 0 ? '&price_to=' . $price_to : '' ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL ?>admin/accounts?page=<?= $i ?><?= isset($game_id) && $game_id > 0 ? '&game_id=' . $game_id : '' ?><?= !empty($date_from) ? '&date_from=' . $date_from : '' ?><?= !empty($date_to) ? '&date_to=' . $date_to : '' ?><?= $price_from > 0 ? '&price_from=' . $price_from : '' ?><?= $price_to > 0 ? '&price_to=' . $price_to : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASE_URL ?>admin/accounts?page=<?= $current_page + 1 ?><?= isset($game_id) && $game_id > 0 ? '&game_id=' . $game_id : '' ?><?= !empty($date_from) ? '&date_from=' . $date_from : '' ?><?= !empty($date_to) ? '&date_to=' . $date_to : '' ?><?= $price_from > 0 ? '&price_from=' . $price_from : '' ?><?= $price_to > 0 ? '&price_to=' . $price_to : '' ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin.php';
?> 