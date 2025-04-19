<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <?php if (isset($account)): ?>
            <!-- Breadcrumb -->
            <nav class="mb-4">
                <ol class="flex items-center space-x-2 text-gray-600">
                    <li><a href="<?php echo BASE_PATH; ?>" class="hover:text-blue-600">Trang chủ</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="<?php echo BASE_PATH; ?>/accounts" class="hover:text-blue-600">Tài khoản game</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900"><?php echo htmlspecialchars($account['title']); ?></li>
                </ol>
            </nav>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                    <!-- Image Gallery -->
                    <div class="space-y-4">
                        <?php 
                        $images = json_decode($account['images'], true);
                        $main_image = isset($images['main']) ? $images['main'] : '/public/img/default-account.jpg';
                        ?>
                        <div class="main-image-container relative aspect-w-4 aspect-h-3 rounded-lg overflow-hidden">
                            <img src="<?php echo BASE_PATH . $main_image; ?>" 
                                 alt="<?php echo htmlspecialchars($account['title']); ?>"
                                 class="w-full h-full object-cover" 
                                 id="mainImage">
                        </div>
                        
                        <?php if (!empty($images['sub'])): ?>
                            <div class="grid grid-cols-5 gap-2">
                                <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden cursor-pointer hover:opacity-75 transition-opacity">
                                    <img src="<?php echo BASE_PATH . $main_image; ?>" 
                                         alt="Main Image"
                                         class="w-full h-full object-cover thumbnail active"
                                         onclick="changeMainImage(this.src)">
                                </div>
                                <?php foreach ($images['sub'] as $sub_image): ?>
                                    <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden cursor-pointer hover:opacity-75 transition-opacity">
                                        <img src="<?php echo BASE_PATH . $sub_image; ?>" 
                                             alt="Sub Image"
                                             class="w-full h-full object-cover thumbnail"
                                             onclick="changeMainImage(this.src)">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Account Details -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4">
                            <img src="<?php echo BASE_PATH; ?>/public/img/games/<?php echo strtolower(str_replace(' ', '-', $account['game_name'])); ?>-icon.jpg" 
                                 alt="<?php echo htmlspecialchars($account['game_name']); ?>"
                                 class="w-12 h-12 rounded-full">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($account['title']); ?></h1>
                                <p class="text-gray-600"><?php echo htmlspecialchars($account['game_name']); ?></p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">Thông tin tài khoản</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Tên đăng nhập</p>
                                    <p class="font-medium"><?php echo htmlspecialchars($account['username']); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Mật khẩu</p>
                                    <p class="font-medium"><?php echo htmlspecialchars($account['password']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">Mô tả tài khoản</h2>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-600 whitespace-pre-line"><?php echo nl2br(htmlspecialchars($account['basic_description'])); ?></p>
                            </div>
                        </div>

                        <div class="border-t pt-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">Giá bán</p>
                                    <p class="text-3xl font-bold text-red-600"><?php echo number_format($account['price'], 0, ',', '.'); ?>đ</p>
                                </div>
                                <div class="space-x-4">
                                    <button class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                        Mua ngay
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="text-gray-500">Không tìm thấy tài khoản</div>
                <a href="<?php echo BASE_PATH; ?>/accounts" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                    Quay lại danh sách tài khoản
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.aspect-w-4 { position: relative; padding-bottom: 75%; }
.aspect-w-1 { position: relative; padding-bottom: 100%; }
.aspect-h-3, .aspect-h-1 { position: absolute; top: 0; right: 0; bottom: 0; left: 0; }

.thumbnail.active {
    border: 2px solid #3b82f6;
}

.whitespace-pre-line {
    white-space: pre-line;
}

@media (max-width: 768px) {
    .grid-cols-5 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
        if (thumb.src === src) {
            thumb.classList.add('active');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails[0]?.classList.add('active');
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 