<?php
include 'config/config.php';
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
// Lấy thông tin người dùng 
$sql_info = "SELECT * FROM users WHERE email = :email";
$stmt = $conn->prepare($sql_info);
$stmt->execute(['email' => $_SESSION['email']]);
$user_info = $stmt->fetch(PDO::FETCH_ASSOC);



// Dữ liệu tổng quan
$balance = 25680000;
$income = 15000000;
$expense = 4320000;

// Dữ liệu giao dịch gần đây
$transactions = [
    ['type' => 'expense', 'category' => 'Ăn uống', 'description' => 'Bữa tối tại nhà hàng Pizza 4P\'s', 'amount' => -850000, 'icon' => 'bx-restaurant'],
    ['type' => 'expense', 'category' => 'Mua sắm', 'description' => 'Mua áo thun tại Uniqlo', 'amount' => -499000, 'icon' => 'bx-shopping-bag'],
    ['type' => 'income', 'category' => 'Lương', 'description' => 'Lương tháng 8', 'amount' => 15000000, 'icon' => 'bx-money-withdraw'],
    ['type' => 'expense', 'category' => 'Di chuyển', 'description' => 'Tiền xăng xe tháng 8', 'amount' => -500000, 'icon' => 'bx-gas-pump'],
];

// Dữ liệu phân tích chi tiêu
$spending_analysis = [
    ['category' => 'Ăn uống', 'percentage' => 65, 'color' => '#ff6384'],
    ['category' => 'Mua sắm', 'percentage' => 25, 'color' => '#36a2eb'],
    ['category' => 'Khác', 'percentage' => 10, 'color' => '#ffce56'],
];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển - Quản lý chi tiêu</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div class="dashboard-container">
    <!-- ========== SIDEBAR (ĐÃ CẬP NHẬT) ========== -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class='bx bxs-wallet-alt logo-icon'></i>
            <span class="logo-text">MyWallet</span>
        </div>

        <div class="sidebar-profile">
            <img src="https://i.pravatar.cc/100" alt="Avatar" class="profile-avatar">
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

    <!-- ========== MAIN CONTENT (Nội dung giữ nguyên) ========== -->
    <main class="main-content">
        <!-- -------- Header -------- -->
        <header class="main-header">
            <div class="header-left">
                <h1>Bảng điều khiển</h1>
                <p class="welcome-message">Chào mừng trở lại, <?php echo htmlspecialchars($user_info['username']); ?>! 👋</p>
            </div>
            <div class="header-right">
                <div class="search-bar">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Tìm kiếm giao dịch...">
                </div>
                <button class="action-button"><i class='bx bx-bell'></i></button>
                <button class="action-button primary-btn"><i class='bx bx-plus'></i> <span>Thêm giao dịch</span></button>
            </div>
        </header>

        <!-- -------- Overview Cards -------- -->
        <section class="overview-cards">
            <div class="card animated-card magnetic-effect">
                <div class="card-icon" style="background-color: #e0f7fa;">
                    <i class='bx bxs-wallet' style="color: #00acc1;"></i>
                </div>
                <div class="card-info">
                    <p>Tổng số dư</p>
                    <h3 class="count-up" data-value="<?php echo $balance; ?>">0đ</h3>
                </div>
            </div>
            <div class="card animated-card magnetic-effect" style="animation-delay: 0.1s;">
                <div class="card-icon" style="background-color: #e8f5e9;">
                    <i class='bx bx-trending-up' style="color: #43a047;"></i>
                </div>
                <div class="card-info">
                    <p>Thu nhập tháng</p>
                    <h3 class="count-up" data-value="<?php echo $income; ?>">+0đ</h3>
                </div>
            </div>
            <div class="card animated-card magnetic-effect" style="animation-delay: 0.2s;">
                <div class="card-icon" style="background-color: #ffebee;">
                    <i class='bx bx-trending-down' style="color: #e53935;"></i>
                </div>
                <div class="card-info">
                    <p>Chi tiêu tháng</p>
                    <h3 class="count-up" data-value="<?php echo $expense; ?>">-0đ</h3>
                </div>
            </div>
        </section>

        <!-- -------- Main Section (Transactions & Analysis) -------- -->
        <section class="main-section">
            <!-- Left Column: Recent Transactions -->
            <div class="transactions-container animated-card magnetic-effect" style="animation-delay: 0.3s;">
                <div class="section-header">
                    <h2>Giao dịch gần đây</h2>
                    <a href="transactions.php" class="view-all">Xem tất cả</a>
                </div>
                <ul class="transaction-list">
                    <?php foreach ($transactions as $index => $t): ?>
                    <li class="transaction-item animated-li" style="animation-delay: <?php echo 0.4 + $index * 0.1; ?>s;">
                        <div class="transaction-icon" style="background-color: <?php echo $t['type'] === 'income' ? '#e8f5e9' : '#fff3e0'; ?>;">
                            <i class='<?php echo $t['icon']; ?>' style="color: <?php echo $t['type'] === 'income' ? '#43a047' : '#fb8c00'; ?>;"></i>
                        </div>
                        <div class="transaction-details">
                            <h4><?php echo htmlspecialchars($t['description']); ?></h4>
                            <p><?php echo htmlspecialchars($t['category']); ?></p>
                        </div>
                        <div class="transaction-amount <?php echo $t['type']; ?>">
                            <?php echo number_format($t['amount'], 0, ',', '.'); ?>đ
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Right Column: Spending Analysis -->
            <div class="analysis-container animated-card magnetic-effect" style="animation-delay: 0.4s;">
                <div class="section-header">
                    <h2>Phân tích chi tiêu</h2>
                </div>
                <div class="chart-container">
                    <div class="donut-chart-placeholder">
                        <div class="chart-center-text">
                            <span>Tổng chi</span>
                            <h4 class="count-up" data-value="<?php echo $expense; ?>">0đ</h4>
                        </div>
                    </div>
                </div>
                <ul class="chart-legend">
                    <?php foreach ($spending_analysis as $item): ?>
                    <li>
                        <span class="legend-color" style="background-color: <?php echo $item['color']; ?>;"></span>
                        <span class="legend-text"><?php echo htmlspecialchars($item['category']); ?></span>
                        <span class="legend-percentage"><?php echo $item['percentage']; ?>%</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- HIỆU ỨNG ĐẾM SỐ ---
    const countUpElements = document.querySelectorAll('.count-up');
    const animateCountUp = (el) => {
        const finalValue = parseInt(el.dataset.value, 10);
        const duration = 1500;
        let startTime = null;

        const step = (timestamp) => {
            if (!startTime) startTime = timestamp;
            const progress = Math.min((timestamp - startTime) / duration, 1);
            const currentValue = Math.floor(progress * finalValue);
            const prefix = el.textContent.startsWith('+') ? '+' : el.textContent.startsWith('-') ? '-' : '';
            el.textContent = prefix + currentValue.toLocaleString('vi-VN') + 'đ';

            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                 el.textContent = prefix + finalValue.toLocaleString('vi-VN') + 'đ';
            }
        };
        window.requestAnimationFrame(step);
    };
    countUpElements.forEach(animateCountUp);

    // --- HIỆU ỨNG NAM CHÂM KHI DI CHUỘT ---
    const magneticElements = document.querySelectorAll('.magnetic-effect');
    magneticElements.forEach(el => {
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            // Giảm cường độ di chuyển để hiệu ứng tinh tế hơn
            const moveX = x * 0.1;
            const moveY = y * 0.1;

            el.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });

        el.addEventListener('mouseleave', () => {
            el.style.transform = 'translate(0, 0)';
        });
    });
});
</script>

</body>
</html>
