<?php
session_start();
include('config/config.php');
// --- MÔ PHỎNG DỮ LIỆU ĐỘNG ---
$userName = trim(" Khánh");
$userEmail = "duykhanh@gmail.com";
$userAvatar = "https://scontent.fsgn21-1.fna.fbcdn.net/v/t39.30808-6/474189628_1310277973503688_3036333816967852750_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_ohc=hMqcP2MP3hMQ7kNvwEysxR9&_nc_oc=AdmRy2aZYjM-Q9iwxXPO7EnyU9y8I4N4-r31oBvb1bv3Yc0YYB1M-VXyn56PtGCFM2AaUSbYKZBD2yLKF6QUMmWa&_nc_zt=23&_nc_ht=scontent.fsgn21-1.fna&_nc_gid=2VfzLP0HwXXmY6gmfFxWqw&oh=00_AfRS2AS7qmPGz9dSe3jvA1Tx5pWDZOF9KwbBA6Q7rky9vg&oe=687D3C9E";

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt - Quản lý chi tiêu</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .settings-grid .report-card.animated-card .setting-item a {
            text-decoration: none !important;
        }
    </style>
</head>

<body>

<div class="dashboard-container">
    <!-- ========== SIDEBAR ========== -->
    <?php include('sidebar/sidebar.php') ?>
    <!-- ========== MAIN CONTENT ========== -->
    <main class="main-content">
        <header class="main-header">
            <div class="header-left">
                <h1>Cài đặt</h1>
                <p class="welcome-message">Tùy chỉnh ứng dụng theo sở thích của bạn.</p>
            </div>
        </header>

        <section class="settings-grid">
            <div class="report-card animated-card">
                <h3 class="report-card-title">Hồ sơ</h3>
                <div class="setting-item">
                    <p>Quản lý thông tin cá nhân, ảnh đại diện và mật khẩu của bạn.</p>
                    <a href="accounts.php" class="btn btn-secondary ">Đi đến hồ sơ</a>
                </div>
            </div>
             <div class="report-card animated-card" style="animation-delay: 0.1s;">
                <h3 class="report-card-title">Hiển thị</h3>
                <div class="setting-item">
                    <p>Chế độ hiển thị</p>
                    <div class="toggle-switch">
                        <i class='bx bx-sun'></i>
                        <label class="switch">
                            <input type="checkbox" id="theme-toggle">
                       <span class="slider round"></span>
                        </label>
                        <i class='bx bxs-moon'></i>
                    </div>
                </div>
            </div>
             <div class="report-card animated-card" style="animation-delay: 0.2s;">
                <h3 class="report-card-title">Tiền tệ</h3>
                <div class="setting-item">
                    <p>Chọn đơn vị tiền tệ mặc định</p>
                    <div class="filter-group">
                        <select>
                            <option>VND - Việt Nam Đồng</option>
                            <option>USD - Đô la Mỹ</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;

    // Hàm để áp dụng theme
    const applyTheme = (theme) => {
        if (theme === 'dark') {
            body.classList.add('dark-mode');
            themeToggle.checked = true;
        } else {
            body.classList.remove('dark-mode');
            themeToggle.checked = false;
        }
    };

    // Hàm để chuyển đổi theme
    const toggleTheme = () => {
        if (themeToggle.checked) {
            localStorage.setItem('theme', 'dark');
            applyTheme('dark');
        } else {
            localStorage.setItem('theme', 'light');
            applyTheme('light');
        }
    };

    // Gán sự kiện cho nút gạt
    themeToggle.addEventListener('change', toggleTheme);

    // Kiểm tra và áp dụng theme đã lưu khi tải trang
    //lưu vào localStorage
    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('theme') || 'light';
        applyTheme(savedTheme);
    });
</script>

</body>
</html>
