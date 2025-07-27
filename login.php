<?php
// BẬT HIỂN THỊ LỖI PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS riêng -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
        }
        .auth-container {
            display: flex;
            height: 100vh;
        }
        .auth-left-panel {
            background-color: #f8f9fa;
        }
        .auth-left-panel img {
            max-width: 100%;
            max-height: 80%;
            object-fit: contain;
            padding: 20px;
        }
        .auth-form-wrapper {
            max-width: 400px;
            width: 100%;
            padding: 30px;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <!-- Panel trái -->
    <div class="auth-left-panel d-none d-md-flex col-md-6 justify-content-center align-items-center">
        <img src="./assets/login_avatar.jpg" alt="Login Illustration">
    </div>

    <!-- Panel phải -->
    <div class="auth-right-panel col-12 col-md-6 d-flex justify-content-center align-items-center">
        <div class="auth-form-wrapper">
            <h2 class="auth-title">Sign In</h2>
            <p class="auth-subtitle">Chào mừng trở lại! Vui lòng nhập thông tin của bạn.</p>

            <form class="auth-form" action="login_check.php" method="POST" autocomplete="on">
                <!-- Email -->
                <div class="form-group d-flex align-items-center mb-3">
                    <img src="https://placehold.co/24x24/FFFFFF/999999?text=✉️" alt="Email Icon" class="me-2">
                    <input type="email" name="email" class="form-control" placeholder="Nhập Email của bạn" required>
                </div>

                <!-- Mật khẩu -->
                <div class="form-group d-flex align-items-center mb-3">
                    <img src="https://placehold.co/24x24/FFFFFF/999999?text=🔒" alt="Password Icon" class="me-2">
                    <input type="password" name="password" class="form-control" placeholder="Nhập Mật khẩu" required>
                </div>

                <!-- Ghi nhớ -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Ghi nhớ tôi</label>
                    </div>
                    <a href="#" class="text-decoration-none">Quên mật khẩu?</a>
                </div>

                <!-- Nút đăng nhập -->
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>

            <p class="text-center mt-3">
                Chưa có tài khoản? <a href="register.php">Tạo tài khoản ngay</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
