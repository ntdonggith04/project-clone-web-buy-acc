<?php
$title = "Thêm tài khoản game";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Thêm tài khoản game mới</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>accounts/store" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="game_id" class="form-label">Game</label>
                        <select class="form-select" id="game_id" name="game_id" required>
                            <option value="">Chọn game</option>
                            <?php foreach($games as $game): ?>
                                <option value="<?php echo $game['id']; ?>"><?php echo $game['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="level" class="form-label">Level</label>
                        <input type="number" class="form-control" id="level" name="level" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rank" class="form-label">Rank</label>
                        <input type="text" class="form-control" id="rank" name="rank" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="server" class="form-label">Server</label>
                        <input type="text" class="form-control" id="server" name="server" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="price" class="form-label">Giá (VNĐ)</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="images" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        <small class="text-muted">Có thể chọn nhiều ảnh</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="account_info" class="form-label">Thông tin tài khoản</label>
                        <textarea class="form-control" id="account_info" name="account_info" rows="3" required></textarea>
                        <small class="text-muted">Nhập thông tin đăng nhập tài khoản (sẽ được mã hóa)</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
                        <a href="<?php echo BASE_URL; ?>accounts" class="btn btn-secondary">Hủy</a>
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