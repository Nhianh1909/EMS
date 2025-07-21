<<<<<<< Updated upstream
<?php 
echo 'hi mình là Bạn Liễu, đây là nhánh resgiter';
=======
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
    <title>Đăng ký (Sign Up)</title>
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
            <img src="https://placehold.co/500x500/E0F7FA/333333?text=Hinh+Minh+Hoa\n(signup-illustration.svg)" alt="Hình minh họa Đăng ký" class="auth-illustration">
        </div>

        <!-- Phần bên phải chứa form đăng ký -->
        <div class="auth-right-panel">
            <div class="auth-form-wrapper">
                <h2 class="auth-title">Sign Up</h2>
                
                <form class="auth-form register-form" action="#" method="POST">
                    <!-- Nhóm nhập liệu cho Tên -->
                    <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=👤" alt="User Icon" class="form-icon">
                        <input type="text" name="first_name" placeholder="Enter First Name" required>
                    </div>

                    <!-- Nhóm nhập liệu cho Họ -->
                    <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=👤" alt="User Icon" class="form-icon">
                        <input type="text" name="last_name" placeholder="Enter Last Name" required>
                    </div>
                    
                    <!-- Nhóm nhập liệu cho Tên người dùng -->
                    <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=👤" alt="Username Icon" class="form-icon">
                        <input type="text" name="username" placeholder="Enter Username" required>
                    </div>

                    <!-- Nhóm nhập liệu cho Email -->
                    <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=✉️" alt="Mail Icon" class="form-icon">
                        <input type="email" name="email" placeholder="Enter Email" required>
                    </div>

                    <!-- Nhóm nhập liệu cho Mật khẩu -->
                    <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=🔒" alt="Password Icon" class="form-icon">
                        <input type="password" name="password" placeholder="Enter Password" required>
                    </div>

                    <!-- Nhóm nhập liệu cho Xác nhận Mật khẩu -->
                    <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=🔒" alt="Confirm Password Icon" class="form-icon">
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>

                    <!-- Tùy chọn Đồng ý điều khoản -->
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">I agree to all terms</label>
                        </div>
                    </div>

                    <!-- Nút Đăng ký -->
                    <button type="submit" class="auth-button">Register</button>
                </form>

                <!-- Link chuyển sang trang Đăng nhập -->
                <p class="auth-switch-link">
                    Already have an account? <a href="login.php">Sign In</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
>>>>>>> Stashed changes
