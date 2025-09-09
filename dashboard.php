<?php
session_start();
include('config/config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
// $current_month = '2025-07';
// Lấy tháng hiện tại theo định dạng YYYY-MM
$current_month = date('Y-m');


// Tổng thu nhập
$sqlIncome = "SELECT COALESCE(SUM(t.amount), 0) FROM transactions t 
              JOIN categories c ON t.category_id = c.id 
              WHERE t.user_id = :user_id AND c.type = 'income' AND DATE_FORMAT(t.transaction_date, '%Y-%m') = :current_month";
$stmt = $conn->prepare($sqlIncome);
$stmt->execute([':user_id' => $user_id, ':current_month' => $current_month]);
$total_income = $stmt->fetchColumn();



// Tổng chi tiêu
$sqlExpense = "SELECT COALESCE(SUM(t.amount), 0) FROM transactions t 
               JOIN categories c ON t.category_id = c.id 
               WHERE t.user_id = :user_id AND c.type = 'expense' AND DATE_FORMAT(t.transaction_date, '%Y-%m') = :current_month";
$stmt = $conn->prepare($sqlExpense);
$stmt->execute([':user_id' => $user_id, ':current_month' => $current_month]);
$total_expense = $stmt->fetchColumn();

// Tổng số dư
$balance = $total_income - $total_expense;

// Chi tiêu tháng hiện tại từ bảng statistics
// $sqlMonthExpense= "SELECT SUM(total_amount) FROM statistics 
//         WHERE user_id = :user_id AND type = :type 
//         AND period_type = 'month' AND period_value = :period_value";
// $stmt = $conn->prepare($sqlMonthExpense);
// $stmt->execute([
//     ':user_id' => $user_id,
//     ':type' => 'expense',
//     ':period_value' => $current_month
// ]);
// $monthly_expense= $stmt->fetchColumn() ?? 0;
// Thu nhập tháng hiện tại từ bảng statistics
// $sqlMonthIncome = "SELECT SUM(total_amount) FROM statistics 
//         WHERE user_id = :user_id AND type = :type 
//         AND period_type = 'month' AND period_value = :period_value";
// $stmt = $conn->prepare($sqlMonthIncome);
// $stmt->execute([
//     ':user_id' => $user_id,
//     ':type' => 'income',
//     ':period_value' => $current_month
// ]);
// $monthly_income = $stmt->fetchColumn() ?? 0;

// Giao dịch gần đây
$sqlRecent = "SELECT t.description, c.name AS category, c.type, t.amount, t.transaction_date 
              FROM transactions t 
              JOIN categories c ON t.category_id = c.id 
              WHERE t.user_id = :user_id 
              AND MONTH(t.transaction_date) = MONTH(CURRENT_DATE())
                AND YEAR(t.transaction_date) = YEAR(CURRENT_DATE())
              ORDER BY t.transaction_date DESC 
              LIMIT 10";
$stmt = $conn->prepare($sqlRecent);
$stmt->execute([':user_id' => $user_id]);
$recent_transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo "<pre>";
// print_r($recent_transactions);
// echo "</pre>";


// Phân tích tổng thu nhập và chi tiêu theo tháng cho pie chart (100%)
// $sum_query = "SELECT 
//     SUM(CASE WHEN type = 'income' THEN total_amount ELSE 0 END) AS income_sum,
//     SUM(CASE WHEN type = 'expense' THEN total_amount ELSE 0 END) AS expense_sum
//     FROM statistics 
//     WHERE user_id = :user_id AND period_type = 'month' AND period_value = :month";

// $stmt = $conn->prepare($sum_query);
// $stmt->execute([
//     ':user_id' => $user_id,
//     ':month' => $current_month
// ]);
// $sum_result = $stmt->fetch(PDO::FETCH_ASSOC) ?? 0;
// $income_sum = $sum_result['income_sum'] ?? 0;
// $expense_sum = $sum_result['expense_sum'] ?? 0;
// $total_sum = $income_sum + $expense_sum;

// $expense_analysis = [];
// if ($total_sum > 0) {
//     $expense_analysis[] = [
//         'type' => 'income',
//         'percentage' => round($income_sum * 100 / $total_sum, 2),
//         'color' => '#43a047',
//         'label' => 'Thu nhập'
//     ];
//     $expense_analysis[] = [
//         'type' => 'expense',
//         'percentage' => round($expense_sum * 100 / $total_sum, 2),
//         'color' => '#e53935',
//         'label' => 'Chi tiêu'
//     ];
// }
// Phân tích tổng thu nhập và chi tiêu theo tháng cho pie chart (100%)
$sum_query = "SELECT 
    SUM(CASE WHEN c.type = 'income' THEN t.amount ELSE 0 END) AS income_sum,
    SUM(CASE WHEN c.type = 'expense' THEN t.amount ELSE 0 END) AS expense_sum
FROM transactions t
JOIN categories c ON t.category_id = c.id
WHERE t.user_id = :user_id
  AND DATE_FORMAT(t.transaction_date, '%Y-%m') = :month";

$stmt = $conn->prepare($sum_query);
$stmt->execute([
    ':user_id' => $user_id,
    ':month' => $current_month
]);
$sum_result = $stmt->fetch(PDO::FETCH_ASSOC) ?? [];

$income_sum = $sum_result['income_sum'] ?? 0;
$expense_sum = $sum_result['expense_sum'] ?? 0;
$total_sum = $income_sum + $expense_sum;

$expense_analysis = [];
if ($total_sum > 0) {
    $expense_analysis[] = [
        'type' => 'income',
        'percentage' => round($income_sum * 100 / $total_sum, 2),
        'color' => '#43a047',
        'label' => 'Thu nhập'
    ];
    $expense_analysis[] = [
        'type' => 'expense',
        'percentage' => round($expense_sum * 100 / $total_sum, 2),
        'color' => '#e53935',
        'label' => 'Chi tiêu'
    ];
}
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
    <?php include('sidebar/sidebar.php') ?>

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
                    <h3 class="count-up" data-value="<?php echo $total_income; ?>">+0đ</h3>
                </div>
            </div>
            <div class="card animated-card magnetic-effect" style="animation-delay: 0.2s;">
                <div class="card-icon" style="background-color: #ffebee;">
                    <i class='bx bx-trending-down' style="color: #e53935;"></i>
                </div>
                <div class="card-info">
                    <p>Chi tiêu tháng</p>
                    <h3 class="count-up" data-value="<?php echo $total_expense; ?>">-0đ</h3>
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
                    <?php foreach ($recent_transactions as $index => $t): ?>
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
                <div >
                    <canvas id="expenseDonutChart" width="200" height="200"></canvas>
                    <div class="chart-center-text">
                        <span>Tổng</span>
                        <h4 class="count-up" data-value="<?php echo $expense_sum; ?>">0đ</h4>
                    </div>
                </div>

                <ul class="chart-legend">
                    <?php foreach ($expense_analysis as $item): ?>
                    <li>
                        <span class="legend-color" style="background-color: <?php echo $item['color']; ?>;"></span>
                       
                        <span class="legend-percentage"><?php echo $item['percentage']; ?>%</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ==== DỮ LIỆU BIỂU ĐỒ TỪ PHP ====
    const chartData = <?php echo json_encode($expense_analysis); ?>;

    const labels = chartData.map(item => item.label);
    const percentages = chartData.map(item => item.percentage);
    const colors = chartData.map(item => item.color);

    const ctx = document.getElementById('expenseDonutChart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: percentages,
                backgroundColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

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

    // --- HIỆU ỨNG NAM CHÂM ---
    const magneticElements = document.querySelectorAll('.magnetic-effect');
    magneticElements.forEach(el => {
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

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
