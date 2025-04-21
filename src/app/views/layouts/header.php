<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Game Account Store - Mua bán tài khoản game an toàn, uy tín' ?></title>
    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_URL ?>favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom styles -->
    <style>
        :root {
            --primary-color: #5e72e4;
            --secondary-color: #f5365c;
            --success-color: #2dce89;
            --info-color: #11cdef;
            --warning-color: #fb6340;
            --danger-color: #f5365c;
            --light-color: #f8f9fe;
            --dark-color: #172b4d;
            --text-color: #525f7f;
            --text-muted: #8898aa;
            --navbar-height: 56px;
        }

        /* Base styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            color: var(--text-color);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
            scroll-behavior: smooth;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        
        /* Đảm bảo không có khoảng cách đầu trang */
        body, html {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        /* Các mục không phải trang chủ cần được đẩy xuống */
        body:not(.home-page) > section:first-of-type,
        body:not(.home-page) > div:first-of-type,
        body:not(.home-page) > main:first-of-type {
            padding-top: calc(var(--navbar-height) + 20px);
        }
        
        /* Custom scrollbar Styles */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* For Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 #f1f1f1;
        }
        
        /* Global content container */
        .content {
            min-height: calc(100vh - 250px);
            padding-bottom: 3rem;
            position: relative;
            padding-top: var(--navbar-height);
        }
        
        /* Card styling */
        .card {
            border: 0;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        /* Button styling */
        .btn {
            border-radius: 30px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #4a5dd0;
            border-color: #4a5dd0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(94, 114, 228, 0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(94, 114, 228, 0.3);
        }
        
        /* Headings */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: var(--dark-color);
        }
        
        /* Section styling */
        section {
            padding: 5rem 0;
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 70px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 10px;
            left: 0;
            bottom: -10px;
        }
        
        .section-title.text-center:after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        /* Animation classes */
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }
        
        .slide-up {
            animation: slideUp 0.8s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Links */
        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        a:hover {
            color: #4a5dd0;
            text-decoration: none;
        }
        
        /* Form styling */
        .form-control {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
        }
        
        /* Responsive typography */
        @media (max-width: 768px) {
            h1, .h1 { font-size: calc(1.675rem + 1.5vw); }
            h2, .h2 { font-size: calc(1.325rem + 0.9vw); }
            h3, .h3 { font-size: calc(1.25rem + 0.6vw); }
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/navbar.php'; ?>

</body>
</html> 