<?php
$title = "Trang chủ";
?>

<!-- Hero Section with Overlay và Carousel -->
<section class="hero-section position-relative">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" class="d-block w-100 carousel-img" alt="Game 1" style="height: 600px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block slide-caption caption-1">
                    <h2 class="display-4 fw-bold text-uppercase gradient-text">Khám Phá Thế Giới Game</h2>
                    <p class="lead">Hàng ngàn tài khoản game đang chờ đợi bạn</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" class="d-block w-100 carousel-img" alt="Game 2" style="height: 600px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block slide-caption caption-2">
                    <h2 class="display-4 fw-bold text-uppercase gradient-text">Mua Bán An Toàn</h2>
                    <p class="lead">Giao dịch nhanh chóng, bảo mật tuyệt đối</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" class="d-block w-100 carousel-img" alt="Game 3" style="height: 600px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block slide-caption caption-3">
                    <h2 class="display-4 fw-bold text-uppercase gradient-text">Bắt Đầu Ngay</h2>
                    <p class="lead">Trải nghiệm game với tài khoản chất lượng</p>
                </div>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        
        <!-- Text overlay -->
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white hero-content" style="z-index: 10; width: 80%;">
            <h1 class="display-3 fw-bold mb-3 text-uppercase animated fadeInUp" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.7); color: wheat;">Mua Bán Tài Khoản Game</h1>
            <p class="lead fs-4 mb-4 animated fadeInUp" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.7); animation-delay: 0.3s;">Nơi giao dịch tài khoản game an toàn, nhanh chóng và tiện lợi</p>
            <div class="d-flex justify-content-center animated fadeInUp" style="animation-delay: 0.6s;">
                <a href="<?= BASE_URL ?>accounts" class="btn btn-success btn-lg me-3 px-4 py-2">Xem tài khoản ngay</a>
                <a href="<?= BASE_URL ?>games" class="btn btn-outline-light btn-lg px-4 py-2">Tất cả game</a>
            </div>
        </div>
        
        <!-- Dark overlay -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.4));"></div>
    </div>
</section>

<!-- Stats Section - Replace with How It Works -->
<section class="how-it-works bg-white py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title display-5 fw-bold pb-2">Cách Thức Hoạt Động</h2>
            <p class="lead text-muted mt-4">Quy trình đơn giản để sở hữu tài khoản game</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                <div class="process-item text-center p-4 rounded h-100" style="background: rgba(13, 110, 253, 0.1); box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                    <div class="process-icon mb-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 80px; height: 80px; background-color: rgba(13, 110, 253, 0.2); border-radius: 50%;">
                            <i class="fas fa-search fa-2x" style="color: #0d6efd;"></i>
                        </div>
                        <div class="step-number badge bg-primary rounded-pill px-3 py-2 mb-3">Bước 1</div>
                    </div>
                    <h3 class="h4 mb-3 fw-bold">Tìm Tài Khoản</h3>
                    <p class="text-muted">Tìm kiếm tài khoản game phù hợp với nhu cầu của bạn từ kho tài khoản đa dạng của chúng tôi.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                <div class="process-item text-center p-4 rounded h-100" style="background: rgba(25, 135, 84, 0.1); box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                    <div class="process-icon mb-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 80px; height: 80px; background-color: rgba(25, 135, 84, 0.2); border-radius: 50%;">
                            <i class="fas fa-shopping-cart fa-2x" style="color: #198754;"></i>
                        </div>
                        <div class="step-number badge bg-success rounded-pill px-3 py-2 mb-3">Bước 2</div>
                    </div>
                    <h3 class="h4 mb-3 fw-bold">Mua Tài Khoản</h3>
                    <p class="text-muted">Tiến hành thanh toán nhanh chóng qua các phương thức đa dạng, an toàn và bảo mật.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                <div class="process-item text-center p-4 rounded h-100" style="background: rgba(255, 193, 7, 0.1); box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                    <div class="process-icon mb-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 80px; height: 80px; background-color: rgba(255, 193, 7, 0.2); border-radius: 50%;">
                            <i class="fas fa-gamepad fa-2x" style="color: #ffc107;"></i>
                        </div>
                        <div class="step-number badge bg-warning rounded-pill px-3 py-2 mb-3">Bước 3</div>
                    </div>
                    <h3 class="h4 mb-3 fw-bold">Nhận Tài Khoản</h3>
                    <p class="text-muted">Nhận thông tin tài khoản ngay lập tức sau khi thanh toán và bắt đầu trải nghiệm game.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Games Section - Changed to Categories -->
