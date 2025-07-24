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
    <!-- Link tới file CSS chung -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Link tới Google Fonts để có font đẹp hơn -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <!-- Phần bên trái chứa ảnh minh họa -->
        <div class="auth-left-panel">
            <img src="https://placehold.co/500x500/E0F7FA/333333?text=Hinh+Minh+Hoa\n(login-illustration.svg)" alt="Hình minh họa Đăng nhập" class="auth-illustration">
        </div>

        <!-- Phần bên phải chứa form đăng nhập -->
        <div class="auth-right-panel">
            <div class="auth-form-wrapper">
                <h2 class="auth-title">Sign In</h2>
                <p class="auth-subtitle">Chào mừng trở lại! Vui lòng nhập thông tin của bạn.</p>
                
                <form class="auth-form" action="#" method="POST">
                    <!-- Nhóm nhập liệu cho Email -->
                    <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=✉️" alt="Mail Icon" class="form-icon">
                        <input type="email" name="email" placeholder="Nhập Email của bạn" required>
                    </div>

                    <!-- Nhóm nhập liệu cho Mật khẩu -->
                    <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=🔒" alt="Password Icon" class="form-icon">
                        <input type="password" name="password" placeholder="Nhập Mật khẩu" required>
                    </div>

                    <!-- Tùy chọn Ghi nhớ đăng nhập và Quên mật khẩu -->
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember">
                            <label for="remember">Ghi nhớ tôi</label>
                        </div>
                        <a href="#" class="forgot-password">Quên mật khẩu?</a>
                    </div>

                    <!-- Nút Đăng nhập -->
                    <button type="submit" class="auth-button">Sign In</button>
                </form>

                <!-- Link chuyển sang trang Đăng ký (ĐÃ CẬP NHẬT) -->
                <p class="auth-switch-link">
                    Chưa có tài khoản? <a href="register.php">Tạo tài khoản ngay</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
