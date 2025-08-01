<?php
// Hiển thị lỗi (chỉ nên dùng khi DEV)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('config/config.php');
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$categoryIcons = [
    'Ăn uống'   => ['icon' => 'bx-restaurant',   'color' => '#ef4444'],
    'Mua sắm'   => ['icon' => 'bx-shopping-bag', 'color' => '#f97316'],
    'Di chuyển' => ['icon' => 'bx-car',          'color' => '#3b82f6'],
    'Giải trí'  => ['icon' => 'bx-movie-play',   'color' => '#a855f7'],
    'Hóa đơn'   => ['icon' => 'bx-receipt',      'color' => '#84cc16'],
    'Khác'      => ['icon' => 'bx-dots-horizontal-rounded', 'color' => '#6b7280']
];

// 2. Truy vấn ngân sách kèm tổng chi tiêu
$sql = "
        SELECT 
        b.category_id AS category_id,
        c.name AS category_name,
        b.amount AS total_budget,
        c.type AS category_type,
        COALESCE(SUM(t.amount), 0) AS total_spent
    FROM budgets b
    JOIN categories c ON b.category_id = c.id
    LEFT JOIN transactions t ON b.category_id = t.category_id
    WHERE b.user_id = :user_id AND c.type = 'expense'
    GROUP BY b.category_id, c.name, b.amount, c.type
";


    // LEFT JOIN transactions t ON b.category_id = t.category_id
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($results);
// echo '</pre>';
// 3. Gộp dữ liệu và ánh xạ icon
$budgets = [];
foreach ($results as $row) {
    $categoryName = $row['category_name'];
    $limit = (int)$row['total_budget'];
    $spent = (int)$row['total_spent'];

    // Lấy icon và màu từ ánh xạ
    $iconInfo = $categoryIcons[$categoryName] ?? ['icon' => 'bx-category', 'color' => '#9ca3af'];
    
    $budgets[] = [
        'id'=> $row['category_id'],
        'category' => $categoryName,
        'limit'    => $limit,
        'spent'    => $spent,
        'icon'     => $iconInfo['icon'],
        'color'    => $iconInfo['color'],
    // echo $row['category_name'];

    ];
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ngân sách - Quản lý chi tiêu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<div class="dashboard-container">
    <?php include('sidebar/sidebar.php') ?>

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
                data-category="<?= htmlspecialchars($budget['category']) ?>" 
                data-limit="<?= $budget['limit'] ?>">
                <div class="budget-card-header">
                    <div class="budget-card-icon">
                        <i class='bx <?= htmlspecialchars($budget['icon']) ?>' style="color: <?= htmlspecialchars($budget['color']) ?>;"></i>
                    </div>
                    <h3 class="budget-card-category"><?= htmlspecialchars($budget['category']) ?></h3>
                </div>
                <div class="budget-card-body">
                    <div class="budget-progress-bar">
                        <div class="progress-fill" style="width: <?= min($percentage, 100) ?>%; background-color: <?= htmlspecialchars($budget['color']) ?>;"></div>
                    </div>
                    <div class="budget-details">
                        <p>Đã chi: <span><?= number_format($budget['spent'], 0, ',', '.') ?>đ</span></p>
                        <p>Còn lại: <span><?= number_format($remaining, 0, ',', '.') ?>đ</span></p>
                    </div>
                </div>
                <div class="budget-card-footer">
                    <p>Hạn mức: <?= number_format($budget['limit'], 0, ',', '.') ?>đ</p>
                    
                    <div class="budget-actions">
                        <form action="manage_budget.php" method="POST" style="display:inline;">
                            <input type="number" class="budget-limit-input" name="new_amount" value="<?= $budget['limit'] ?>" style="display:none;   width:80px;">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="category_id" value="<?= htmlspecialchars($budget['id']); 
                            ?>">
                            <button type="submit" class="btn-edit"><i class='bx bx-edit-alt'></i></button>
                            <button type="submit" class="btn-save" style="display:none;"><i class='bx bx-save'></i></button>
                        </form>
                        <form action="manage_budget.php" method="GET" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ngân sách này?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="category_id" value="<?= htmlspecialchars($budget['id']) ?>">
                            <button type="submit" class="btn-delete"><i class='bx bx-trash'></i></button>
                        </form>
                    </div>
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
            <form id="budget-form" action="manage_budget.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group-modal">
                    <label for="budget-category-id">Danh mục</label>
                    <select id="budget-category-id" name="budget_category_id" required>
                        <?php
                        $sql_cate = $conn->prepare("SELECT * FROM categories WHERE user_id = :u_id");
                        $sql_cate->execute(['u_id' =>$_SESSION['user_id']]);
                        $categories = $sql_cate->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?> 
                    </select>
                </div>
                <div class="form-group-modal">
                    <label for="budget-amount">Hạn mức</label>
                    <input type="number" id="budget-amount" name="budget_amount" placeholder="0" required>
                </div>
                <div class="form-group-modal">
                    <label for="start-date">Ngày bắt đầu</label>
                    <input type="date" id="start-date" name="start_date" required>
                </div>
                <div class="form-group-modal">
                    <label for="end-date">Ngày kết thúc</label>
                    <input type="date" id="end-date" name="end_date" required>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancel-budget-btn" class="btn btn-secondary">Hủy</button>
                    <button type="submit" id="save-budget-btn" class="btn btn-primary">Lưu Ngân sách</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== SCRIPT HIỂN THỊ/MODAL ========== -->
<script>
    const addBudgetBtn = document.getElementById('add-budget-btn');
    const budgetModal = document.getElementById('add-budget-modal');
    const closeBudgetModalBtn = document.getElementById('close-budget-modal-btn');
    const cancelBudgetBtn = document.getElementById('cancel-budget-btn');

    const budgetForm = document.getElementById('budget-form');
    const budgetModalTitle = document.getElementById('budget-modal-title');
    const saveBudgetBtn = document.getElementById('save-budget-btn');

    const budgetCategorySelect = document.getElementById('budget-category-id');
    const budgetLimitInput = document.getElementById('budget-amount');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');

    const showBudgetModal = () => {
        budgetForm.reset();
        budgetModal.classList.remove('hidden');
    };

    const hideBudgetModal = () => {
        budgetModal.classList.add('hidden');
    };

    const setupAddModal = () => {
        budgetModalTitle.textContent = 'Tạo Ngân sách mới';
        saveBudgetBtn.textContent = 'Lưu Ngân sách';
        showBudgetModal();
    };

    addBudgetBtn.addEventListener('click', setupAddModal);
    closeBudgetModalBtn.addEventListener('click', hideBudgetModal);
    cancelBudgetBtn.addEventListener('click', hideBudgetModal);
    budgetModal.addEventListener('click', (e) => {
        if (e.target === budgetModal) hideBudgetModal();
    });
    // Phần này xử lý Edit Inline
    // Xử lý Edit Inline
  document.querySelectorAll('.budget-card-footer').forEach(footer => {
    const editBtn = footer.querySelector('.btn-edit');
    const saveBtn = footer.querySelector('.btn-save');
    const limitText = footer.querySelector('p');
    const limitInput = footer.querySelector('.budget-limit-input');

    editBtn.addEventListener('click', function(e) {
      e.preventDefault(); // Chặn submit form ngay khi bấm Edit
      limitText.style.display = 'none';
      limitInput.style.display = 'inline-block';
      editBtn.style.display = 'none';
      saveBtn.style.display = 'inline-block';
      limitInput.focus();
    });
  });
</script>

</body>
</html>

