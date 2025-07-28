<?php
include 'config/config.php';
//đã có session từ dasboard.php
// Lấy thông tin người dùng 
$sql_info = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql_info);
$stmt->execute(['id' => $_SESSION['user_id']]);
$user_info = $stmt->fetch(PDO::FETCH_ASSOC);

if (!empty($user_info['avatar'])) {
    // Nếu DB có avatar thì hiển thị
    $userAvatar = '/assets/avatars/' . $user_info['avatar'];
} else {
    // Nếu chưa có avatar thì dùng ảnh mặc định
    $userAvatar = '/assets/default-avatar.png';
}

?>

<!-- ========== SIDEBAR (ĐÃ CẬP NHẬT) ========== -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class='bx bxs-wallet-alt logo-icon'></i>
            <span class="logo-text">MyWallet</span>
        </div>

        <div class="sidebar-profile">
            <img src="<?php echo $userAvatar;?>" alt="Avatar" class="profile-avatar">
            <h4 class="profile-name"><?php echo htmlspecialchars($user_info['username']); ?></h4>
            <p class="profile-email"><?php echo htmlspecialchars($user_info['email']); ?></p>
        </div>

        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php" class="active"><i class='bx bxs-dashboard'></i><span>Bảng điều khiển</span></a></li>
                <li><a href="accounts.php"><i class='bx bxs-credit-card-alt'></i><span>Tài khoản</span></a></li>
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
            <a href="logout.php" class="logout-button text-decoration-none">
                <i class='bx bx-log-out'></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>