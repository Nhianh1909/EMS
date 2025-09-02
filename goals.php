    <?php
    session_start();
    include('config/config.php');

    // Giả sử user_id luôn có trong session, nếu không có thể gán mặc định để test
    if (!isset($_SESSION['user_id'])) {
        // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
        // header('Location: login.php');
        // exit();
        $_SESSION['user_id'] = 2; // Tạm thời gán user_id để test
    }
    $userId = $_SESSION['user_id'];


    // Lấy danh sách mục tiêu từ CSDL
    $sql = "
        SELECT 
            id,
            name,
            target,
            saved,
            deadline,
            icon,
            color
        FROM goals
        WHERE user_id = ?
        ORDER BY created_at DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);
    $goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lấy danh sách category của user (giữ nguyên)
    $stmt = $conn->prepare("SELECT id, name FROM categories WHERE user_id = ? AND is_deleted = 0 AND type = 'income'");
    $stmt->execute([$userId]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- LẤY DÒNG TIỀN DƯ TỪ monthly_surplus ---
$currentMonth = date('Y-m');

$stmt = $conn->prepare("
    SELECT surplus 
    FROM monthly_surplus 
    WHERE user_id = ? AND month = ?
");
$stmt->execute([$userId, $currentMonth]);
$monthlySurplus = $stmt->fetchColumn();

if ($monthlySurplus === false) {
    // Nếu chưa có bản ghi => tạo mới bằng cách tính theo logic cũ (chỉ 1 lần duy nhất)
    $stmtIncome = $conn->prepare("
        SELECT COALESCE(SUM(amount), 0) 
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = ? AND c.type = 'income' 
          AND DATE_FORMAT(t.transaction_date, '%Y-%m') = ?
    ");
    $stmtIncome->execute([$userId, $currentMonth]);
    $totalIncome = $stmtIncome->fetchColumn();

    $stmtExpense = $conn->prepare("
        SELECT COALESCE(SUM(amount), 0) 
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = ? AND c.type = 'expense' 
          AND DATE_FORMAT(t.transaction_date, '%Y-%m') = ?
    ");
    $stmtExpense->execute([$userId, $currentMonth]);
    $totalExpense = $stmtExpense->fetchColumn();

    $monthlySurplus = $totalIncome - $totalExpense;

    // Lưu lại để những lần sau chỉ lấy ra
    $stmtInsert = $conn->prepare("
        INSERT INTO monthly_surplus (user_id, month, surplus) 
        VALUES (?, ?, ?)
    ");
    $stmtInsert->execute([$userId, $currentMonth, $monthlySurplus]);
}

    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mục tiêu Tiết kiệm - Quản lý chi tiêu</title>
        <link rel="stylesheet" href="css/dashboard_style.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== Modal Style ===== */
        .modal { position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; padding: 20px; }
        .modal-content { background: var(--card-bg, #fff); padding: 25px 30px; border-radius: 12px; width: 100%; max-width: 450px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); animation: fadeIn 0.25s ease-in-out; font-family: 'Poppins', sans-serif; }
        .modal-content h2 { margin-bottom: 20px; font-weight: 600; color: var(--text-color, #333); text-align: center; }
        .modal-content label { display: block; margin-top: 12px; font-weight: 500; font-size: 14px; color: var(--text-color, #333); }
        .modal-content input, .modal-content select { font-size: 15px; padding: 12px 14px; margin-top: 5px; border-radius: 8px; border: 1px solid #ddd; outline: none; width: 100%; background: #f9f9f9; transition: border-color 0.2s ease; }
        .modal-content input:focus, .modal-content select:focus { border-color: #3498db; }
        .primary-btn-a { display: inline-block; background: var(--primary-color, #3498db); color: #fff; border: none; padding: 10px 15px; border-radius: 8px; margin-top: 18px; width: 100%; font-size: 15px; cursor: pointer; font-weight: 500; transition: background 0.2s ease; }
        .primary-btn-a:hover { background: #2980b9; }
        .close-btn { float: right; font-size: 22px; cursor: pointer; color: #888; }
        .close-btn:hover { color: #000; }
        @keyframes fadeIn { from {opacity: 0; transform: scale(0.9);} to {opacity: 1; transform: scale(1);} }

        /* === CSS CHO CÁC THÀNH PHẦN MỚI === */
        .main-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .header-center { flex-grow: 1; display: flex; justify-content: center; padding: 0 20px; }
        .surplus-card { display: flex; align-items: center; background: var(--card-bg); padding: 12px 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid var(--border-color); }
        .surplus-card i { font-size: 28px; color: var(--primary-color); margin-right: 15px; }
        .surplus-info { display: flex; flex-direction: column; }
        .surplus-info span { font-size: 13px; color: var(--text-color-light); }
        .surplus-info strong { font-size: 18px; font-weight: 600; }
        .surplus-info strong.positive { color: #27ae60; }
        .surplus-info strong.negative { color: #e74c3c; }
        
        .goal-card-header { position: relative; }
        .delete-btn { position: absolute; top: 10px; right: 10px; background: transparent; border: none; color: #e74c3c; font-size: 20px; cursor: pointer; opacity: 0.5; transition: opacity 0.2s ease; }
        .delete-btn:hover { opacity: 1; }
        
        .contribute-btn { background: #e9f5ff; color: #3498db; border: none; padding: 6px 12px; border-radius: 20px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s ease; }
        .contribute-btn:hover { background: #cce7ff; color: #2980b9; }
        .goal-card-footer { justify-content: space-between; }
        
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 8px; font-weight: 500; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
    </style>
    </head>
    <body>
    <div class="dashboard-container">
        <?php include('sidebar/sidebar.php')?>
        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <h1>Mục tiêu Tiết kiệm</h1>
                    <p class="welcome-message">Đặt mục tiêu và theo dõi tiến độ để biến ước mơ thành hiện thực.</p>
                </div>
                <div class="header-center">
                    <div class="surplus-card">
                        <i class='bx bx-wallet'></i>
                        <div class="surplus-info">
                            <span>Dòng tiền dư tháng này</span>
                            <strong class="<?= $monthlySurplus >= 0 ? 'positive' : 'negative' ?>">
                                <?= number_format($monthlySurplus, 0, ',', '.') ?>đ
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="header-right">
                    <button id="openModalBtn" class="action-button primary-btn"><i class='bx bx-plus'></i> <span>Thêm mục tiêu mới</span></button>
                </div>
            </header>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success_message']; ?></div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error_message']; ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <section class="goals-grid">
                <?php if (empty($goals)): ?>
                    <p>Bạn chưa có mục tiêu nào. Hãy tạo một mục tiêu mới!</p>
                <?php else: ?>
                    <?php foreach ($goals as $goal): 
                        $percentage = ($goal['target'] > 0) ? ($goal['saved'] / $goal['target']) * 100 : 0;
                    ?>
                    <div class="goal-card animated-card">
                        <div class="goal-card-header">
                            <div class="goal-card-icon">
                                <i class='bx <?= htmlspecialchars($goal['icon']) ?>' style="color: <?= htmlspecialchars($goal['color']) ?>;"></i>
                            </div>
                            <h3 class="goal-card-name"><?= htmlspecialchars($goal['name']) ?></h3>
                            <form method="POST" action="manage_goal.php" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mục tiêu này không?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="goal_id" value="<?= $goal['id'] ?>">
                                <button type="submit" class="delete-btn" title="Xóa mục tiêu"><i class='bx bx-trash'></i></button>
                            </form>
                        </div>
                        <div class="goal-card-body">
                            <div class="goal-progress-bar">
                                <div class="progress-fill" style="width: <?= min($percentage, 100) ?>%; background-color: <?= htmlspecialchars($goal['color']) ?>;"></div>
                            </div>
                            <div class="goal-details">
                                <p>Đã tiết kiệm</p>
                                <span><?= number_format($goal['saved'], 0, ',', '.') ?>đ</span>
                            </div>
                        </div>
                        <div class="goal-card-footer">
                            <span>Mục tiêu: <?= number_format($goal['target'], 0, ',', '.') ?>đ</span>
                            <button class="contribute-btn" data-goal-id="<?= $goal['id'] ?>" data-goal-name="<?= htmlspecialchars($goal['name']) ?>">
                                <i class='bx bx-plus-circle'></i> Góp tiền
                            </button>
                            <span><?= round($percentage) ?>%</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>

        <div id="goalModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span id="closeModalBtn" class="close-btn">&times;</span>
            <h2>🎯 Thêm mục tiêu mới</h2>
            <form action="manage_goal.php" method="POST">
                <input type="hidden" name="action" value="add">
                <label for="name">Tên mục tiêu</label>
                <input type="text" name="name" id="name" placeholder="Ví dụ: Mua xe máy" required>

                <label for="target">Số tiền mục tiêu</label>
                <input type="number" name="target" id="target" placeholder="Nhập số tiền" required>
                
                
                <label for="moneyIn">Số tiền góp vào ban đầu</label>
                <input type="number" name="moneyIn" id="moneyIn" placeholder="Nhập số tiền (có thể để 0)" value="0" required>

                <label for="deadline">Hạn hoàn thành</label>
                <input type="date" name="deadline" id="deadline">

                <input type="hidden" name="icon" value="bx-target-lock">
                <input type="hidden" name="color" value="#3498db">

                <button type="submit" class="primary-btn-a"><i class='bx bx-save'></i> Lưu mục tiêu</button>
            </form>
        </div>
    </div>          

        <div id="contributeModal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2>💰 Góp tiền vào mục tiêu</h2>
                <h3 id="contributeGoalName" style="text-align: center; margin-top: -15px; margin-bottom: 20px; font-weight: 500; color: #555;"></h3>
                <form action="manage_goal.php" method="POST">
                    <input type="hidden" name="action" value="contribute">
                    <input type="hidden" name="goal_id" id="contributeGoalId">
                    <label for="amount">Số tiền muốn góp</label>
                    <input type="number" name="amount" id="amount" placeholder="Nhập số tiền" required>
                    <button type="submit" class="primary-btn-a"><i class='bx bx-send'></i> Xác nhận góp tiền</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // ===== Modal Thêm mục tiêu Open/Close =====
        const goalModal = document.getElementById('goalModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        if(openModalBtn) { openModalBtn.addEventListener('click', () => { goalModal.style.display = 'flex'; }); }
        if(closeModalBtn) { closeModalBtn.addEventListener('click', () => { goalModal.style.display = 'none'; }); }

        // ===== Modal Góp tiền Open/Close =====
        const contributeModal = document.getElementById('contributeModal');
        const contributeGoalIdInput = document.getElementById('contributeGoalId');
        const contributeGoalNameEl = document.getElementById('contributeGoalName');
        const closeContributeModalBtn = contributeModal.querySelector('.close-btn');

        document.querySelectorAll('.contribute-btn').forEach(button => {
            button.addEventListener('click', () => {
                contributeGoalIdInput.value = button.dataset.goalId;
                contributeGoalNameEl.textContent = button.dataset.goalName;
                contributeModal.style.display = 'flex';
            });
        });
        if(closeContributeModalBtn) { closeContributeModalBtn.addEventListener('click', () => { contributeModal.style.display = 'none'; }); }
        
        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target == goalModal) { goalModal.style.display = 'none'; }
            if (e.target == contributeModal) { contributeModal.style.display = 'none'; }
        });
    });
    </script>
    </body>
    </html>