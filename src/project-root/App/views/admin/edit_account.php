<?php
$content = '
<div class="container-fluid px-4">
    <h2 class="mt-4">Chỉnh sửa tài khoản game</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="' . BASE_PATH . '/admin">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="' . BASE_PATH . '/admin/accounts">Quản lý tài khoản game</a></li>
        <li class="breadcrumb-item active">Chỉnh sửa tài khoản</li>
    </ol>

    <div class="card mb-4">
        <div class="card-body">
            <form action="' . BASE_PATH . '/admin/accounts/edit/' . $account['id'] . '" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Game</label>
                            <select class="form-select" name="game_id" required>
                                <option value="">Chọn game</option>
                                <?php foreach ($games as $game): ?>
                                    <option value="<?php echo $game["id"]; ?>" <?php echo ($game["id"] == $account["game_id"]) ? "selected" : ""; ?>>
                                        <?php echo htmlspecialchars($game["name"]); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($account["title"]); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tài khoản</label>
                            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($account["username"]); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="text" class="form-control" name="password" value="<?php echo htmlspecialchars($account["password"]); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá (VNĐ)</label>
                            <input type="number" class="form-control" name="price" value="<?php echo $account["price"]; ?>" required min="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status">
                                <option value="available" <?php echo ($account["status"] == "available") ? "selected" : ""; ?>>Còn hàng</option>
                                <option value="sold" <?php echo ($account["status"] == "sold") ? "selected" : ""; ?>>Đã bán</option>
                                <option value="pending" <?php echo ($account["status"] == "pending") ? "selected" : ""; ?>>Đang giao dịch</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Mô tả cơ bản</label>
                            <textarea class="form-control" name="basic_description" rows="3" required><?php echo htmlspecialchars($account["basic_description"]); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả chi tiết</label>
                            <textarea class="form-control" name="detailed_description" rows="3"><?php echo htmlspecialchars($account["detailed_description"] ?? ""); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thông tin thêm</label>
                            <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($account["description"] ?? ""); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ảnh chính</label>
                            <?php 
                            $images = json_decode($account["images"], true);
                            $mainImage = $images["main"] ?? "/public/img/accounts/default/default-account.svg";
                            ?>
                            <div class="mb-2">
                                <img src="<?php echo $mainImage; ?>" alt="Main image" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                            <input type="file" class="form-control" name="main_image" accept="image/*">
                            <small class="text-muted">Để trống nếu không muốn thay đổi ảnh chính</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ảnh phụ</label>
                            <?php if (!empty($images["sub"])): ?>
                                <div class="row mb-2">
                                    <?php foreach ($images["sub"] as $index => $subImage): ?>
                                        <div class="col-md-4 mb-2">
                                            <div class="position-relative">
                                                <img src="<?php echo $subImage; ?>" alt="Sub image <?php echo $index + 1; ?>" class="img-thumbnail" style="max-height: 100px;">
                                                <div class="form-check position-absolute" style="top: 5px; right: 5px;">
                                                    <input class="form-check-input" type="checkbox" name="remove_sub_images[]" value="<?php echo $index; ?>">
                                                    <label class="form-check-label">Xóa</label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="sub_images[]" accept="image/*" multiple>
                            <small class="text-muted">Có thể chọn nhiều ảnh. Tối đa 5 ảnh phụ.</small>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <a href="' . BASE_PATH . '/admin/accounts" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
';

require_once ROOT_PATH . "/App/views/admin/layout.php"; 