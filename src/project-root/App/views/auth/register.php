<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - GameAccount</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/project-clone-web-buy-acc/src/project-root/public/css/style.css">
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

        main {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
            margin: 0 auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .register-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 600;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
        }

        .register-header p {
            color: var(--text-light);
            font-size: 1.2em;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.95em;
            letter-spacing: 0.3px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 25px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95em;
            transition: all 0.3s ease;
            outline: none;
            background: #f8f9fa;
            letter-spacing: 0.3px;
            height: 45px;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: #fff;
        }

        .btn-register {
            width: 100%;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            padding: 12px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
            height: 45px;
            display: block;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .login-link p {
            color: var(--text-light);
            font-size: 0.95em;
            letter-spacing: 0.3px;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            letter-spacing: 0.3px;
        }

        .login-link a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }

        .login-link a:hover {
            color: var(--secondary-color);
        }

        .login-link a:hover::after {
            width: 100%;
        }

        .error-message {
            background: #fee;
            color: var(--error-color);
            padding: 10px;
            border-radius: 8px;
            font-size: 0.9em;
            margin-bottom: 20px;
            border: 1px solid #fdd;
            position: relative;
            padding-left: 40px;
            letter-spacing: 0.3px;
        }

        .error-message::before {
            content: '!';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: var(--error-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .success-message {
            background: #efe;
            color: var(--success-color);
            padding: 10px;
            border-radius: 8px;
            font-size: 0.9em;
            margin-bottom: 20px;
            border: 1px solid #dfd;
            position: relative;
            padding-left: 40px;
            letter-spacing: 0.3px;
        }

        .success-message::before {
            content: '✓';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: var(--success-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 25px 20px;
            }

            .register-header h1 {
                font-size: 1.8em;
            }
        }

        @media (max-width: 768px) {
            .register-container {
                padding: 30px 25px;
            }

            .register-header h1 {
                font-size: 2.2em;
            }

            .form-group input {
                padding: 8px 20px;
                height: 40px;
            }

            .btn-register {
                height: 40px;
                padding: 10px;
            }
        }

        @media (max-width: 1024px) {
            .register-container {
                max-width: 450px;
            }
        }

        @media (min-width: 1440px) {
            .register-container {
                max-width: 550px;
                padding: 40px;
            }

            .register-header h1 {
                font-size: 2.8em;
            }

            .register-header p {
                font-size: 1.3em;
            }

            .form-group input {
                padding: 12px 30px;
                height: 50px;
                font-size: 1.1em;
            }

            .btn-register {
                height: 50px;
                padding: 15px;
                font-size: 1.2em;
            }

            .login-link p {
                font-size: 1.1em;
            }
        }
    </style>
</head>
<body>
    <main>
        <div class="register-container">
            <div class="register-header">
                <h1>Đăng ký</h1>
                <p>Tạo tài khoản mới để bắt đầu</p>
            </div>
            
            <?php if (isset($errors['general'])): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($errors['general']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/project-clone-web-buy-acc/src/project-root/public/register">
                <div class="form-group">
                    <label for="username">Tên người dùng</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Nhập tên người dùng của bạn"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <?php if (isset($errors['username'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['username']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Nhập địa chỉ email của bạn"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['email']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Nhập mật khẩu của bạn">
                    <?php if (isset($errors['password'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['password']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           placeholder="Nhập lại mật khẩu của bạn">
                    <?php if (isset($errors['confirm_password'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['confirm_password']); ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-register">Đăng ký</button>
            </form>

            <div class="login-link">
                <p>Đã có tài khoản? <a href="/project-clone-web-buy-acc/src/project-root/public/login">Đăng nhập ngay</a></p>
            </div>
        </div>
    </main>
</body>
</html> 