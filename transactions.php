<?php
// --- MÔ PHỎNG DỮ LIỆU ĐỘNG ---
$userName = trim(" Khánh");
$userEmail = "duykhanh@gmail.com";

// Lấy các giá trị bộ lọc từ URL, nếu không có thì dùng giá trị mặc định
$selectedDay = $_GET['day'] ?? date('d');
$selectedMonth = $_GET['month'] ?? date('m');
$selectedYear = $_GET['year'] ?? date('Y');
$selectedType = $_GET['type'] ?? 'all';


// Dữ liệu giao dịch mẫu MỞ RỘNG (thêm dữ liệu tháng 6 và ID)
$all_transactions = [
    // Tháng 7
    ['id' => 1, 'date' => '15/07/2025', 'type' => 'expense', 'category' => 'Ăn uống', 'description' => 'Cà phê với bạn bè', 'amount' => -75000],
    ['id' => 2, 'date' => '15/07/2025', 'type' => 'expense', 'category' => 'Di chuyển', 'description' => 'Grab bike đi làm', 'amount' => -25000],
    ['id' => 3, 'date' => '14/07/2025', 'type' => 'expense', 'category' => 'Mua sắm', 'description' => 'Mua sắm tại siêu thị Co.opmart', 'amount' => -1250000],
    ['id' => 4, 'date' => '12/07/2025', 'type' => 'expense', 'category' => 'Giải trí', 'description' => 'Xem phim tại CGV', 'amount' => -250000],
    ['id' => 5, 'date' => '10/07/2025', 'type' => 'expense', 'category' => 'Hóa đơn', 'description' => 'Thanh toán tiền điện', 'amount' => -650000],
    ['id' => 6, 'date' => '05/07/2025', 'type' => 'income', 'category' => 'Lương', 'description' => 'Lương tháng 7', 'amount' => 15000000],
    ['id' => 7, 'date' => '01/07/2025', 'type' => 'expense', 'category' => 'Hóa đơn', 'description' => 'Thanh toán tiền nhà', 'amount' => -4000000],
    // Tháng 6
    ['id' => 8, 'date' => '25/06/2025', 'type' => 'income', 'category' => 'Thưởng', 'description' => 'Thưởng dự án', 'amount' => 2000000],
    ['id' => 9, 'date' => '20/06/2025', 'type' => 'expense', 'category' => 'Ăn uống', 'description' => 'Ăn trưa văn phòng', 'amount' => -50000],
    ['id' => 10, 'date' => '05/06/2025', 'type' => 'income', 'category' => 'Lương', 'description' => 'Lương tháng 6', 'amount' => 15000000],
];

// --- LOGIC LỌC GIAO DỊCH NÂNG CAO ---
$filtered_transactions = $all_transactions;

// 1. Lọc theo Năm
$filtered_transactions = array_filter($filtered_transactions, function($transaction) use ($selectedYear) {
    $date_parts = explode('/', $transaction['date']);
    return $date_parts[2] == $selectedYear;
});

// 2. Lọc theo Tháng
$filtered_transactions = array_filter($filtered_transactions, function($transaction) use ($selectedMonth) {
    $date_parts = explode('/', $transaction['date']);
    return ltrim($date_parts[1], '0') == $selectedMonth;
});

// 3. Lọc theo Ngày (chỉ khi ngày cụ thể được chọn)
if ($selectedDay !== 'all') {
    $filtered_transactions = array_filter($filtered_transactions, function($transaction) use ($selectedDay) {
        $date_parts = explode('/', $transaction['date']);
        return ltrim($date_parts[0], '0') == $selectedDay;
    });
}

