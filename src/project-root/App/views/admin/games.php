<?php
ob_start();
?>
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mt-4">Quản lý danh mục</h2>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="<?php echo BASE_PATH; ?>/admin">Dashboard</a></li>
                <li class="breadcrumb-item active">Quản lý danh mục</li>
            </ol>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGameModal">
                <i class="fas fa-plus"></i> Thêm danh mục mới
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

    <?php if (isset($_SESSION["warning"])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo $_SESSION["warning"]; ?>
            <div class="mt-2">
                <a href="<?php echo BASE_PATH; ?>/admin/games/delete/<?php echo $_SESSION['game_to_delete']; ?>?force=1" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này và TẤT CẢ tài khoản liên quan?')">
                    Xóa danh mục và tất cả tài khoản
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="alert">
                    Hủy
                </button>
            </div>
            <?php 
            unset($_SESSION["warning"]);
            unset($_SESSION["game_to_delete"]);
            ?>
        </div>
    <?php endif; ?>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3" method="GET" id="searchForm">
                <div class="col-md-4">
                    <label class="form-label">Tên danh mục</label>
                    <div class="position-relative">
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               id="searchInput"
                               placeholder="Nhập tên danh mục..." 
                               autocomplete="off"
                               value="<?php echo isset($_GET["search"]) ? htmlspecialchars($_GET["search"]) : ""; ?>">
                        <div id="searchSuggestions" class="position-absolute w-100 mt-1 rounded shadow-sm bg-white" style="display:none; z-index:1000; max-height:200px; overflow-y:auto;"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sắp xếp theo</label>
                    <select class="form-select" name="sort">
                        <option value="name_asc" <?php echo (isset($_GET["sort"]) && $_GET["sort"] == "name_asc") ? "selected" : ""; ?>>Tên A-Z</option>
                        <option value="name_desc" <?php echo (isset($_GET["sort"]) && $_GET["sort"] == "name_desc") ? "selected" : ""; ?>>Tên Z-A</option>
                        <option value="newest" <?php echo (isset($_GET["sort"]) && $_GET["sort"] == "newest") ? "selected" : ""; ?>>Mới nhất</option>
                        <option value="oldest" <?php echo (isset($_GET["sort"]) && $_GET["sort"] == "oldest") ? "selected" : ""; ?>>Cũ nhất</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Số danh mục/trang</label>
                    <select class="form-select" name="limit">
                        <option value="10" <?php echo (isset($_GET["limit"]) && $_GET["limit"] == "10") ? "selected" : ""; ?>>10</option>
                        <option value="25" <?php echo (isset($_GET["limit"]) && $_GET["limit"] == "25") ? "selected" : ""; ?>>25</option>
                        <option value="50" <?php echo (isset($_GET["limit"]) && $_GET["limit"] == "50") ? "selected" : ""; ?>>50</option>
                        <option value="100" <?php echo (isset($_GET["limit"]) && $_GET["limit"] == "100") ? "selected" : ""; ?>>100</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-grid gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <button type="button" class="btn btn-secondary" id="resetSearch">
                            <i class="fas fa-undo"></i> Đặt lại
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-list me-1"></i>
            Danh sách danh mục
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px">ID</th>
                            <th style="width: 100px">Hình ảnh</th>
                            <th>Tên danh mục</th>
                            <th>Slug</th>
                            <th>Mô tả ngắn</th>
                            <th class="text-center" style="width: 150px">Ngày tạo</th>
                            <th class="text-center" style="width: 150px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($games)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">Không có danh mục nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($games as $game): ?>
                                <tr>
                                    <td class="text-center"><?php echo htmlspecialchars($game["id"]); ?></td>
                                    <td class="text-center">
                                        <img src="<?php echo BASE_PATH . ($game["image"] ?? '/public/images/default-game.png'); ?>" 
                                             alt="<?php echo htmlspecialchars($game["name"]); ?>" 
                                             class="img-thumbnail"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    </td>
                                    <td><?php echo htmlspecialchars($game["name"]); ?></td>
                                    <td><?php echo htmlspecialchars($game["slug"] ?? strtolower(str_replace(' ', '-', $game["name"]))); ?></td>
                                    <td><?php echo htmlspecialchars($game["short_description"] ?? (substr($game["description"], 0, 100) . '...')); ?></td>
                                    <td class="text-center"><?php echo date("d/m/Y H:i", strtotime($game["created_at"])); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary edit-game" data-id="<?php echo $game["id"]; ?>">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>
                                        <a href="<?php echo BASE_PATH; ?>/admin/games/delete/<?php echo $game["id"]; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Nếu danh mục có tài khoản liên quan, bạn sẽ được hỏi thêm.')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <?php if (isset($pagination) && $pagination['total'] > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Hiển thị <?php echo ($pagination['current'] - 1) * $pagination['limit'] + 1; ?> - 
                    <?php echo min($pagination['current'] * $pagination['limit'], $pagination['total_records']); ?> 
                    trên <?php echo $pagination['total_records']; ?> danh mục
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <?php if ($pagination['current'] > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php 
                                $_GET['page'] = $pagination['current'] - 1;
                                echo http_build_query($_GET);
                            ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $pagination['current'] - 2);
                        $end = min($pagination['total'], $pagination['current'] + 2);
                        
                        if ($start > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?' . 
                                 http_build_query(array_merge($_GET, ['page' => 1])) . 
                                 '">1</a></li>';
                            if ($start > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            echo '<li class="page-item ' . ($i == $pagination['current'] ? 'active' : '') . '">';
                            echo '<a class="page-link" href="?' . 
                                 http_build_query(array_merge($_GET, ['page' => $i])) . 
                                 '">' . $i . '</a></li>';
                        }

                        if ($end < $pagination['total']) {
                            if ($end < $pagination['total'] - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="?' . 
                                 http_build_query(array_merge($_GET, ['page' => $pagination['total']])) . 
                                 '">' . $pagination['total'] . '</a></li>';
                        }
                        ?>

                        <?php if ($pagination['current'] < $pagination['total']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php 
                                $_GET['page'] = $pagination['current'] + 1;
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

<!-- Modal thêm danh mục -->
<div class="modal fade" id="addGameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_PATH; ?>/admin/games/add" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" required>
                        <div class="form-text">Ví dụ: genshin-impact, honkai-star-rail</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" name="short_description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control" name="description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" name="image" accept="image/*" required>
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

<!-- Modal sửa danh mục -->
<div class="modal fade" id="editGameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa thông tin danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editGameForm" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" name="name" id="edit-name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" id="edit-slug" required>
                        <div class="form-text">Ví dụ: genshin-impact, honkai-star-rail</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" name="short_description" id="edit-short-description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control" name="description" id="edit-description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh mới (để trống nếu không đổi)</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <img id="edit-current-image" src="" alt="Game Image" class="img-thumbnail mt-2" style="max-width: 200px;">
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
document.addEventListener("DOMContentLoaded", function() {
    // Tự động tạo slug từ tên danh mục
    function generateSlug(str) {
        str = str.toLowerCase();
        str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        str = str.replace(/[đĐ]/g, 'd');
        str = str.replace(/([^0-9a-z-\s])/g, '');
        str = str.replace(/(\s+)/g, '-');
        str = str.replace(/-+/g, '-');
        str = str.replace(/^-+|-+$/g, '');
        return str;
    }

    // Áp dụng cho form thêm mới
    const nameInput = document.querySelector('#addGameModal input[name="name"]');
    const slugInput = document.querySelector('#addGameModal input[name="slug"]');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            slugInput.value = generateSlug(this.value);
        });
    }

    // Xử lý sự kiện click nút sửa
    document.querySelectorAll(".edit-game").forEach(button => {
        button.addEventListener("click", function() {
            const gameId = this.dataset.id;
            const basePath = "<?php echo BASE_PATH; ?>";
            
            // Gọi API để lấy thông tin danh mục
            fetch(`${basePath}/admin/games/get/${gameId}`)
                .then(response => response.json())
                .then(game => {
                    // Điền thông tin danh mục vào form
                    document.getElementById("edit-name").value = game.name;
                    document.getElementById("edit-slug").value = game.slug;
                    document.getElementById("edit-short-description").value = game.short_description;
                    document.getElementById("edit-description").value = game.description;
                    document.getElementById("edit-current-image").src = `${basePath}${game.image}`;
                    
                    // Cập nhật action của form
                    document.getElementById("editGameForm").action = `${basePath}/admin/games/edit/${gameId}`;
                    
                    // Hiển thị modal
                    new bootstrap.Modal(document.getElementById("editGameModal")).show();
                })
                .catch(error => {
                    console.error("Error fetching category data:", error);
                    alert("Có lỗi xảy ra khi tải thông tin danh mục. Vui lòng thử lại sau.");
                });
        });
    });

    // Xử lý tự động tạo slug khi sửa tên danh mục
    const editNameInput = document.querySelector('#editGameModal input[name="name"]');
    const editSlugInput = document.querySelector('#editGameModal input[name="slug"]');
    if (editNameInput && editSlugInput) {
        editNameInput.addEventListener('input', function() {
            editSlugInput.value = generateSlug(this.value);
        });
    }

    // Xử lý form sửa danh mục
    document.getElementById("editGameForm").addEventListener("submit", function(e) {
        // Không ngăn chặn form submit mặc định
        return true;
    });

    // Xử lý nút đặt lại tìm kiếm
    document.getElementById("resetSearch").addEventListener("click", function() {
        document.getElementById("searchForm").reset();
        window.location.href = window.location.pathname;
    });

    // Tự động submit form khi thay đổi select
    document.querySelectorAll('#searchForm select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById("searchForm").submit();
        });
    });

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Xử lý gợi ý tìm kiếm
    const searchInput = document.getElementById('searchInput');
    const suggestionBox = document.getElementById('searchSuggestions');
    let allGames = [];
    let currentFocus = -1;

    // Hàm lấy danh sách danh mục từ server
    function fetchGames() {
        fetch(`${window.location.pathname}?get_games=1`)
            .then(response => response.json())
            .then(games => {
                allGames = games;
            })
            .catch(error => console.error('Error fetching games:', error));
    }

    // Lấy danh sách danh mục khi trang được tải
    fetchGames();

    // Hàm hiển thị gợi ý
    function showSuggestions(value) {
        const searchText = value.toLowerCase();
        
        // Nếu input rỗng, ẩn gợi ý
        if (!searchText) {
            suggestionBox.style.display = 'none';
            return;
        }

        // Lọc danh mục phù hợp với từ khóa (chỉ theo tên)
        const matchedGames = allGames.filter(game => 
            game.name.toLowerCase().includes(searchText)
        ).slice(0, 5); // Giới hạn 5 kết quả

        if (matchedGames.length > 0) {
            suggestionBox.innerHTML = matchedGames.map((game, index) => `
                <div class="suggestion-item p-2 cursor-pointer hover-bg-light" 
                     data-index="${index}"
                     style="cursor: pointer;">
                    <div class="d-flex align-items-center">
                        <img src="${game.image || '/public/images/default-game.png'}" 
                             alt="${game.name}"
                             class="me-2 rounded"
                             style="width: 40px; height: 40px; object-fit: cover;">
                        <div class="fw-bold">${highlightMatch(game.name, searchText)}</div>
                    </div>
                </div>
            `).join('');

            // Thêm hover effect
            const items = suggestionBox.getElementsByClassName('suggestion-item');
            Array.from(items).forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                });
                item.addEventListener('click', function() {
                    searchInput.value = allGames[this.dataset.index].name;
                    suggestionBox.style.display = 'none';
                    document.getElementById('searchForm').submit();
                });
            });

            suggestionBox.style.display = 'block';
        } else {
            suggestionBox.style.display = 'none';
        }
    }

    // Hàm highlight từ khóa tìm kiếm trong text
    function highlightMatch(text, searchText) {
        if (!text) return '';
        const regex = new RegExp(`(${searchText})`, 'gi');
        return text.replace(regex, '<mark class="bg-warning">$1</mark>');
    }

    // Xử lý input
    searchInput.addEventListener('input', debounce(function(e) {
        showSuggestions(this.value);
    }, 300));

    // Xử lý phím mũi tên và enter
    searchInput.addEventListener('keydown', function(e) {
        const items = suggestionBox.getElementsByClassName('suggestion-item');
        
        if (e.key === 'ArrowDown') {
            currentFocus++;
            addActive(items);
            e.preventDefault();
        }
        else if (e.key === 'ArrowUp') {
            currentFocus--;
            addActive(items);
            e.preventDefault();
        }
        else if (e.key === 'Enter' && currentFocus > -1) {
            if (items[currentFocus]) {
                items[currentFocus].click();
                e.preventDefault();
            }
        }
    });

    // Thêm class active cho item được chọn
    function addActive(items) {
        if (!items || !items.length) return;

        removeActive(items);
        
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = items.length - 1;
        
        items[currentFocus].style.backgroundColor = '#e9ecef';
        searchInput.value = allGames[items[currentFocus].dataset.index].name;
    }

    // Xóa class active
    function removeActive(items) {
        Array.from(items).forEach(item => {
            item.style.backgroundColor = 'white';
        });
    }

    // Đóng suggestion box khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionBox.contains(e.target)) {
            suggestionBox.style.display = 'none';
        }
    });

    // Tự động ẩn thông báo sau 3 giây
    setTimeout(function() {
        document.querySelectorAll(".alert").forEach(alert => {
            bootstrap.Alert.getOrCreateInstance(alert).close();
        });
    }, 3000);
});
</script>
<?php
$content = ob_get_clean();
require_once ROOT_PATH . "/App/views/admin/layout.php";
?> 