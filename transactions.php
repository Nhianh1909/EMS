<?php
include('config/config.php');
session_start();

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}

$sql_transaction = $conn->prepare("
    SELECT t.id, t.description, t.amount, t.transaction_date, c.name as category, c.type
    FROM transactions t
    JOIN categories c on t.category_id = c.id
    WHERE t.user_id = :id AND c.user_id = :id
    ORDER BY t.transaction_date DESC
");
$sql_transaction->execute(['id'=> $_SESSION['user_id']]);
$transactions = $sql_transaction->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($transactions);
// echo '</pre>';
// exit;



// Lấy các giá trị bộ lọc từ URL, nếu không có thì dùng giá trị mặc định
$selectedDay = $_GET['day'] ?? date('d');
$selectedMonth = $_GET['month'] ?? date('m');
$selectedYear = $_GET['year'] ?? date('Y');
$selectedType = $_GET['type'] ?? 'all';


// --- LOGIC LỌC GIAO DỊCH NÂNG CAO ---
$filtered_transactions = $transactions;

// 1. Lọc theo Năm
$filtered_transactions = array_filter($filtered_transactions, function($transaction) use ($selectedYear) {
    return date('Y', strtotime($transaction['transaction_date'])) == $selectedYear;
});
//strtotime($transaction['transaction_date']) -> chuyển chuỗi ngày thành timestamp, timestamp là số giây từ 1970 đến thời điểm hiện tại
//date('Y', strtotime($transaction['transaction_date'])) -> lấy năm của timestamp, nếu năm lọc == Y thì lọc theo đk. Bên dưới cũng xử lí như v

// 2. Lọc theo Tháng
$filtered_transactions = array_filter($filtered_transactions, function($transaction) use ($selectedMonth) {
    return date('n', strtotime($transaction['transaction_date'])) == (int)$selectedMonth;
});

// 3. Lọc theo Ngày (nếu không phải "all")
if ($selectedDay !== 'all') {
    $filtered_transactions = array_filter($filtered_transactions, function($transaction) use ($selectedDay) {
        return date('j', strtotime($transaction['transaction_date'])) == (int)$selectedDay;
    });
}

// 4. Lọc theo Loại (income / expense)
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
    <?php include('sidebar/sidebar.php'); ?>

    <!-- ========== MAIN CONTENT ========== -->
    <main class="main-content">
        <header class="main-header">
            <div class="header-left">
                <h1>Quản lý Giao dịch</h1>
                <p class="welcome-message">Xem và quản lý tất cả giao dịch của bạn tại đây.</p>
            </div>
             <div class="header-end">
                <div class="header-left">
                    <button id="handle-category-btn" class="action-button primary-btn"><i class='bx bx-plus'></i> <span>Điều chỉnh danh mục</span></button>
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
                                data-date="<?php echo date('Y-m-d', strtotime($t['transaction_date'])); ?>">
                                <td><?php echo date('d/m/Y', strtotime($t['transaction_date'])); ?></td>
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
            <form id="transaction-form" action="add_transaction.php" method="POST">
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
                        <?php
                        $sql_getCat = $conn->prepare("SELECT * FROM categories WHERE user_id = :u_id");
                        $sql_getCat->execute(['u_id'=>$_SESSION['user_id']]);
                        $categories = $sql_getCat->fetchAll(PDO::FETCH_ASSOC);
                        foreach($categories as $cat){
                            echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                        }
                        ?>
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
    <!-- ========== MODAL ĐIỀU CHỈNH DANH MỤC ========== -->
<div id="manage-category-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
        <h2>Điều chỉnh Danh mục</h2>
        <button id="close-category-modal-btn" class="close-button">&times;</button>
        </div>
        <div class="modal-body">
        <!-- Bạn có thể đặt form thêm mới danh mục và danh sách danh mục hiện tại ở đây -->
        <form id="category-form" action="manage_category.php" method="POST">
            <input type="hidden" name="category_id" id="edit-category-id">
            <div class="form-group-modal">
                <label for="category-name">Tên Danh mục</label>
                <input type="text" id="category-name" name="category_name" placeholder="Nhập tên danh mục" required>
            </div>
            <div class="form-group-modal">
                <label for="category-type">Loại</label>
                <select id="category-type" name="category_type">
                <option value="income">Thu nhập</option>
                <option value="expense">Chi tiêu</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancel-category-btn" class="btn btn-secondary">Hủy</button>
                <button type="button" id="back-to-add-btn" class="btn btn-secondary hidden">Quay lại Thêm</button>
                <button type="submit" class="btn btn-primary">Thêm Danh mục</button>
            </div>
        </form>


        <div id="category-list" style="margin-top: 20px; max-height: 300px; overflow-y: auto;">
            <h3>Danh sách Danh mục</h3>
            <ul style="list-style: none; padding: 0;">
                <?php
                // $sql_getCat = $conn->prepare("SELECT * FROM categories WHERE user_id = :u_id");
                // $sql_getCat->execute(['u_id' => $_SESSION['user_id']]);
                // $categories = $sql_getCat->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $cat) {
                    $icon = '';
                    if ($cat['type'] === 'income') $icon = '💰';
                    if ($cat['type'] === 'expense') $icon = '💸';

                    $nameLower = strtolower($cat['name']);
                    if (str_contains($nameLower, 'ăn') || str_contains($nameLower, 'food')) $icon = '🍔';
                    elseif (str_contains($nameLower, 'xe') || str_contains($nameLower, 'transport')) $icon = '🚗';
                    elseif (str_contains($nameLower, 'nhà') || str_contains($nameLower, 'rent')) $icon = '🏠';

                    echo "
                        <li style='
                                margin: 8px 0;
                                display: flex;
                                align-items: center;
                                justify-content: space-between;'
                                data-id='{$cat['id']}'
                                data-name='" . htmlspecialchars($cat['name']) . "'
                                data-type='{$cat['type']}'>
                            <div style='display: flex; align-items: center;'>
                                <span style='margin-right: 8px;'>$icon</span>
                                <span>{$cat['name']} ({$cat['type']})</span>
                            </div>
                            <div>
                                <button type='button' class='btn-edit-category btn btn-small'>Sửa</button>
                                <a href='manage_category.php?action=delete&id={$cat['id']}'
                                class='btn btn-danger btn-small'
                                onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\");'>
                                Xóa
                                </a>
                            </div>
                        </li>
                        ";

                }
                ?>
            </ul>
        </div>
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
/* ===========================================
   MODAL XỬ LÝ GIAO DỊCH
=========================================== */

