<?php
// Kiểm tra và khởi tạo biến $stats nếu chưa có
if (!isset($stats)) {
    $stats = [
        'total_users' => 0,
        'total_accounts' => 0,
        'total_games' => 0,
        'total_sales' => 0,
        'accounts_by_game' => [],
        'monthly_sales' => []
    ];
}

// Kiểm tra và khởi tạo biến $accounts nếu chưa có
if (!isset($accounts)) {
    $accounts = [];
}

$content = '
<div class="dashboard">
    <h1 class="page-title">Dashboard</h1>
    
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Tổng số người dùng</h3>
            <div class="number">' . ($stats['total_users'] ?? 0) . '</div>
        </div>
        <div class="stat-card">
            <h3>Tổng số tài khoản</h3>
            <div class="number">' . ($stats['total_accounts'] ?? 0) . '</div>
        </div>
        <div class="stat-card">
            <h3>Tổng số game</h3>
            <div class="number">' . ($stats['total_games'] ?? 0) . '</div>
        </div>
        <div class="stat-card">
            <h3>Tổng số giao dịch</h3>
            <div class="number">' . ($stats['total_sales'] ?? 0) . '</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Pie Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Phân bố tài khoản theo game</h2>
                </div>
                <div class="card-body">
                    <canvas id="accountsByGameChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Bar Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Doanh số bán hàng</h2>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Người dùng mới nhất</h2>
            <a href="/project-clone-web-buy-acc/src/project-root/public/admin/users" class="btn btn-primary">Xem tất cả</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Ngày đăng ký</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                ' . (isset($this->userModel) ? $this->userModel->getRecentUsers() : '<tr><td colspan="5" class="text-center">Không có dữ liệu</td></tr>') . '
            </tbody>
        </table>
    </div>

    <!-- Recent Accounts -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Tài khoản mới nhất</h2>
            <a href="/project-clone-web-buy-acc/src/project-root/public/admin/accounts" class="btn btn-primary">Xem tất cả</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên game</th>
                    <th>Giá</th>
                    <th>Người bán</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>';

if (empty($accounts)) {
    $content .= '<tr><td colspan="6" class="text-center py-4">Không có tài khoản game nào</td></tr>';
} else {
    $count = 0;
    foreach ($accounts as $account) {
        if ($count >= 5) break; // Chỉ hiển thị 5 tài khoản mới nhất
        
        $statusClass = "";
        $statusText = "";
        switch($account["status"] ?? 'pending') {
            case "available":
                $statusClass = "bg-success";
                $statusText = "Còn hàng";
                break;
            case "sold":
                $statusClass = "bg-secondary";
                $statusText = "Đã bán";
                break;
            default:
                $statusClass = "bg-warning";
                $statusText = "Đang giao dịch";
        }

        $content .= '<tr>
            <td>' . htmlspecialchars($account["id"] ?? '') . '</td>
            <td>' . htmlspecialchars($account["game_name"] ?? 'N/A') . '</td>
            <td class="text-end">' . number_format($account["price"] ?? 0, 0, ",", ".") . ' VNĐ</td>
            <td>' . htmlspecialchars($account["seller_name"] ?? 'N/A') . '</td>
            <td class="text-center">
                <span class="badge ' . $statusClass . '">
                    ' . $statusText . '
                </span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-primary edit-account" data-id="' . ($account["id"] ?? '') . '">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger delete-account" data-id="' . ($account["id"] ?? '') . '">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>';
        $count++;
    }
}

$content .= '</tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Dữ liệu cho biểu đồ phân bố tài khoản theo game
const accountsByGameData = {
    labels: ' . json_encode(array_column($stats["accounts_by_game"] ?? [], "game_name")) . ',
    data: ' . json_encode(array_column($stats["accounts_by_game"] ?? [], "count")) . ',
    datasets: [{
        backgroundColor: [
            "#FF6384",
            "#36A2EB",
            "#FFCE56",
            "#4BC0C0",
            "#9966FF",
            "#FF9F40"
        ]
    }]
};

// Dữ liệu cho biểu đồ doanh số
const salesData = {
    labels: ' . json_encode(array_column($stats["monthly_sales"] ?? [], "month")) . ',
    datasets: [{
        label: "Doanh số (VNĐ)",
        data: ' . json_encode(array_column($stats["monthly_sales"] ?? [], "total")) . ',
        backgroundColor: "rgba(54, 162, 235, 0.2)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 1
    }]
};

