<?php
ob_start();
?>
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mt-4">Quản lý người dùng</h2>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="<?php echo BASE_PATH; ?>/admin">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý người dùng</li>
            </ol>
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
            <form class="row g-3" method="GET" id="searchForm">
                <div class="col-md-4">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           placeholder="Tên, email, số điện thoại..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vai trò</label>
                    <select class="form-select" name="role">
                        <option value="">Tất cả</option>
                        <option value="admin" <?php echo (isset($_GET['role']) && $_GET['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo (isset($_GET['role']) && $_GET['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                        <option value="">Tất cả</option>
                        <option value="active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>Hoạt động</option>
                        <option value="inactive" <?php echo (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : ''; ?>>Khóa</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hiển thị</label>
                    <select class="form-select" name="limit">
                        <option value="10" <?php echo (isset($_GET['limit']) && $_GET['limit'] == '10') ? 'selected' : ''; ?>>10</option>
                        <option value="25" <?php echo (isset($_GET['limit']) && $_GET['limit'] == '25') ? 'selected' : ''; ?>>25</option>
                        <option value="50" <?php echo (isset($_GET['limit']) && $_GET['limit'] == '50') ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo (isset($_GET['limit']) && $_GET['limit'] == '100') ? 'selected' : ''; ?>>100</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="d-grid gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" class="btn btn-secondary" id="resetSearch">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Danh sách người dùng
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px">ID</th>
                            <th style="width: 80px">Avatar</th>
                            <th>Tên người dùng</th>
                            <th>Email</th>
                            <th class="text-center" style="width: 120px">Vai trò</th>
                            <th class="text-center" style="width: 120px">Trạng thái</th>
                            <th class="text-center" style="width: 150px">Ngày tạo</th>
                            <th class="text-center" style="width: 200px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">Không có người dùng nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr data-user-id="<?php echo $user["id"]; ?>">
                                    <td class="text-center"><?php echo htmlspecialchars($user["id"]); ?></td>
                                    <td class="text-center">
                                        <img src="<?php echo BASE_PATH . $user["avatar"]; ?>" 
                                             alt="Avatar" 
                                             class="rounded-circle"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td><?php echo htmlspecialchars($user["username"]); ?></td>
                                    <td><?php echo htmlspecialchars($user["email"]); ?></td>
                                    <td class="text-center">
                                        <span class="badge <?php echo $user["role"] == "admin" ? "bg-danger" : "bg-primary"; ?>">
                                            <?php echo $user["role"] == "admin" ? "Admin" : "Khách hàng"; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?php echo $user["status"] == "active" ? "bg-success" : "bg-warning"; ?>">
                                            <?php echo $user["status"] == "active" ? "Hoạt động" : "Khóa"; ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo date("d/m/Y H:i", strtotime($user["created_at"])); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary edit-user" data-id="<?php echo $user["id"]; ?>">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>
                                        <?php if ($user["role"] != "admin"): ?>
                                            <button class="btn btn-sm btn-danger delete-user" data-id="<?php echo $user["id"]; ?>">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Hiển thị <?php echo $pagination['start'] + 1; ?> - 
                    <?php echo min($pagination['start'] + $pagination['limit'], $pagination['total_records']); ?> 
                    trên <?php echo $pagination['total_records']; ?> người dùng
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?php 
                                    $_GET['page'] = $pagination['current_page'] - 1;
                                    echo http_build_query($_GET);
                                ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $pagination['current_page'] - 2);
                        $end = min($pagination['total_pages'], $pagination['current_page'] + 2);

                        if ($start > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?' . 
                                 http_build_query(array_merge($_GET, ['page' => 1])) . 
                                 '">1</a></li>';
                            if ($start > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            echo '<li class="page-item ' . ($i == $pagination['current_page'] ? 'active' : '') . '">';
                            echo '<a class="page-link" href="?' . 
                                 http_build_query(array_merge($_GET, ['page' => $i])) . 
                                 '">' . $i . '</a></li>';
                        }

                        if ($end < $pagination['total_pages']) {
                            if ($end < $pagination['total_pages'] - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="?' . 
                                 http_build_query(array_merge($_GET, ['page' => $pagination['total_pages']])) . 
                                 '">' . $pagination['total_pages'] . '</a></li>';
                        }
                        ?>

                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?php 
                                    $_GET['page'] = $pagination['current_page'] + 1;
                                    echo http_build_query($_GET);
                                ?>" aria-label="Next">
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

<!-- Modal sửa người dùng -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa thông tin người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên người dùng</label>
                        <input type="text" class="form-control" name="username" id="edit-username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit-email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Avatar mới (để trống nếu không đổi)</label>
                        <input type="file" class="form-control" name="avatar" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Avatar hiện tại</label>
                        <img id="edit-current-avatar" src="" alt="Avatar" class="d-block rounded mt-2" style="max-width: 100px;">
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

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa người dùng này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Xử lý reset form tìm kiếm
    document.getElementById('resetSearch').addEventListener('click', function() {
        window.location.href = '<?php echo BASE_PATH; ?>/admin/users';
    });

    // Tự động submit form khi thay đổi select
    document.querySelectorAll('#searchForm select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    });

    // Xử lý nút sửa người dùng
    document.querySelectorAll(".edit-user").forEach(button => {
        button.addEventListener("click", function() {
            const userId = this.dataset.id;
            const basePath = "<?php echo BASE_PATH; ?>";
            
            // Gọi API lấy thông tin người dùng
            fetch(basePath + "/admin/users/get/" + userId)
                .then(response => response.json())
                .then(user => {
                    document.getElementById("edit-username").value = user.username;
                    document.getElementById("edit-email").value = user.email;
                    document.getElementById("edit-current-avatar").src = basePath + user.avatar;
                    
                    // Cập nhật action của form
                    document.getElementById("editUserForm").action = basePath + "/admin/users/edit/" + userId;
                    
                    // Hiển thị modal
                    new bootstrap.Modal(document.getElementById("editUserModal")).show();
                });
        });
    });

    // Xử lý nút xóa
    const deleteButtons = document.querySelectorAll('.delete-user');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-id');
            deleteForm.action = `<?php echo BASE_PATH; ?>/admin/users/delete/${userId}`;
            deleteModal.show();
        });
    });

    // Xử lý submit form xóa
    deleteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
        submitBtn.disabled = true;

        // Sử dụng XMLHttpRequest thay vì fetch
        const xhr = new XMLHttpRequest();
        xhr.open('POST', this.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            console.log('Status:', xhr.status);
            console.log('Response:', xhr.responseText);
            
            let response;
            try {
                if (xhr.responseText) {
                    response = JSON.parse(xhr.responseText);
                    console.log('Parsed response:', response);
                } else {
                    console.log('Empty response received');
                    response = {
                        success: false,
                        message: 'Không nhận được phản hồi từ server'
                    };
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                console.log('Raw response:', xhr.responseText);
                response = {
                    success: false,
                    message: 'Có lỗi xảy ra khi xử lý phản hồi từ server'
                };
            }

            // Tạo và hiển thị thông báo
            showAlert(response.success, response.message);

            if (response.success) {
                // Xóa dòng người dùng khỏi bảng
                const userRow = document.querySelector(`tr[data-user-id="${response.userId}"]`);
                if (userRow) {
                    userRow.remove();
                }

                // Ẩn modal
                deleteModal.hide();

                // Kiểm tra nếu không còn user nào thì reload trang
                const remainingRows = document.querySelectorAll('tbody tr');
                if (remainingRows.length === 0 || remainingRows[0].textContent.trim() === 'Không có người dùng nào') {
                    location.reload();
                }
            }

            // Khôi phục nút submit
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        };

        xhr.onerror = function() {
            console.error('Network error occurred');
            showAlert(false, 'Lỗi kết nối đến server');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        };

        // Gửi request
        xhr.send();
    });

    // Hàm hiển thị thông báo
    function showAlert(isSuccess, message) {
        const alertClass = isSuccess ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        const container = document.querySelector('.container-fluid');
        const firstChild = container.firstChild;
        const alertElement = new DOMParser()
            .parseFromString(alertHtml, 'text/html')
            .body.firstChild;
        
        container.insertBefore(alertElement, firstChild);

        // Tự động ẩn thông báo sau 3 giây
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertElement);
            bsAlert.close();
        }, 3000);
    }
});
</script>
<?php
$content = ob_get_clean();
require_once ROOT_PATH . "/App/views/admin/layout.php";
?> 