// Lấy các phần tử modal Giao dịch
const addTransactionBtn = document.getElementById('add-transaction-btn');
const transactionTableBody = document.getElementById('transaction-table-body');
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
let rowToDelete = null; // Lưu hàng cần xóa

// --- Hàm dùng chung ---
const showModal = (modalElement) => modalElement.classList.remove('hidden');
const hideModal = (modalElement) => modalElement.classList.add('hidden');

// --- Hàm Thêm/Sửa Giao dịch ---
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

// --- Xử lý Xóa Giao dịch ---
closeDeleteModalBtn.addEventListener('click', () => hideModal(deleteModal));
cancelDeleteBtn.addEventListener('click', () => hideModal(deleteModal));
deleteModal.addEventListener('click', (e) => {
  if (e.target === deleteModal) hideModal(deleteModal);
});
confirmDeleteBtn.addEventListener('click', () => {
  if (rowToDelete) {
    rowToDelete.remove();
    hideModal(deleteModal);
    rowToDelete = null;
  }
});

// --- Click trên bảng Giao dịch ---
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
    rowToDelete = deleteBtn.closest('tr');
    showModal(deleteModal);
  }
});


/* ===========================================
   MODAL QUẢN LÝ DANH MỤC
=========================================== */

// Các phần tử modal Danh mục
const handleCategoryBtn = document.getElementById('handle-category-btn');
const manageCategoryModal = document.getElementById('manage-category-modal');
const closeCategoryModalBtn = document.getElementById('close-category-modal-btn');
const cancelCategoryBtn = document.getElementById('cancel-category-btn');

const categoryList = document.getElementById('category-list');
const categoryForm = document.getElementById('category-form');
const nameInput = document.getElementById('category-name');
const typeSelect = document.getElementById('category-type');
const hiddenId = document.getElementById('edit-category-id');
const submitBtn = categoryForm.querySelector('button[type="submit"]');
const backToAddBtn = document.getElementById('back-to-add-btn');

// Mở modal Quản lý Danh mục
handleCategoryBtn.addEventListener('click', () => {
  // 1. Ẩn modal trước nếu cần
  // 2. Reset form
  categoryForm.reset();

  // 3. Xóa ID ẩn + text button
  hiddenId.value = '';
  submitBtn.textContent = 'Thêm Danh mục';

  // 4. LUÔN ẨN nút Quay lại Thêm
  backToAddBtn.classList.add('hidden');

  // 5. Hiện modal
  manageCategoryModal.classList.remove('hidden');
});


// Đóng modal Quản lý Danh mục
closeCategoryModalBtn.addEventListener('click', () => {
  manageCategoryModal.classList.add('hidden');
});
cancelCategoryBtn.addEventListener('click', () => {
  manageCategoryModal.classList.add('hidden');
});

// Click ra ngoài để đóng modal
manageCategoryModal.addEventListener('click', (e) => {
  if (e.target === manageCategoryModal) {
    manageCategoryModal.classList.add('hidden');
  }
});

// Bấm SỬA Danh mục
categoryList.addEventListener('click', (e) => {
  const editBtn = e.target.closest('.btn-edit-category');

  if (editBtn) {
    const li = editBtn.closest('li');
    const id = li.dataset.id;
    const name = li.dataset.name;
    const type = li.dataset.type;

    nameInput.value = name;
    typeSelect.value = type;
    hiddenId.value = id;

    submitBtn.textContent = 'Cập nhật Danh mục';
    backToAddBtn.classList.remove('hidden');
  }
});

// Bấm QUAY LẠI THÊM Danh mục
backToAddBtn.addEventListener('click', () => {
  categoryForm.reset();
  hiddenId.value = '';
  submitBtn.textContent = 'Thêm Danh mục';
  backToAddBtn.classList.add('hidden');
});
console.log('Quay lại trước:', backToAddBtn.classList);
backToAddBtn.classList.add('hidden');
console.log('Quay lại sau:', backToAddBtn.classList);

</script>

</body>
</html>