// Cấu hình và vẽ biểu đồ phân bố tài khoản
const accountsByGameChart = new Chart(
    document.getElementById("accountsByGameChart"),
    {
        type: "pie",
        data: accountsByGameData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom"
                },
                title: {
                    display: true,
                    text: "Phân bố tài khoản theo game"
                }
            }
        }
    }
);

// Cấu hình và vẽ biểu đồ doanh số
const salesChart = new Chart(
    document.getElementById("salesChart"),
    {
        type: "bar",
        data: salesData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat("vi-VN").format(value) + " VNĐ";
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: "bottom"
                },
                title: {
                    display: true,
                    text: "Doanh số bán hàng theo tháng"
                }
            }
        }
    }
);

// Xử lý sửa và xóa tài khoản
document.addEventListener("DOMContentLoaded", function() {
    // Xử lý nút sửa
    document.querySelectorAll(".edit-account").forEach(button => {
        button.addEventListener("click", function() {
            const id = this.getAttribute("data-id");
            // Mở modal sửa
            var modal = new bootstrap.Modal(document.getElementById("editAccountModal"));
            modal.show();
            
            // Gọi API lấy thông tin tài khoản
            fetch(`${BASE_PATH}/admin/accounts/get/${id}`)
                .then(response => response.json())
                .then(account => {
                    // Điền thông tin vào form
                    document.getElementById("edit_game_id").value = account.game_id;
                    document.getElementById("edit_title").value = account.title;
                    document.getElementById("edit_username").value = account.username;
                    document.getElementById("edit_password").value = account.password;
                    document.getElementById("edit_price").value = account.price;
                    document.getElementById("edit_status").value = account.status;
                    document.getElementById("edit_basic_description").value = account.basic_description;
                    document.getElementById("edit_detailed_description").value = account.detailed_description || "";
                    document.getElementById("edit_description").value = account.description || "";

                    // Hiển thị ảnh chính
                    const mainImagePreview = document.getElementById("edit_main_image_preview");
                    if (account.image) {
                        mainImagePreview.innerHTML = 
                            `<div class="position-relative mb-2">
                                <img src="${BASE_PATH}/public/uploads/${account.image}" class="img-thumbnail" style="max-height: 200px;">
                                <div class="current-image-text">Ảnh hiện tại</div>
                            </div>`;
                    } else {
                        mainImagePreview.innerHTML = \'<p class="text-muted">Chưa có ảnh chính</p>\';
                    }

                    // Hiển thị ảnh phụ
                    const subImagesPreview = document.getElementById("edit_sub_images_preview");
                    subImagesPreview.innerHTML = "";
                    if (account.sub_images && account.sub_images.length > 0) {
                        account.sub_images.forEach((image, index) => {
                            subImagesPreview.innerHTML += 
                                `<div class="col-md-4 mb-2">
                                    <div class="position-relative">
                                        <img src="${BASE_PATH}/public/uploads/${image}" class="img-thumbnail" style="max-height: 100px;">
                                        <div class="form-check position-absolute" style="top: 5px; right: 5px;">
                                            <input class="form-check-input" type="checkbox" name="remove_sub_images[]" value="${index}">
                                            <label class="form-check-label">Xóa</label>
                                        </div>
                                    </div>
                                </div>`;
                        });
                    } else {
                        subImagesPreview.innerHTML = \'<p class="text-muted">Chưa có ảnh phụ</p>\';
                    }

                    // Cập nhật action của form
                    document.getElementById("editAccountForm").action = `${BASE_PATH}/admin/accounts/edit/${id}`;
                });
        });
    });

    // Xử lý nút xóa
    document.querySelectorAll(".delete-account").forEach(button => {
        button.addEventListener("click", function() {
            const id = this.getAttribute("data-id");
            if (confirm("Bạn có chắc chắn muốn xóa tài khoản này?")) {
                window.location.href = `${BASE_PATH}/admin/accounts/delete/${id}`;
            }
        });
    });
});
</script>

<style>
.current-image-text {
    position: absolute;
    top: 5px;
    left: 5px;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 12px;
}
</style>
';

require_once ROOT_PATH . "/App/views/admin/layout.php"; 