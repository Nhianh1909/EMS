<?php
session_start();
include('config/config.php');
// Dữ liệu báo cáo mẫu
// $report_summary = [
//     'total_income' => 15000000,
//     'total_expense' => 6800000,
//     'net_income' => 8200000
// ];
//tạo bộ lọc để lấy giá trị tháng, năm
$filter = $_GET['report_period'] ?? 'this_month';
//khởi tạo where để lấy dữ liệu transaction_date theo UI19
$where = '';
switch($filter){
    case 'last_month':
        $where = "AND YEAR(t.transaction_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)
                  AND MONTH(t.transaction_date) = MONTH(CURDATE() - INTERVAL 1 MONTH)";
        break;    
    case 'this_year':
        $where = "AND YEAR(t.transaction_date) = YEAR(CURDATE())";
        break;
    default:
        $where = "AND YEAR(t.transaction_date) = YEAR(CURDATE())
                  AND MONTH(t.transaction_date) = MONTH(CURDATE())";
        break;
}


//truy vấn biến $report_summary để lấy ra tổng thu và tổng chi tiêu 
$report_summary = $conn->prepare(
    "SELECT SUM(CASE WHEN c.type = 'income' THEN t.amount ELSE 0 END) AS total_income,
    SUM(CASE WHEN c.type = 'expense' THEN t.amount ELSE 0 END) AS total_expense
    FROM transactions t
    JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = :user_id
    $where
");
$report_summary->execute(['user_id'=>$_SESSION['user_id']]);
$report_summary = $report_summary->fetch(PDO::FETCH_ASSOC);
//truy vấn xong gán kết quả cho từng biến để dễ dùng
$total_income = $report_summary['total_income'] ?? 0;
$total_expense = $report_summary['total_expense'] ?? 0;
$net_income = $total_income - $total_expense;




// Dữ liệu cho biểu đồ cột (Thu nhập vs Chi tiêu)
// $income_expense_data = [
//     ['month' => 'Tháng 5', 'income' => 15000000, 'expense' => 5500000],
//     ['month' => 'Tháng 6', 'income' => 17000000, 'expense' => 7200000],
//     ['month' => 'Tháng 7', 'income' => 15000000, 'expense' => 6800000],
// ];
//truy vấn biến $income_expense_data để lấy ra dữ liệu cho biểu đồ cột (Thu nhập vs Chi tiêu) sắp xếp theo tháng
$income_expense_data = $conn->prepare(
    "SELECT
     DATE_FORMAT(t.transaction_date, '%Y-%m') AS month,
     SUM(CASE WHEN c.type = 'income' THEN t.amount ELSE 0 END) AS income,
     SUM(CASE WHEN c.type = 'expense' THEN t.amount ELSE 0 END) AS expense
     FROM transactions t
     JOIN categories c ON t.category_id = c.id
     WHERE t.user_id = :user_id
     $where
     GROUP BY DATE_FORMAT(t.transaction_date, '%Y-%m')
     ORDER BY month ASC"
);
$income_expense_data->execute(['user_id' => $_SESSION['user_id']]);
$income_expense_data = $income_expense_data->fetchAll(PDO::FETCH_ASSOC);
//tạo biến $max_value để lấy ra giá trị lớn nhất của thu nhập và chi tiêu(Nhằm lấy phần trăm dựa trên giá trị lớn nhất)
$max_value = 0;
foreach($income_expense_data as $row) {
    if($row['income']>$max_value) $max_value = $row['income'];
    if($row['expense']>$max_value) $max_value = $row['expense'];
}
if($max_value == 0) $max_value = 1;//tránh chia 0 vì sẽ lỗi 
// Dữ liệu cho biểu đồ tròn (Phân loại chi tiêu)
// $expense_by_category = [
//     ['category' => 'Hóa đơn', 'amount' => 4000000, 'percentage' => 58.8, 'color' => '#3b82f6'],
//     ['category' => 'Ăn uống', 'amount' => 1500000, 'percentage' => 22.1, 'color' => '#ef4444'],
//     ['category' => 'Mua sắm', 'amount' => 800000, 'percentage' => 11.8, 'color' => '#f97316'],
//     ['category' => 'Khác', 'amount' => 500000, 'percentage' => 7.3, 'color' => '#a855f7'],
// ];

// truy vấn lấy ra tên danh mục và tổng số tiền chi tiêu của từng danh mục và được lọc theo $where
$expense_stmt = $conn->prepare(
    "SELECT c.name AS category,
    SUM(t.amount) AS amount
    FROM transactions t
    JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = :user_id AND c.type = 'expense'
    $where
    GROUP BY c.id
");
$expense_stmt->execute(['user_id'=>$_SESSION['user_id']]);
$expense_data = $expense_stmt->fetchAll(PDO::FETCH_ASSOC);

//khởi tạo biến tổng chi tiêu để tính phần trăm của từng amount dựa trên tổng
$total_expense = 0;
foreach($expense_data as $row) {
    $total_expense += $row['amount'];
}
$expense_by_category = [];
foreach($expense_data as $row){
    $percent = $total_expense > 0 ? ($row['amount'] / $total_expense) * 100 : 0;

//gán màu tĩnh theo tên category (ví dụ if/else)
$colors = "#999";
if($row['category']=='Ăn uống') $color = '#3b82f6';
else if($row['category']=='Mua sắm') $color = '#f97316';
else if($row['category']=='Hóa đơn điện') $color = '#f43f5e';
else if($row['category']=='Tiền điện') $color = '#10b981';
else if($row['category']=='Giải trí') $color = '#a855f7';
else if($row['category']=='Nuôi thú cưng') $color = '#13092AFF';
else $color = '#55F7F2FF';
//sau khi gán màu theo từng danh mục -> gán vào mảng
$expense_by_category[] = [
    'category'=>$row['category'],
    'amount'=>$row['amount'],
    'percentage' => round($percent, 1),
    'color' => $color
];
}

// Tính $gradient
$gradient = '';
$start = 0;
foreach ($expense_by_category as $item) {
  $end = $start + $item['percentage'];
  $gradient .= "{$item['color']} {$start}% {$end}%, ";
  $start = $end;//sau khi gán xong thì gán $start = $end để cập nhật lại từng dảy màu của danh mục
}//cho vòng lặp lặp qua từng danh mục và gán dãy màu đã gán ở hàm trên
$gradient = rtrim($gradient, ', ');//dùng rtrim để bỏ dấu phẩy vì các danh mục đang nằm trong mảng có dấu phẩy

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
                    <form method="GET">
                    <select id="report-period" name="report_period" onchange="this.form.submit()">
                        <option value="this_month" <?php if (($_GET['report_period'] ?? '') === 'this_month') echo 'selected'; ?>>Tháng này</option>
                        <option value="last_month" <?php if (($_GET['report_period'] ?? '') === 'last_month') echo 'selected'; ?>>Tháng trước</option>
                        <option value="this_year" <?php if (($_GET['report_period'] ?? '') === 'this_year') echo 'selected'; ?>>Năm nay</option>
                    </select>
                    </form>
                </div>
            </div>
        </header>

        <section class="overview-cards">
            <div class="card">
                <div class="card-icon" style="background-color: #e8f5e9;"><i class='bx bx-trending-up' style="color: #43a047;"></i></div>
                <div class="card-info">
                    <p>Tổng thu nhập</p>
                    <h3>+<?php echo number_format($total_income, 0, ',', '.'); ?>đ</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-icon" style="background-color: #ffebee;"><i class='bx bx-trending-down' style="color: #e53935;"></i></div>
                <div class="card-info">
                    <p>Tổng chi tiêu</p>
                    <h3>-<?php echo number_format($total_expense, 0, ',', '.'); ?>đ</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-icon" style="background-color: #e0f7fa;"><i class='bx bxs-wallet' style="color: #00acc1;"></i></div>
                <div class="card-info">
                    <p>Dòng tiền ròng</p>
                    <h3 style="color: <?php echo $net_income >= 0 ? '#22c55e' : '#ef4444'; ?>;">
                        <?php echo number_format($net_income, 0, ',', '.'); ?>đ
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
                            <div class="bar income-bar" style="height: <?php echo ($data['income']/$max_value)*100; ?>%;"></div>
                            <div class="bar expense-bar" style="height: <?php echo ($data['expense']/$max_value)*100; ?>%;"></div>
                        </div>
                        <span class="bar-label"><?php echo $data['month']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="report-card">
                <h3 class="report-card-title">Phân loại chi tiêu</h3>
                <div class="pie-chart-container">
                    <div class="pie-chart-placeholder" style="background: conic-gradient(<?php echo $gradient; ?>);"></div>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const theme = localStorage.getItem('theme');
        if (theme === 'dark') {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    });
</script>

</body>
</html>
