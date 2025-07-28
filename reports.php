<?php
include('config/config.php');
session_start();
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
    <?php include('sidebar/sidebar.php')?>

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