<section class="featured-games">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title display-5 fw-bold pb-2">Danh Mục Game</h2>
            <p class="lead text-muted mt-4">Khám phá các danh mục game đa dạng với nhiều tài khoản chất lượng</p>
        </div>
        
        <div class="row g-4">
            <?php if(!empty($games)): ?>
                <?php foreach ($games as $index => $game): ?>
                    <div class="col-md-4 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>" data-aos-once="true">
                        <div class="card game-card h-100 border-0 rounded-4 overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img src="<?= !empty($game['image']) ? BASE_URL . 'uploads/games/' . $game['image'] : 'https://via.placeholder.com/300x200?text=' . urlencode($game['name']) ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($game['name']) ?>"
                                     style="height: 200px; object-fit: cover;">
                                <div class="game-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(13, 110, 253, 0.2); opacity: 0; transition: all 0.3s ease;">
                                    <a href="<?= BASE_URL ?>games/<?= htmlspecialchars($game['slug']) ?>" class="btn btn-light">Xem chi tiết</a>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h3 class="h5 mb-0 fw-bold"><?= htmlspecialchars($game['name']) ?></h3>
                                    <span class="badge bg-success rounded-pill"><?= $game['account_count'] ?? 0 ?></span>
                                </div>
                                <p class="card-text text-muted small mb-3">
                                    <?= htmlspecialchars(substr($game['description'] ?? '', 0, 100)) ?>...
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <?php if (isset($game['min_price']) && $game['min_price'] > 0): ?>
                                        <span class="fw-bold text-primary">Từ <?= number_format($game['min_price']) ?>đ</span>
                                    <?php else: ?>
                                        <span class="text-muted">Liên hệ</span>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>games/<?= htmlspecialchars($game['slug']) ?>" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        Chưa có game nào được thêm vào.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>games" class="btn btn-outline-primary btn-lg px-5">Xem tất cả danh mục</a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose-us bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title display-5 fw-bold pb-2">Tại Sao Chọn Chúng Tôi</h2>
            <p class="lead text-muted mt-4">Những lý do khiến chúng tôi trở thành lựa chọn số 1</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
                <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4">
                    <div class="feature-icon rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                        style="width: 80px; height: 80px; background-color: rgba(13, 110, 253, 0.1);">
                        <i class="fas fa-shield-alt fa-2x text-primary"></i>
                    </div>
                    <div class="card-body p-0">
                        <h3 class="h5 fw-bold mb-3">Uy Tín</h3>
                        <p class="text-muted small">Cam kết tài khoản chất lượng, không gian lận với lịch sử giao dịch minh bạch.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
                <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4">
                    <div class="feature-icon rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                        style="width: 80px; height: 80px; background-color: rgba(13, 110, 253, 0.1);">
                        <i class="fas fa-tags fa-2x text-primary"></i>
                    </div>
                    <div class="card-body p-0">
                        <h3 class="h5 fw-bold mb-3">Giá Tốt</h3>
                        <p class="text-muted small">Giá cả cạnh tranh, phù hợp với mọi người chơi cùng các chương trình khuyến mãi thường xuyên.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300" data-aos-once="true">
                <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4">
                    <div class="feature-icon rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                        style="width: 80px; height: 80px; background-color: rgba(13, 110, 253, 0.1);">
                        <i class="fas fa-headset fa-2x text-primary"></i>
                    </div>
                    <div class="card-body p-0">
                        <h3 class="h5 fw-bold mb-3">Hỗ Trợ</h3>
                        <p class="text-muted small">Hỗ trợ khách hàng 24/7, giải đáp mọi thắc mắc nhanh chóng, tận tâm.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400" data-aos-once="true">
                <div class="card h-100 border-0 shadow-sm text-center p-4 rounded-4">
                    <div class="feature-icon rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4"
                        style="width: 80px; height: 80px; background-color: rgba(13, 110, 253, 0.1);">
                        <i class="fas fa-sync fa-2x text-primary"></i>
                    </div>
                    <div class="card-body p-0">
                        <h3 class="h5 fw-bold mb-3">Hoàn Tiền</h3>
                        <p class="text-muted small">Chính sách hoàn tiền 100% nếu tài khoản không đúng như mô tả.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta" style="background: linear-gradient(to right, #0d6efd, #6610f2);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-white" data-aos="fade-right" data-aos-once="true">
                <h2 class="display-6 fw-bold mb-3">Bắt đầu trải nghiệm game ngay hôm nay!</h2>
                <p class="lead mb-0">Hàng ngàn tài khoản game chất lượng đang chờ đợi bạn.</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0" data-aos="fade-left" data-aos-once="true">
                <a href="<?= BASE_URL ?>accounts" class="btn btn-light btn-lg px-5 py-3 fw-bold">Khám phá ngay</a>
            </div>
        </div>
    </div>
</section>

<style>
    /* Custom styles for the homepage */
    .hero-section {
        padding-top: 0;
        overflow: hidden;
        position: relative;
        z-index: 0; /* Đảm bảo hero section nằm dưới navbar */
    }
    
    /* Đặt khoảng cách giữa các section */
    .stats-section {
        padding: 3.5rem 0;
    }
    
    .featured-games {
        padding: 4rem 0;
    }
    
    .how-it-works {
        padding: 4.5rem 0;
    }
    
    .why-choose-us {
        padding: 4.5rem 0;
    }
    
    .cta {
        padding: 4rem 0;
        margin-bottom: 0;
    }
    
    /* Giữ các hiệu ứng carousel như cũ */
    .carousel-fade .carousel-item {
        transition: opacity 1.5s cubic-bezier(0.7, 0, 0.3, 1);
    }
    
    .carousel-item img {
        filter: brightness(0.8);
        transition: transform 7s ease-in-out;
        transform: scale(1.05);
    }
    
    .carousel-item.active img {
        transform: scale(1);
    }
    
    .slide-caption {
        opacity: 0;
        transform: translateY(30px);
        transition: all 1s ease-out 0.5s;
    }
    
    .carousel-item.active .slide-caption {
        opacity: 1;
        transform: translateY(0);
    }
    
    .caption-1 {
        background: rgba(13, 110, 253, 0.7);
        padding: 1rem 2rem;
        border-radius: 5px;
    }
    
    .caption-2 {
        background: rgba(25, 135, 84, 0.7);
        padding: 1rem 2rem;
        border-radius: 5px;
    }
    
    .caption-3 {
        background: rgba(220, 53, 69, 0.7);
        padding: 1rem 2rem;
        border-radius: 5px;
    }
    
    .carousel-control-prev, .carousel-control-next {
        width: 5%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .hero-section:hover .carousel-control-prev,
    .hero-section:hover .carousel-control-next {
        opacity: 0.8;
    }
    
    .carousel-indicators {
        z-index: 15;
    }
    
    .carousel-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 7px;
        background-color: white;
        opacity: 0.5;
        transition: all 0.3s ease;
    }
    
    .carousel-indicators button.active {
        background-color: var(--primary-color);
        transform: scale(1.2);
        opacity: 1;
    }
    
    /* Existing styles */
    .section-title:after {
        content: '';
        position: absolute;
        width: 80px;
        height: 3px;
        background-color: var(--primary-color);
        bottom: -10px;
        left: calc(50% - 40px);
    }
    
    .game-card:hover .game-overlay {
        opacity: 1;
    }
    
    .stat-item {
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .stat-item:hover {
        transform: translateY(-10px);
        background: rgba(255,255,255,0.2) !important;
    }
    
    .stat-icon {
        color: rgba(255,255,255,0.7);
        transition: all 0.3s ease;
    }
    
    .stat-item:hover .stat-icon {
        color: white;
        transform: scale(1.2);
    }
    
    .counter {
        transition: all 0.5s ease;
    }
    
    /* Parallax effect for CTA section */
    .cta {
        background-attachment: fixed;
        position: relative;
        overflow: hidden;
    }
    
    .cta:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('https://images.unsplash.com/photo-1560419015-7c427e8ae5ba?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80') center/cover no-repeat;
        opacity: 0.1;
        z-index: -1;
    }
    
    @media (max-width: 768px) {
        .hero-section {
            height: auto;
        }
        
        .carousel-item img {
            height: 400px !important;
        }
        
        .hero-content h1 {
            font-size: 2rem;
        }
        
        .hero-content p {
            font-size: 1rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
    }
    
    /* Gradient Text Effects */
    .gradient-text {
        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
        display: inline-block;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }
    
    .caption-1 .gradient-text {
        background-image: linear-gradient(45deg, #ff7e5f, #feb47b);
        filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.5));
    }
    
    .caption-2 .gradient-text {
        background-image: linear-gradient(45deg, #0061ff, #60efff);
        filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.5));
    }
    
    .caption-3 .gradient-text {
        background-image: linear-gradient(45deg, #1ed761, #8bc34a);
        filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.5));
    }
    
    /* Carousel Styles */
    .carousel-fade .carousel-item {
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
    }
    
    .carousel-fade .carousel-item.active {
        opacity: 1;
    }
    
    .carousel-caption {
        background-color: rgba(0,0,0,0.3);
        backdrop-filter: blur(5px);
        border-radius: 10px;
        padding: 1.5rem;
    }
    
    .slide-caption {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s ease 0.3s;
    }
    
    .carousel-item.active .slide-caption {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Thêm hiệu ứng đổ bóng cho văn bản */
    .carousel-caption p.lead {
        text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        color: white;
        font-weight: 500;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lỗi hiệu ứng carousel - sửa bằng cách viết lại mã khởi tạo
        try {
            // Khởi tạo carousel một cách trực tiếp với đối tượng DOM
            const carousel = document.getElementById('heroCarousel');
            if (carousel) {
                const carouselInstance = new bootstrap.Carousel(carousel, {
                    interval: 3000,
                    wrap: true,
                    keyboard: true,
                    pause: 'hover',
                    ride: 'carousel'
                });
                
                // Log debug
                console.log('Carousel initialized successfully');
                
                // Đảm bảo nó khởi động tự động
                setTimeout(function() {
                    carouselInstance.cycle();
                }, 100);
            }
        } catch (error) {
            console.error('Error initializing carousel:', error);
        }
        
        // Hiệu ứng đếm cho thống kê
        const animateCounters = function() {
            const counters = document.querySelectorAll('.counter');
            
            if (counters.length > 0) {
                counters.forEach(counter => {
                    const target = parseInt(counter.innerText) || 0;
                    let count = 0;
                    const duration = 2000; // 2 seconds
                    const increment = target / (duration / 30); // 30 fps
                    
                    const updateCount = () => {
                        if (count < target) {
                            count += increment;
                            counter.innerText = Math.ceil(count);
                            requestAnimationFrame(updateCount);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    
                    updateCount();
                });
            }
        };
        
        // Bắt đầu đếm khi phần tử nhìn thấy được
        const statSection = document.querySelector('.stats-section');
        if (statSection) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(statSection);
        }
    });
</script> 