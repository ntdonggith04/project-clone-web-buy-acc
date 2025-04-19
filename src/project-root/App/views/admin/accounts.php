<?php
ob_start();
?>
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mt-4">Quản lý tài khoản game</h2>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="<?php echo BASE_PATH; ?>/admin">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý tài khoản game</li>
            </ol>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                <i class="fas fa-plus"></i> Thêm tài khoản game
            </button>
        </div>
    </div>

    <!-- Hiển thị thông báo -->
    <?php if (isset($_SESSION["success"])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION["success"];
            unset($_SESSION["success"]);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION["error"])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION["error"];
            unset($_SESSION["error"]);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3" method="GET">
                <div class="col-md-3">
                    <label class="form-label">Game</label>
                    <select class="form-select" name="game_id">
                        <option value="">Tất cả</option>
                        <?php 
                        $seenGameIds = [];
                        foreach ($games as $game):
                            if (!isset($seenGameIds[$game['id']])):
                                $seenGameIds[$game['id']] = true;
                        ?>
                            <option value="<?php echo $game['id']; ?>" <?php echo isset($_GET['game_id']) && $_GET['game_id'] == $game['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($game['name']); ?>
                            </option>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                        <option value="">Tất cả</option>
                        <option value="available" <?php echo isset($_GET['status']) && $_GET['status'] == 'available' ? 'selected' : ''; ?>>Còn hàng</option>
                        <option value="sold" <?php echo isset($_GET['status']) && $_GET['status'] == 'sold' ? 'selected' : ''; ?>>Đã bán</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tìm kiếm</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Tên tài khoản..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-list me-1"></i>
            Danh sách tài khoản game
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px">ID</th>
                            <th>Game</th>
                            <th>Tài khoản</th>
                            <th>Mô tả</th>
                            <th>Thông tin chi tiết</th>
                            <th class="text-end" style="width: 120px">Giá</th>
                            <th class="text-center" style="width: 120px">Trạng thái</th>
                            <th class="text-center" style="width: 100px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($accounts)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">Không có tài khoản game nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($accounts as $account): ?>
                                <tr>
                                    <td class="text-center"><?php echo htmlspecialchars($account['id']); ?></td>
                                    <td><?php echo htmlspecialchars($account['game_name']); ?></td>
                                    <td>
                                        <strong>Tài khoản:</strong> <?php echo htmlspecialchars($account['username']); ?><br>
                                        <strong>Mật khẩu:</strong> <?php echo htmlspecialchars($account['password']); ?>
                                    </td>
                                    <td>
                                        <strong>Tiêu đề:</strong> <?php echo htmlspecialchars($account['title']); ?><br>
                                        <?php echo nl2br(htmlspecialchars($account['basic_description'])); ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($account['detailed_description'])): ?>
                                            <?php echo nl2br(htmlspecialchars($account['detailed_description'])); ?>
                                        <?php endif; ?>
                                        <?php if (!empty($account['description'])): ?>
                                            <br><?php echo nl2br(htmlspecialchars($account['description'])); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end"><?php echo number_format($account['price'], 0, ',', '.'); ?>đ</td>
                                    <td class="text-center">
                                        <?php
                                        $statusClasses = [
                                            'available' => 'bg-success',
                                            'sold' => 'bg-secondary',
                                            'pending' => 'bg-warning'
                                        ];
                                        $statusNames = [
                                            'available' => 'Còn hàng',
                                            'sold' => 'Đã bán',
                                            'pending' => 'Đang giao dịch'
                                        ];
                                        $class = $statusClasses[$account['status']] ?? 'bg-secondary';
                                        $status = $statusNames[$account['status']] ?? $account['status'];
                                        ?>
                                        <span class="badge <?php echo $class; ?>">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-sm btn-primary edit-account" 
                                                data-id="<?php echo $account['id']; ?>"
                                                data-game-id="<?php echo $account['game_id']; ?>"
                                                data-title="<?php echo htmlspecialchars($account['title']); ?>"
                                                data-username="<?php echo htmlspecialchars($account['username']); ?>"
                                                data-password="<?php echo htmlspecialchars($account['password']); ?>"
                                                data-price="<?php echo $account['price']; ?>"
                                                data-status="<?php echo $account['status']; ?>"
                                                data-basic-desc="<?php echo htmlspecialchars($account['basic_description']); ?>"
                                                data-detailed-desc="<?php echo htmlspecialchars($account['detailed_description'] ?? ''); ?>"
                                                data-description="<?php echo htmlspecialchars($account['description'] ?? ''); ?>"
                                                data-main-image="<?php echo !empty($account['image']) ? BASE_PATH . '/public/uploads/' . $account['image'] : ''; ?>"
                                                data-sub-images='<?php echo !empty($account['sub_images']) ? json_encode(array_map(function($img) { return BASE_PATH . '/public/uploads/' . $img; }, $account['sub_images'])) : '[]'; ?>'
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editAccountModal">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>
                                        <a href="<?php echo BASE_PATH; ?>/admin/accounts/delete/<?php echo $account['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm tài khoản -->
