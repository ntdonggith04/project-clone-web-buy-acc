<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - GameAccount</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/public/css/style.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --text-color: #333;
            --text-light: #666;
            --border-color: #e1e1e1;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
        }

        .reset-password-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .reset-password-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: var(--text-light);
            font-size: 1em;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-light);
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95em;
            display: flex;
            align-items: center;
        }

        .message i {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .success-message {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .password-strength {
            margin-top: 5px;
            font-size: 0.85em;
            color: var(--text-light);
        }

        .password-strength span {
            margin-right: 10px;
        }

        .strength-weak { color: var(--error-color); }
        .strength-medium { color: #f39c12; }
        .strength-strong { color: var(--success-color); }

        .form-group .input-wrapper {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="header">
            <h1>Đặt lại mật khẩu</h1>
            <p>Vui lòng nhập mật khẩu mới của bạn</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo BASE_PATH; ?>/reset-password" id="resetPasswordForm">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        minlength="6"
                        placeholder="Nhập mật khẩu mới"
                    >
                    <i class="fas fa-eye-slash password-toggle" onclick="togglePassword('password')"></i>
                </div>
                <div class="password-strength" id="passwordStrength"></div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        required 
                        minlength="6"
                        placeholder="Nhập lại mật khẩu mới"
                    >
                    <i class="fas fa-eye-slash password-toggle" onclick="togglePassword('confirm_password')"></i>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-key"></i> Đặt lại mật khẩu
            </button>
        </form>
    </div>

    <script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling;
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    document.getElementById('password').addEventListener('input', function(e) {
        const password = e.target.value;
        const strengthDiv = document.getElementById('passwordStrength');
        let strength = 0;
        let message = '';

        // Kiểm tra độ dài
        if (password.length >= 8) strength++;
        // Kiểm tra chữ hoa và chữ thường
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        // Kiểm tra số
        if (password.match(/\d/)) strength++;
        // Kiểm tra ký tự đặc biệt
        if (password.match(/[^a-zA-Z\d]/)) strength++;

        switch(strength) {
            case 0:
            case 1:
                message = '<span class="strength-weak"><i class="fas fa-circle"></i> Yếu</span>';
                break;
            case 2:
            case 3:
                message = '<span class="strength-medium"><i class="fas fa-circle"></i> Trung bình</span>';
                break;
            case 4:
                message = '<span class="strength-strong"><i class="fas fa-circle"></i> Mạnh</span>';
                break;
        }

        strengthDiv.innerHTML = message;
    });

    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Mật khẩu xác nhận không khớp!');
        }
    });
    </script>
</body>
</html> 