// 4. Lọc theo Loại (Thu nhập/Chi tiêu)
if ($selectedType !== 'all') {
    $filtered_transactions = array_filter($filtered_transactions, function($transaction) use ($selectedType) {
        return $transaction['type'] === $selectedType;
    });
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao dịch - Quản lý chi tiêu</title>
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
                <li><a href="transactions.php" class="active"><i class='bx bx-transfer-alt'></i><span>Giao dịch</span></a></li>
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
                <h1>Quản lý Giao dịch</h1>
                <p class="welcome-message">Xem và quản lý tất cả giao dịch của bạn tại đây.</p>
            </div>
             <div class="header-right">
                <button id="add-transaction-btn" class="action-button primary-btn"><i class='bx bx-plus'></i> <span>Thêm giao dịch</span></button>
            </div>
        </header>

        <section class="transactions-page-container animated-card">
            <!-- BỘ LỌC ĐÃ ĐƯỢC NÂNG CẤP -->
            <form class="filter-bar" method="GET" action="transactions.php">
                <div class="filter-group">
                    <label for="day-select">Ngày:</label>
                    <select id="day-select" name="day" onchange="this.form.submit()">
                        <option value="all">Tất cả</option>
                        <?php for ($i = 1; $i <= 31; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php if ($i == $selectedDay) echo 'selected'; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="month-select">Tháng:</label>
                    <select id="month-select" name="month" onchange="this.form.submit()">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php if ($m == $selectedMonth) echo 'selected'; ?>>
                                Tháng <?php echo $m; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                 <div class="filter-group">
                    <label for="year-select">Năm:</label>
                    <select id="year-select" name="year" onchange="this.form.submit()">
                        <?php for ($y = date('Y') - 2; $y <= date('Y') + 5; $y++): ?>
                             <option value="<?php echo $y; ?>" <?php if ($y == $selectedYear) echo 'selected'; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                 <div class="filter-group">
                    <label for="type-select">Loại:</label>
                    <select id="type-select" name="type" onchange="this.form.submit()">
                        <option value="all" <?php if ($selectedType == 'all') echo 'selected'; ?>>Tất cả</option>
                        <option value="income" <?php if ($selectedType == 'income') echo 'selected'; ?>>Thu nhập</option>
                        <option value="expense" <?php if ($selectedType == 'expense') echo 'selected'; ?>>Chi tiêu</option>
                    </select>
                </div>
            </form>

            <div class="transactions-table-wrapper">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Danh mục</th>
                            <th>Mô tả</th>
                            <th class="amount-col">Số tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="transaction-table-body">
                        <?php if (empty($filtered_transactions)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px;">Không có giao dịch nào phù hợp.</td>
                            </tr>
                        <?php else: ?>
                            <?php $index = 0; foreach ($filtered_transactions as $t): ?>
                            <tr class="animated-li" style="animation-delay: <?php echo $index * 0.05; ?>s;"
                                data-id="<?php echo $t['id']; ?>" 
                                data-type="<?php echo $t['type']; ?>" 
                                data-category="<?php echo htmlspecialchars($t['category']); ?>" 
                                data-description="<?php echo htmlspecialchars($t['description']); ?>" 
                                data-amount="<?php echo abs($t['amount']); ?>" 
                                data-date="<?php echo date('Y-m-d', strtotime(str_replace('/', '-', $t['date']))); ?>">
                                <td><?php echo $t['date']; ?></td>
                                <td><?php echo htmlspecialchars($t['category']); ?></td>
                                <td><?php echo htmlspecialchars($t['description']); ?></td>
                                <td class="amount-col <?php echo $t['type']; ?>">
                                    <?php echo number_format($t['amount'], 0, ',', '.'); ?>đ
                                </td>
                                <td class="action-col">
                                    <button class="table-action-btn edit-btn"><i class='bx bxs-edit'></i></button>
                                    <button class="table-action-btn delete-btn"><i class='bx bxs-trash'></i></button>
                                </td>
                            </tr>
                            <?php $index++; endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<!-- ========== MODAL THÊM/SỬA GIAO DỊCH ========== -->
<div id="add-transaction-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Thêm Giao dịch mới</h2>
            <button id="close-modal-btn" class="close-button">&times;</button>
        </div>
        <div class="modal-body">
            <form id="transaction-form" action="#" method="POST">
                <input type="hidden" id="trans-id" name="trans_id">
                <div class="form-row">
                    <div class="form-group-modal">
                        <label for="trans-type">Loại giao dịch</label>
                        <select id="trans-type" name="trans_type">
                            <option value="expense">Chi tiêu</option>
                            <option value="income">Thu nhập</option>
                        </select>
                    </div>
                    <div class="form-group-modal">
                        <label for="trans-amount">Số tiền</label>
                        <input type="number" id="trans-amount" name="trans_amount" placeholder="0" required>
                    </div>
                </div>
                <div class="form-group-modal">
                    <label for="trans-category">Danh mục</label>
                    <select id="trans-category" name="trans_category">
                        <option>Ăn uống</option>
                        <option>Mua sắm</option>
                        <option>Di chuyển</option>
                        <option>Hóa đơn</option>
                        <option>Giải trí</option>
                        <option>Lương</option>
                        <option>Thưởng</option>
                        <option>Khác</option>
                    </select>
                </div>
                <div class="form-group-modal">
                    <label for="trans-date">Ngày</label>
                    <input type="date" id="trans-date" name="trans_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group-modal">
                    <label for="trans-description">Mô tả</label>
                    <textarea id="trans-description" name="trans_description" rows="3" placeholder="Thêm mô tả ngắn..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancel-btn" class="btn btn-secondary">Hủy</button>
                    <button type="submit" id="save-btn" class="btn btn-primary">Lưu Giao dịch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== MODAL XÁC NHẬN XÓA ========== -->
<div id="delete-confirm-modal" class="modal-overlay hidden">
    <div class="modal-content modal-sm">
        <div class="modal-header">
            <h2>Xác nhận Xóa</h2>
            <button id="close-delete-modal-btn" class="close-button">&times;</button>
        </div>
        <div class="modal-body">
            <p>Bạn có chắc chắn muốn xóa giao dịch này không? Hành động này không thể hoàn tác.</p>
        </div>
        <div class="modal-footer">
            <button type="button" id="cancel-delete-btn" class="btn btn-secondary">Hủy</button>
            <button type="button" id="confirm-delete-btn" class="btn btn-danger">Xóa Giao dịch</button>
        </div>
    </div>
</div>


<!-- JAVASCRIPT ĐỂ ĐIỀU KHIỂN MODAL VÀ CÁC HÀNH ĐỘNG -->
<script>
    // Lấy các phần tử cần thiết từ DOM
    const addTransactionBtn = document.getElementById('add-transaction-btn');
    const transactionTableBody = document.getElementById('transaction-table-body');
    
    // Modal Thêm/Sửa
    const addEditModal = document.getElementById('add-transaction-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const transTypeSelect = document.getElementById('trans-type');
    const transAmountInput = document.getElementById('trans-amount');
    const modalTitle = document.getElementById('modal-title');
    const saveBtn = document.getElementById('save-btn');
    const transIdInput = document.getElementById('trans-id');
    const transCategorySelect = document.getElementById('trans-category');
    const transDateInput = document.getElementById('trans-date');
    const transDescriptionTextarea = document.getElementById('trans-description');

    // Modal Xác nhận Xóa
    const deleteModal = document.getElementById('delete-confirm-modal');
    const closeDeleteModalBtn = document.getElementById('close-delete-modal-btn');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    let rowToDelete = null; // Biến để lưu trữ hàng cần xóa

    // --- Các hàm chung ---
    const showModal = (modalElement) => modalElement.classList.remove('hidden');
    const hideModal = (modalElement) => modalElement.classList.add('hidden');

    // --- Xử lý Modal Thêm/Sửa ---
    const updateAmountStyle = () => {
        const selectedType = transTypeSelect.value;
        transAmountInput.classList.remove('income-text', 'expense-text');
        if (selectedType === 'income') {
            transAmountInput.classList.add('income-text');
        } else {
            transAmountInput.classList.add('expense-text');
        }
    };

    const setupAddModal = () => {
        document.getElementById('transaction-form').reset();
        transIdInput.value = '';
        modalTitle.textContent = 'Thêm Giao dịch mới';
        saveBtn.textContent = 'Lưu Giao dịch';
        transDateInput.value = new Date().toISOString().slice(0, 10);
        updateAmountStyle();
        showModal(addEditModal);
    };

    addTransactionBtn.addEventListener('click', setupAddModal);
    closeModalBtn.addEventListener('click', () => hideModal(addEditModal));
    cancelBtn.addEventListener('click', () => hideModal(addEditModal));
    addEditModal.addEventListener('click', (e) => {
        if (e.target === addEditModal) hideModal(addEditModal);
    });
    transTypeSelect.addEventListener('change', updateAmountStyle);

    // --- Xử lý Modal Xóa ---
    closeDeleteModalBtn.addEventListener('click', () => hideModal(deleteModal));
    cancelDeleteBtn.addEventListener('click', () => hideModal(deleteModal));
    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) hideModal(deleteModal);
    });

    confirmDeleteBtn.addEventListener('click', () => {
        if (rowToDelete) {
            rowToDelete.remove();
            hideModal(deleteModal);
            rowToDelete = null; // Reset biến
        }
    });

    // --- Xử lý sự kiện click trên bảng (Sửa và Xóa) ---
    transactionTableBody.addEventListener('click', (e) => {
        const target = e.target;
        const editBtn = target.closest('.edit-btn');
        const deleteBtn = target.closest('.delete-btn');

        if (editBtn) {
            const row = editBtn.closest('tr');
            const dataset = row.dataset;

            transIdInput.value = dataset.id;
            transTypeSelect.value = dataset.type;
            transAmountInput.value = dataset.amount;
            transCategorySelect.value = dataset.category;
            transDateInput.value = dataset.date;
            transDescriptionTextarea.value = dataset.description;

            modalTitle.textContent = 'Chỉnh sửa Giao dịch';
            saveBtn.textContent = 'Cập nhật';
            updateAmountStyle();
            showModal(addEditModal);
        }

        if (deleteBtn) {
            rowToDelete = deleteBtn.closest('tr'); // Lưu hàng cần xóa
            showModal(deleteModal); // Hiển thị modal xác nhận
        }
    });

</script>

</body>
</html>