<div class="modal fade" id="addAccountModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm tài khoản game mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_PATH; ?>/admin/accounts/add" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Game</label>
                                <select class="form-select" name="game_id" required>
                                    <option value="">Chọn game</option>
                                    <?php 
                                    $seenGameIds = [];
                                    foreach ($games as $game):
                                        if (!isset($seenGameIds[$game['id']])):
                                            $seenGameIds[$game['id']] = true;
                                    ?>
                                        <option value="<?php echo $game['id']; ?>">
                                            <?php echo htmlspecialchars($game['name']); ?>
                                        </option>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" name="title" required
                                       placeholder="VD: Acc Liên Quân full skin, nhiều tướng">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tên tài khoản</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu</label>
                                <input type="text" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả cơ bản</label>
                                <textarea class="form-control" name="basic_description" rows="3" required
                                          placeholder="Mô tả ngắn gọn về tài khoản (hiển thị ở trang chủ)"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea class="form-control" name="detailed_description" rows="4"
                                          placeholder="Mô tả chi tiết về tài khoản (hiển thị ở trang chi tiết)"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả bổ sung</label>
                                <textarea class="form-control" name="description" rows="4"
                                          placeholder="Thông tin bổ sung về tài khoản (nếu có)"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giá bán</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="price" required min="0">
                                    <span class="input-group-text">đ</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status">
                                    <option value="available">Còn hàng</option>
                                    <option value="pending">Đang giao dịch</option>
                                    <option value="sold">Đã bán</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ảnh chính <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="main_image" accept="image/*" required>
                                <div class="form-text">Ảnh chính hiển thị ở trang chủ (jpg, png, gif)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ảnh phụ</label>
                                <input type="file" class="form-control" name="sub_images[]" accept="image/*" multiple>
                                <div class="form-text">Có thể chọn thêm tối đa 5 ảnh phụ (jpg, png, gif)</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal sửa tài khoản -->
