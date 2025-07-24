<?php
// BẬT HIỂN THỊ LỖI PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- MÔ PHỎNG DỮ LIỆU ĐỘNG ---
$userName = trim(" Khánh");
$userEmail = "duykhanh@gmail.com";

// Dữ liệu mẫu cho các ngân sách
$budgets = [
    ['category' => 'Ăn uống', 'limit' => 5000000, 'spent' => 4550000, 'icon' => 'bx-restaurant', 'color' => '#ef4444'],
    ['category' => 'Mua sắm', 'limit' => 3000000, 'spent' => 1250000, 'icon' => 'bx-shopping-bag', 'color' => '#f97316'],
    ['category' => 'Di chuyển', 'limit' => 1000000, 'spent' => 650000, 'icon' => 'bx-car', 'color' => '#3b82f6'],
    ['category' => 'Giải trí', 'limit' => 1500000, 'spent' => 250000, 'icon' => 'bx-movie-play', 'color' => '#a855f7'],
    ['category' => 'Hóa đơn', 'limit' => 5000000, 'spent' => 5000000, 'icon' => 'bx-receipt', 'color' => '#84cc16'],
];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngân sách - Quản lý chi tiêu</title>
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
            <img src="https://scontent.fsgn21-1.fna.fbcdn.net/v/t39.30808-6/474189628_1310277973503688_3036333816967852750_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_ohc=hMqcP2MP3hMQ7kNvwEysxR9&_nc_oc=AdmRy2aZYjM-Q9iwxXPO7EnyU9y8I4N4-r31oBvb1bv3Yc0YYB1M-VXyn56PtGCFM2AaUSbYKZBD2yLKF6QUMmWa&_nc_zt=23&_nc_ht=scontent.fsgn21-1.fna&_nc_gid=2VfzLP0HwXXmY6gmfFxWqw&oh=00_AfRS2AS7qmPGz9dSe3jvA1Tx5pWDZOF9KwbBA6Q7rky9vg&oe=687D3C9E" alt="Avatar" class="profile-avatar">
            <h4 class="profile-name"><?php echo htmlspecialchars($userName); ?></h4>
            <p class="profile-email"><?php echo htmlspecialchars($userEmail); ?></p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php"><i class='bx bxs-dashboard'></i><span>Bảng điều khiển</span></a></li>
                <li><a href="accounts.php"><i class='bx bxs-credit-card-alt'></i><span>Tài khoản</span></a></li>
                <li><a href="transactions.php"><i class='bx bx-transfer-alt'></i><span>Giao dịch</span></a></li>
                <li><a href="reports.php"><i class='bx bx-bar-chart-square'></i><span>Báo cáo</span></a></li>
                <li><a href="budgets.php" class="active"><i class='bx bx-pie-chart-alt-2'></i><span>Ngân sách</span></a></li>
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
                <h1>Quản lý Ngân sách</h1>
                <p class="welcome-message">Theo dõi giới hạn chi tiêu hàng tháng của bạn.</p>
            </div>
             <div class="header-right">
                <button id="add-budget-btn" class="action-button primary-btn"><i class='bx bx-plus'></i> <span>Tạo ngân sách mới</span></button>
            </div>
        </header>

        <section id="budgets-grid" class="budgets-grid">
            <?php foreach ($budgets as $budget): 
                $percentage = ($budget['spent'] / $budget['limit']) * 100;
                $remaining = $budget['limit'] - $budget['spent'];
            ?>
            <div class="budget-card animated-card" 
                 data-category="<?php echo htmlspecialchars($budget['category']); ?>" 
                 data-limit="<?php echo $budget['limit']; ?>">
                <div class="budget-card-header">
                    <div class="budget-card-icon">
                        <i class='bx <?php echo htmlspecialchars($budget['icon']); ?>' style="color: <?php echo htmlspecialchars($budget['color']); ?>;"></i>
                    </div>
                    <h3 class="budget-card-category"><?php echo htmlspecialchars($budget['category']); ?></h3>
                </div>
                <div class="budget-card-body">
                    <div class="budget-progress-bar">
                        <div class="progress-fill" style="width: <?php echo min($percentage, 100); ?>%; background-color: <?php echo htmlspecialchars($budget['color']); ?>;"></div>
                    </div>
                    <div class="budget-details">
                        <p>Đã chi: <span><?php echo number_format($budget['spent'], 0, ',', '.'); ?>đ</span></p>
                        <p>Còn lại: <span><?php echo number_format($remaining, 0, ',', '.'); ?>đ</span></p>
                    </div>
                </div>
                <div class="budget-card-footer">
                    <p>Hạn mức: <?php echo number_format($budget['limit'], 0, ',', '.'); ?>đ</p>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
    </main>
