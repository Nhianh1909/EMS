<?php
// --- MÔ PHỎNG DỮ LIỆU ĐỘNG ---
$userName = trim(" Khánh");
$userEmail = "duykhanh@gmail.com";

// Dữ liệu báo cáo mẫu
$report_summary = [
    'total_income' => 15000000,
    'total_expense' => 6800000,
    'net_income' => 8200000
];

// Dữ liệu cho biểu đồ cột (Thu nhập vs Chi tiêu)
$income_expense_data = [
    ['month' => 'Tháng 5', 'income' => 15000000, 'expense' => 5500000],
    ['month' => 'Tháng 6', 'income' => 17000000, 'expense' => 7200000],
    ['month' => 'Tháng 7', 'income' => 15000000, 'expense' => 6800000],
];

// Dữ liệu cho biểu đồ tròn (Phân loại chi tiêu)
$expense_by_category = [
    ['category' => 'Hóa đơn', 'amount' => 4000000, 'percentage' => 58.8, 'color' => '#3b82f6'],
    ['category' => 'Ăn uống', 'amount' => 1500000, 'percentage' => 22.1, 'color' => '#ef4444'],
    ['category' => 'Mua sắm', 'amount' => 800000, 'percentage' => 11.8, 'color' => '#f97316'],
    ['category' => 'Khác', 'amount' => 500000, 'percentage' => 7.3, 'color' => '#a855f7'],
];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo - Quản lý chi tiêu</title>
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
            <img src="https://scontent.fsgn21-1.fna.fbcdn.net/v/t39.30808-6/474189628_1310277973503688_3036333816967852750_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_ohc=hMqcP2MP3hMQ7kNvwEysxR9&_nc_oc=AdmRy2aZYjM-Q9iwxXPO7EnyU9y8I4N4-r31oBvb1bv3Yc0YYB1M-VXyn56PtGCFM2AaUSbYKZBD2yLKF6QUMmWa&_nc_zt=23&_nc_ht=scontent.fsgn21-1.fna&_nc_gid=2VfzLP0HwXXmY6gmfFxWqw&oh=00_AfRS2AS7qmPGz9dSe3jvA1Tx5pWDZOF9KwbBA6Q7rky9vg&oe=687D3C9E" alt="Avatar" class="profile-avatar">
            <h4 class="profile-name"><?php echo htmlspecialchars($userName); ?></h4>
            <p class="profile-email"><?php echo htmlspecialchars($userEmail); ?></p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php"><i class='bx bxs-dashboard'></i><span>Bảng điều khiển</span></a></li>
                <li><a href="accounts.php"><i class='bx bxs-credit-card-alt'></i><span>Tài khoản</span></a></li>
                <li><a href="transactions.php"><i class='bx bx-transfer-alt'></i><span>Giao dịch</span></a></li>
                <li><a href="reports.php" class="active"><i class='bx bx-bar-chart-square'></i><span>Báo cáo</span></a></li>
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
                <h1>Báo cáo Tài chính</h1>
                <p class="welcome-message">Phân tích dòng tiền và thói quen chi tiêu của bạn.</p>
            </div>
             <div class="header-right">
                <div class="filter-group">
                    <select id="report-period" name="report_period">
                        <option>Tháng này</option>
                        <option>Tháng trước</option>
                        <option>Năm nay</option>
                    </select>
                </div>
            </div>
        </header>

        <section class="overview-cards">
            <div class="card">
                <div class="card-icon" style="background-color: #e8f5e9;"><i class='bx bx-trending-up' style="color: #43a047;"></i></div>
                <div class="card-info">
                    <p>Tổng thu nhập</p>
                    <h3>+<?php echo number_format($report_summary['total_income'], 0, ',', '.'); ?>đ</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-icon" style="background-color: #ffebee;"><i class='bx bx-trending-down' style="color: #e53935;"></i></div>
                <div class="card-info">
                    <p>Tổng chi tiêu</p>
                    <h3>-<?php echo number_format($report_summary['total_expense'], 0, ',', '.'); ?>đ</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-icon" style="background-color: #e0f7fa;"><i class='bx bxs-wallet' style="color: #00acc1;"></i></div>
                <div class="card-info">
                    <p>Dòng tiền ròng</p>
                    <h3 style="color: <?php echo $report_summary['net_income'] >= 0 ? '#22c55e' : '#ef4444'; ?>;">
                        <?php echo number_format($report_summary['net_income'], 0, ',', '.'); ?>đ
                    </h3>
                </div>
            </div>
        </section>
        
        <section class="reports-grid">
            <div class="report-card">
                <h3 class="report-card-title">Thu nhập vs. Chi tiêu</h3>
                <div class="bar-chart-placeholder">
                    <!-- Mô phỏng biểu đồ cột bằng CSS -->
                    <?php foreach($income_expense_data as $data): ?>
                    <div class="bar-group">
                        <div class="bar-wrapper">
                            <div class="bar income-bar" style="height: <?php echo ($data['income']/20000000)*100; ?>%;"></div>
                            <div class="bar expense-bar" style="height: <?php echo ($data['expense']/20000000)*100; ?>%;"></div>
                        </div>
                        <span class="bar-label"><?php echo $data['month']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="report-card">
                <h3 class="report-card-title">Phân loại chi tiêu</h3>
                <div class="pie-chart-container">
                    <div class="pie-chart-placeholder" style="background: conic-gradient(#3b82f6 0% 58.8%, #ef4444 58.8% 80.9%, #f97316 80.9% 92.7%, #a855f7 92.7% 100%);"></div>
                    <ul class="chart-legend">
                        <?php foreach ($expense_by_category as $item): ?>
                        <li>
                            <span class="legend-color" style="background-color: <?php echo $item['color']; ?>;"></span>
                            <span class="legend-text"><?php echo htmlspecialchars($item['category']); ?></span>
                            <span class="legend-percentage"><?php echo $item['percentage']; ?>%</span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

    </main>
</div>

</body>
</html>