<div class="modal fade" id="editAccountModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa thông tin tài khoản game</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAccountForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Game</label>
                                <select class="form-select" name="game_id" id="edit_game_id" required>
                                    <option value="">Chọn game</option>
                                    <?php foreach ($games as $game): ?>
                                        <option value="<?php echo $game['id']; ?>">
                                            <?php echo htmlspecialchars($game['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" name="title" id="edit_title" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tài khoản</label>
                                <input type="text" class="form-control" name="username" id="edit_username" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu</label>
                                <input type="text" class="form-control" name="password" id="edit_password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giá (VNĐ)</label>
                                <input type="number" class="form-control" name="price" id="edit_price" required min="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status" id="edit_status">
                                    <option value="available">Còn hàng</option>
                                    <option value="sold">Đã bán</option>
                                    <option value="pending">Đang giao dịch</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mô tả cơ bản</label>
                                <textarea class="form-control" name="basic_description" id="edit_basic_description" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea class="form-control" name="detailed_description" id="edit_detailed_description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Thông tin thêm</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ảnh chính</label>
                                <div id="edit_main_image_preview" class="mb-2"></div>
                                <input type="file" class="form-control" name="main_image" accept="image/*">
                                <small class="text-muted">Để trống nếu không muốn thay đổi ảnh chính</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ảnh phụ</label>
                                <div id="edit_sub_images_preview" class="row mb-2"></div>
                                <input type="file" class="form-control" name="sub_images[]" accept="image/*" multiple>
                                <small class="text-muted">Có thể chọn nhiều ảnh. Tối đa 5 ảnh phụ.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý sự kiện click nút sửa
    document.querySelectorAll('.edit-account').forEach(button => {
        button.addEventListener('click', function() {
            const btn = this;
            const form = document.getElementById('editAccountForm');
            
            // Lấy thông tin từ data attributes
            document.getElementById('edit_game_id').value = btn.getAttribute('data-game-id');
            document.getElementById('edit_title').value = btn.getAttribute('data-title');
            document.getElementById('edit_username').value = btn.getAttribute('data-username');
            document.getElementById('edit_password').value = btn.getAttribute('data-password');
            document.getElementById('edit_price').value = btn.getAttribute('data-price');
            document.getElementById('edit_status').value = btn.getAttribute('data-status');
            document.getElementById('edit_basic_description').value = btn.getAttribute('data-basic-desc');
            document.getElementById('edit_detailed_description').value = btn.getAttribute('data-detailed-desc');
            document.getElementById('edit_description').value = btn.getAttribute('data-description');

            // Hiển thị ảnh chính
            const mainImage = btn.getAttribute('data-main-image');
            const mainImagePreview = document.getElementById('edit_main_image_preview');
            if (mainImage) {
                mainImagePreview.innerHTML = `
                    <div class="position-relative mb-2">
                        <img src="${mainImage}" class="img-thumbnail" style="max-height: 200px;">
                        <div class="current-image-text">Ảnh hiện tại</div>
                    </div>`;
            } else {
                mainImagePreview.innerHTML = '<p class="text-muted">Chưa có ảnh chính</p>';
            }

            // Hiển thị ảnh phụ
            const subImages = JSON.parse(btn.getAttribute('data-sub-images') || '[]');
            const subImagesPreview = document.getElementById('edit_sub_images_preview');
            subImagesPreview.innerHTML = '';
            if (subImages && subImages.length > 0) {
                subImages.forEach((image, index) => {
                    subImagesPreview.innerHTML += `
                        <div class="col-md-4 mb-2">
                            <div class="position-relative">
                                <img src="${image}" class="img-thumbnail" style="max-height: 100px;">
                                <div class="form-check position-absolute" style="top: 5px; right: 5px;">
                                    <input class="form-check-input" type="checkbox" name="remove_sub_images[]" value="${index}">
                                    <label class="form-check-label">Xóa</label>
                                </div>
                            </div>
                        </div>`;
                });
            } else {
                subImagesPreview.innerHTML = '<p class="text-muted">Chưa có ảnh phụ</p>';
            }

            // Cập nhật action của form
            form.action = `${BASE_PATH}/admin/accounts/edit/${btn.getAttribute('data-id')}`;
        });
    });

    // Xử lý submit form
    document.getElementById('editAccountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Đóng modal
                bootstrap.Modal.getInstance(document.getElementById('editAccountModal')).hide();
                // Reload trang để cập nhật dữ liệu
                window.location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra khi cập nhật tài khoản');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật tài khoản');
        });
    });
});
</script>
<?php
$content = ob_get_clean();
require_once ROOT_PATH . "/App/views/admin/layout.php";
?> 