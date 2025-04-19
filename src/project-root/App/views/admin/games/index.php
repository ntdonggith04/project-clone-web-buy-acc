<?php require_once ROOT_PATH . '/App/views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Quản lý Game</h1>

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
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Game</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Game</th>
                            <th>Hình ảnh</th>
                            <th>Mô tả ngắn</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($games as $game): ?>
                        <tr>
                            <td><?php echo $game['id']; ?></td>
                            <td><?php echo htmlspecialchars($game['name']); ?></td>
                            <td>
                                <img src="<?php echo BASE_PATH . '/' . ($game['image'] ?? 'images/default-game.png'); ?>" 
                                     alt="<?php echo htmlspecialchars($game['name']); ?>"
                                     style="max-width: 100px;">
                            </td>
                            <td><?php echo htmlspecialchars($game['short_description']); ?></td>
                            <td>
                                <a href="<?php echo BASE_PATH; ?>/admin/games/edit/<?php echo $game['id']; ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/App/views/admin/layouts/footer.php'; ?> 