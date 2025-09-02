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
        <div class="auth-left-panel d-none d-md-flex col-md-6 justify-content-center align-items-center">
            <img src="./assets/login_avatar.jpg" alt="Login Illustration" style="max-width:80%; max-height:400px; height:auto; width:auto; object-fit:contain; border-radius:12px;">

        </div>

        <!-- Phần bên phải chứa form đăng ký -->
        <div class="auth-right-panel">
            <div class="auth-form-wrapper">
                <h2 class="auth-title">Sign Up</h2>
                
                <form class="auth-form register-form" action="register_check.php" method="POST" autocomplete="on">
                    <!-- Nhóm nhập liệu cho Tên -->
                    <!-- <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=👤" alt="User Icon" class="form-icon">
                        <input type="text" name="first_name" placeholder="Enter First Name" required>
                    </div> -->

                    <!-- Nhóm nhập liệu cho Họ -->
                    <!-- <div class="form-group">
                        <img src="https://placehold.co/24x24/FFFFFF/999999?text=👤" alt="User Icon" class="form-icon">
                        <input type="text" name="last_name" placeholder="Enter Last Name" required>
                    </div> -->
                    
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
                            <input type="password" id="password" name="password" placeholder="Enter Password" required>
                        </div>

                        <!-- Nhóm nhập liệu cho Xác nhận Mật khẩu -->
                        <div class="form-group">
                            <img src="https://placehold.co/24x24/FFFFFF/999999?text=🔒" alt="Confirm Password Icon" class="form-icon">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
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
<script>
document.querySelector(".register-form").addEventListener("submit", function(e) {
    const password = document.getElementById("password").value;
    const confirm  = document.getElementById("confirm_password").value;

    if (password !== confirm) {
        e.preventDefault(); // chặn gửi form
        alert("❌ Password và Confirm Password không khớp!");
    }
});
</script>
