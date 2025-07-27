<?php
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
    <title>Tài khoản - Quản lý chi tiêu</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div class="dashboard-container">
    <!-- ========== SIDEBAR ========== -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class='bx bxs-wallet-alt logo-icon'></i>
            <span class="logo-text">MyWallet</span>
        </div>
        <div class="sidebar-profile">
            <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Avatar" class="profile-avatar">
            <h4 class="profile-name"><?php echo htmlspecialchars($userName); ?></h4>
            <p class="profile-email"><?php echo htmlspecialchars($userEmail); ?></p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php"><i class='bx bxs-dashboard'></i><span>Bảng điều khiển</span></a></li>
                <li><a href="accounts.php" class="active"><i class='bx bxs-credit-card-alt'></i><span>Tài khoản</span></a></li>
                <li><a href="transactions.php"><i class='bx bx-transfer-alt'></i><span>Giao dịch</span></a></li>
                <li><a href="reports.php"><i class='bx bx-bar-chart-square'></i><span>Báo cáo</span></a></li>
                <li><a href="budgets.php"><i class='bx bx-pie-chart-alt-2'></i><span>Ngân sách</span></a></li>
                <li><a href="#"><i class='bx bxs-flag-checkered'></i><span>Mục tiêu Tiết kiệm</span></a></li>
                <li><a href="#"><i class='bx bx-receipt'></i><span>Hóa đơn định kỳ</span></a></li>
                <hr class="nav-divider">
                <li><a href="#"><i class='bx bxs-cog'></i><span>Cài đặt</span></a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="login.php" class="logout-button"><i class='bx bx-log-out'></i><span>Đăng xuất</span></a>
        </div>
    </aside>

    <!-- ========== MAIN CONTENT ========== -->
    <main class="main-content">
        <header class="main-header">
            <div class="header-left">
                <h1>Thông tin Tài khoản</h1>
                <p class="welcome-message">Cập nhật thông tin cá nhân và cài đặt bảo mật của bạn.</p>
            </div>
        </header>

        <section class="profile-settings-container animated-card">
            <div class="avatar-section">
                <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Avatar" class="large-avatar">
                <button class="btn btn-secondary">Thay đổi ảnh</button>
            </div>
            <div class="profile-form">
                <form action="#" method="POST">
                    <div class="form-group-modal">
                        <label for="full-name">Họ và Tên</label>
                        <input type="text" id="full-name" name="full_name" value="<?php echo htmlspecialchars($userName); ?>">
                    </div>
                    <div class="form-group-modal">
                        <label for="email">Địa chỉ Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly>
                    </div>
                    <hr class="form-divider">
                    <div class="form-group-modal">
                        <label for="new-password">Mật khẩu mới</label>
                        <input type="password" id="new-password" name="new_password" placeholder="••••••••">
                    </div>
                    <div class="form-group-modal">
                        <label for="confirm-password">Xác nhận mật khẩu mới</label>
                        <input type="password" id="confirm-password" name="confirm_password" placeholder="••••••••">
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

</body>
</html>