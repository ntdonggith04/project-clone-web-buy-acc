    <!-- Footer -->
    <footer class="footer" style="background: linear-gradient(135deg, #2b3553, #1d253b);">
        <div class="container pt-2">
            <div class="row g-5 justify-content-between">
                <div class="col-lg-5 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="text-white fw-bold mb-4 position-relative">Game Account Store
                        <span class="position-absolute" style="width: 50px; height: 3px; background: linear-gradient(to right, var(--primary-color), #4a5dd0); bottom: -10px; left: 0; border-radius: 5px;"></span>
                    </h4>
                    <p class="text-white-50">Chuyên cung cấp tài khoản game chính hãng, uy tín và chất lượng. Nơi giao dịch an toàn cho cộng đồng game thủ Việt Nam.</p>
                    <div class="social-icons mt-4">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-discord"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="text-white fw-bold mb-4 position-relative">Liên kết nhanh
                        <span class="position-absolute" style="width: 40px; height: 3px; background: linear-gradient(to right, var(--primary-color), #4a5dd0); bottom: -10px; left: 0; border-radius: 5px;"></span>
                    </h5>
                    <ul class="list-unstyled footer-links">
                        <li class="footer-link-item"><a href="<?= BASE_URL ?>" class="footer-link"><i class="fas fa-chevron-right me-2 small"></i>Trang chủ</a></li>
                        <li class="footer-link-item"><a href="<?= BASE_URL ?>games" class="footer-link"><i class="fas fa-chevron-right me-2 small"></i>Danh sách game</a></li>
                        <li class="footer-link-item"><a href="<?= BASE_URL ?>accounts" class="footer-link"><i class="fas fa-chevron-right me-2 small"></i>Tài khoản game</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="text-white fw-bold mb-4 position-relative">Liên hệ
                        <span class="position-absolute" style="width: 40px; height: 3px; background: linear-gradient(to right, var(--primary-color), #4a5dd0); bottom: -10px; left: 0; border-radius: 5px;"></span>
                    </h5>
                    <ul class="list-unstyled contact-info">
                        <li class="d-flex mb-3 contact-info-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh</div>
                        </li>
                        <li class="d-flex mb-3 contact-info-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-text">+84 123 456 789</div>
                        </li>
                        <li class="d-flex mb-3 contact-info-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <a href="mailto:info@gameaccountstore.com" class="footer-link">info@gameaccountstore.com</a>
                            </div>
                        </li>
                        <li class="d-flex contact-info-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-text">Hỗ trợ 24/7</div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 opacity-25">
            
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-5 text-center text-lg-start mb-3 mb-lg-0">
                    <p class="mb-0 text-white-50">&copy; <?= date('Y') ?> Game Account Store. All rights reserved.</p>
                </div>
                <div class="col-lg-5 text-center text-lg-end">
                    <div class="payment-methods">
                        <span class="payment-icon"><i class="fab fa-cc-visa"></i></span>
                        <span class="payment-icon"><i class="fab fa-cc-mastercard"></i></span>
                        <span class="payment-icon"><i class="fab fa-cc-paypal"></i></span>
                        <span class="payment-icon"><i class="fab fa-bitcoin"></i></span>
                        <span class="payment-icon"><i class="fab fa-cc-amazon-pay"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Modern Footer Styles */
        .footer {
            color: #fff;
            position: relative;
            padding: 4rem 0 2rem;
        }
        
        .footer .row {
            row-gap: 3rem;
        }
        
        .footer .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiB2aWV3Qm94PSIwIDAgMTI4MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iIzFkMjUzYiI+PHBhdGggZD0iTTEyODAgMy40QzEwNTAuNTkgMTguMTEgMjE5LjQgODQuODkgMTY5LjQ4IDg0Ljg5Yy0yMC4zMSAwLTMxLjk5LTQuMDctNDMuMTQtOC4yN0MxMTUuMzMgNzIuNzkgMTA0LjM2IDY0IDg3LjE5IDU4LjQ1QzczLjI4IDUzLjkgNTMuODggNTMuMjcgMjEuOTggNTMuMjdMMCAwdjE0MGgxMjgweiIvPjwvZz48L3N2Zz4=');
            background-size: 100% 100px;
            background-position: top;
            background-repeat: no-repeat;
            z-index: -1;
            opacity: 0.5;
        }
        
        @media (min-width: 992px) {
            .footer .col-lg-5:first-child {
                padding-right: 2.5rem;
            }
            
            .footer .col-lg-3 {
                padding-left: 2rem;
                padding-right: 2rem;
            }
            
            .footer .col-lg-4 {
                padding-left: 2.5rem;
            }
        }
        
        .footer h4, .footer h5, .footer h6 {
            color: white;
        }
        
        /* Social Icons */
        .social-icons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-start;
            margin-top: 1.5rem;
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-5px);
        }
        
        /* Footer Links */
        .footer-link-item {
            margin-bottom: 15px;
        }
        
        .footer-link {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            font-size: 1rem;
            padding: 4px 0;
        }
        
        .footer-link:hover {
            color: white;
            transform: translateX(5px);
        }
        
        /* Contact Info */
        .contact-info {
            margin-top: 1.5rem;
        }
        
        .contact-info-item {
            margin-bottom: 18px;
        }
        
        .contact-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--primary-color);
            border-radius: 50%;
            margin-right: 15px;
            transition: all 0.3s ease;
        }
        
        .contact-info-item:hover .contact-icon {
            background-color: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }
        
        .contact-text {
            color: rgba(255, 255, 255, 0.6);
            align-self: center;
        }
        
        /* Payment Methods */
        .payment-methods {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .payment-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 8px;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .payment-icon:hover {
            background-color: var(--primary-color);
            transform: translateY(-5px);
        }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), #4a5dd0);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.5s ease;
        }
        
        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: linear-gradient(135deg, #4a5dd0, var(--primary-color));
            transform: translateY(-5px);
            color: white;
        }
    </style>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo AOS
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true,
                    mirror: false
                });
                
                // Làm mới AOS khi tất cả hình ảnh và resources được tải xong
                window.addEventListener('load', function() {
                    AOS.refresh();
                });
            }
            
            // Back to top button
            const backToTopButton = document.querySelector('.back-to-top');
            
            function toggleBackToTopButton() {
                if (window.scrollY > 300) {
                    backToTopButton.classList.add('active');
                } else {
                    backToTopButton.classList.remove('active');
                }
            }
            
            window.addEventListener('scroll', toggleBackToTopButton);
            
            backToTopButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Xử lý smooth scrolling cho các anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    if (this.getAttribute('href') !== '#') {
                        const targetId = this.getAttribute('href');
                        const targetElement = document.querySelector(targetId);
                        
                        if (targetElement) {
                            e.preventDefault();
                            const headerOffset = 70;
                            const elementPosition = targetElement.getBoundingClientRect().top;
                            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                            
                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html> 