<?php require_once ROOT_PATH . '/App/views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Sửa Game</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin Game</h6>
        </div>
        <div class="card-body">
            <form action="<?php echo BASE_PATH; ?>/admin/games/update/<?php echo $game['id']; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Tên Game</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($game['name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="short_description">Mô tả ngắn</label>
                            <textarea class="form-control" id="short_description" name="short_description" rows="3" required><?php echo htmlspecialchars($game['short_description']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả chi tiết</label>
                            <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($game['description']); ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="current_image">Ảnh hiện tại</label>
                            <?php if (!empty($game['image'])): ?>
                                <div class="mb-3">
                                    <img src="<?php echo BASE_PATH . '/' . $game['image']; ?>" 
                                         alt="Current game image" 
                                         style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="image">Thay đổi ảnh</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="<?php echo BASE_PATH; ?>/admin/games" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/App/views/admin/layouts/footer.php'; ?> 