</div>

<!-- ========== MODAL TẠO/SỬA NGÂN SÁCH ========== -->
<div id="add-budget-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="budget-modal-title">Tạo Ngân sách mới</h2>
            <button id="close-budget-modal-btn" class="close-button">&times;</button>
        </div>
        <div class="modal-body">
            <form id="budget-form" action="#" method="POST">
                <div class="form-group-modal">
                    <label for="budget-category">Danh mục</label>
                    <select id="budget-category" name="budget_category">
                        <option>Ăn uống</option>
                        <option>Mua sắm</option>
                        <option>Di chuyển</option>
                        <option>Hóa đơn</option>
                        <option>Giải trí</option>
                        <option>Khác</option>
                    </select>
                </div>
                <div class="form-group-modal">
                    <label for="budget-limit">Hạn mức</label>
                    <input type="number" id="budget-limit" name="budget_limit" placeholder="0" required>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancel-budget-btn" class="btn btn-secondary">Hủy</button>
                    <button type="submit" id="save-budget-btn" class="btn btn-primary">Lưu Ngân sách</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Lấy các phần tử cần thiết từ DOM
    const addBudgetBtn = document.getElementById('add-budget-btn');
    const budgetModal = document.getElementById('add-budget-modal');
    const closeBudgetModalBtn = document.getElementById('close-budget-modal-btn');
    const cancelBudgetBtn = document.getElementById('cancel-budget-btn');
    const budgetsGrid = document.getElementById('budgets-grid');

    // Các trường trong Modal
    const budgetModalTitle = document.getElementById('budget-modal-title');
    const saveBudgetBtn = document.getElementById('save-budget-btn');
    const budgetCategorySelect = document.getElementById('budget-category');
    const budgetLimitInput = document.getElementById('budget-limit');
    const budgetForm = document.getElementById('budget-form');

    // Hàm hiển thị modal
    const showBudgetModal = () => {
        budgetModal.classList.remove('hidden');
    };

    // Hàm ẩn modal
    const hideBudgetModal = () => {
        budgetModal.classList.add('hidden');
    };

    // Hàm chuẩn bị modal cho việc "Thêm mới"
    const setupAddModal = () => {
        budgetForm.reset(); // Xóa dữ liệu cũ
        budgetModalTitle.textContent = 'Tạo Ngân sách mới';
        saveBudgetBtn.textContent = 'Lưu Ngân sách';
        showBudgetModal();
    };
    
    // Hàm chuẩn bị modal cho việc "Chỉnh sửa"
    const setupEditModal = (card) => {
        const category = card.dataset.category;
        const limit = card.dataset.limit;

        // Điền dữ liệu vào form
        budgetCategorySelect.value = category;
        budgetLimitInput.value = limit;

        // Cập nhật giao diện modal
        budgetModalTitle.textContent = 'Chỉnh sửa Ngân sách';
        saveBudgetBtn.textContent = 'Cập nhật';
        showBudgetModal();
    };

    // Gán sự kiện click để hiển thị modal "Thêm mới"
    addBudgetBtn.addEventListener('click', setupAddModal);
    
    // Gán sự kiện click cho toàn bộ lưới ngân sách
    budgetsGrid.addEventListener('click', (e) => {
        const budgetCard = e.target.closest('.budget-card');
        if (budgetCard) {
            setupEditModal(budgetCard);
        }
    });

    // Gán các sự kiện để ẩn modal
    closeBudgetModalBtn.addEventListener('click', hideBudgetModal);
    cancelBudgetBtn.addEventListener('click', hideBudgetModal);
    budgetModal.addEventListener('click', (e) => {
        if (e.target === budgetModal) {
            hideBudgetModal();
        }
    });
</script>

</body>
</html>
