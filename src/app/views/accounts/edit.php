<?php
$title = "Chỉnh sửa tài khoản " . $account['game_name'];
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Chỉnh sửa tài khoản game</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>accounts/<?php echo $account['id']; ?>/update" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="game_id" class="form-label">Game</label>
                        <select class="form-select" id="game_id" name="game_id" required>
                            <option value="">Chọn game</option>
                            <?php foreach($games as $game): ?>
                                <option value="<?php echo $game['id']; ?>" <?php echo $account['game_id'] == $game['id'] ? 'selected' : ''; ?>>
                                    <?php echo $game['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="level" class="form-label">Level</label>
                        <input type="number" class="form-control" id="level" name="level" value="<?php echo $account['level']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rank" class="form-label">Rank</label>
                        <input type="text" class="form-control" id="rank" name="rank" value="<?php echo $account['rank']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="server" class="form-label">Server</label>
                        <input type="text" class="form-control" id="server" name="server" value="<?php echo $account['server']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="price" class="form-label">Giá (VNĐ)</label>
                        <input type="number" class="form-control" id="price" name="price" value="<?php echo $account['price']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo $account['description']; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <div class="row">
                            <?php foreach($account['images'] as $image): ?>
                                <div class="col-md-3 mb-2">
                                    <img src="<?php echo BASE_URL; ?>assets/images/accounts/<?php echo $image['filename']; ?>" 
                                         class="img-thumbnail" alt="Hình ảnh tài khoản">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="images" class="form-label">Thêm hình ảnh mới</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        <small class="text-muted">Có thể chọn nhiều ảnh</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="account_info" class="form-label">Thông tin tài khoản</label>
                        <textarea class="form-control" id="account_info" name="account_info" rows="3" required><?php echo $account['account_info']; ?></textarea>
                        <small class="text-muted">Nhập thông tin đăng nhập tài khoản (sẽ được mã hóa)</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="<?php echo BASE_URL; ?>accounts/<?php echo $account['id']; ?>" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
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