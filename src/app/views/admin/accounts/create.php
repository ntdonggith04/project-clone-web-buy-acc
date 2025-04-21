<?php
$title = "Thêm tài khoản game | " . SITE_NAME;
ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Thêm tài khoản game mới</h6>
                    <p class="text-sm mb-0">Nhập thông tin cho tài khoản game mới</p>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>admin/accounts/store" method="POST" enctype="multipart/form-data">
                        <!-- Game Selection -->
                        <div class="mb-3">
                            <label for="game_id" class="form-label">Game <span class="text-danger">*</span></label>
                            <select class="form-select" id="game_id" name="game_id" required>
                                <option value="">Chọn game</option>
                                <?php foreach ($games as $game) : ?>
                                    <option value="<?= $game['id'] ?>" <?= (isset($_POST['game_id']) && $_POST['game_id'] == $game['id']) ? 'selected' : '' ?>><?= $game['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['game_id'])) : ?>
                                <div class="text-danger text-xs mt-1"><?= $errors['game_id'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <!-- Account Username -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Tài khoản <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= $_POST['username'] ?? '' ?>" required>
                                    <?php if (isset($errors['username'])) : ?>
                                        <div class="text-danger text-xs mt-1"><?= $errors['username'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Account Password -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="password" name="password" value="<?= $_POST['password'] ?? '' ?>" required>
                                    <?php if (isset($errors['password'])) : ?>
                                        <div class="text-danger text-xs mt-1"><?= $errors['password'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Price -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" value="<?= $_POST['price'] ?? '' ?>" required>
                                    <?php if (isset($errors['price'])) : ?>
                                        <div class="text-danger text-xs mt-1"><?= $errors['price'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="available" <?= (isset($_POST['status']) && $_POST['status'] == 'available') ? 'selected' : 'selected' ?>>Còn hàng</option>
                                        <option value="pending" <?= (isset($_POST['status']) && $_POST['status'] == 'pending') ? 'selected' : '' ?>>Đang chờ</option>
                                        <option value="sold" <?= (isset($_POST['status']) && $_POST['status'] == 'sold') ? 'selected' : '' ?>>Đã bán</option>
                                    </select>
                                    <?php if (isset($errors['status'])) : ?>
                                        <div class="text-danger text-xs mt-1"><?= $errors['status'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Account Details -->
                        <div class="mb-3">
                            <label for="details" class="form-label">Thông tin chi tiết</label>
                            <textarea class="form-control" id="details" name="details" rows="4"><?= $_POST['details'] ?? '' ?></textarea>
                            <small class="text-muted">Nhập thông tin chi tiết về tài khoản, ví dụ: level, rank, tướng, skin...</small>
                            <?php if (isset($errors['details'])) : ?>
                                <div class="text-danger text-xs mt-1"><?= $errors['details'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Screenshots -->
                        <div class="mb-3">
                            <label for="screenshots" class="form-label">Ảnh tài khoản (tối đa 5 ảnh) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="screenshots" name="screenshots[]" multiple accept="image/*" required>
                            <small class="text-muted">Chọn ảnh chụp màn hình cho tài khoản (JPG, PNG)</small>
                            <?php if (isset($errors['screenshots'])) : ?>
                                <div class="text-danger text-xs mt-1"><?= $errors['screenshots'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
                            <a href="<?= BASE_URL ?>admin/accounts" class="btn btn-outline-secondary ms-2">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Div để hiển thị preview của ảnh -->
<div id="imagePreview" class="row mt-3"></div>

<script>
document.getElementById('screenshots').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (this.files) {
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-2';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail';
                img.style.maxHeight = '200px';
                
                col.appendChild(img);
                preview.appendChild(col);
            }
            
            reader.readAsDataURL(file);
        });
    }
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin.php';
?> 