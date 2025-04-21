<?php
$title = "Chỉnh sửa danh mục game | " . SITE_NAME;
ob_start();
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa danh mục game</h1>
        <a href="<?= BASE_URL ?>admin/games" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
        </a>
    </div>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin danh mục game</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>admin/games/update/<?= $game['id'] ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($game['name']) ?>" required>
                            <?php if(isset($errors['name'])): ?>
                                <small class="text-danger"><?= $errors['name'] ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($game['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="image">Hình ảnh danh mục</label>
                            <?php if(!empty($game['image'])): ?>
                                <div class="mb-2">
                                    <img src="<?= BASE_URL ?>uploads/games/<?= $game['image'] ?>" alt="<?= htmlspecialchars($game['name']) ?>" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Để trống nếu không muốn thay đổi hình ảnh</small>
                            <?php if(isset($errors['image'])): ?>
                                <small class="text-danger"><?= $errors['image'] ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../layouts/admin.php';
?> 