<?php
session_start();
include('config/config.php');

$userId = $_SESSION['user_id'];

$sql = "
    SELECT 
        g.id,
        g.name,
        g.target,
        g.deadline,
        g.icon,
        g.color,
        COALESCE(SUM(t.amount), 0) AS saved
    FROM goals g
    LEFT JOIN transactions t 
        ON g.user_id = t.user_id
        AND g.category_id = t.category_id
        AND t.amount > 0  -- chỉ tính thu nhập hoặc tiền gửi vào mục tiêu
    WHERE g.user_id = ?
    GROUP BY g.id, g.name, g.target, g.deadline, g.icon, g.color
";

$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$goals = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Lấy danh sách category của user
$stmt = $conn->prepare("SELECT id, name FROM categories WHERE user_id = ? AND is_deleted = 0");
$stmt->execute([$userId]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mục tiêu Tiết kiệm - Quản lý chi tiêu</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    
  <style>
/* ===== Modal Style ===== */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
}
.modal-content {
    background: var(--card-bg, #fff);
    padding: 25px 30px;
    border-radius: 12px;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    animation: fadeIn 0.25s ease-in-out;
    font-family: 'Poppins', sans-serif;
}
.modal-content h2 {
    margin-bottom: 20px;
    font-weight: 600;
    color: var(--text-color, #333);
    text-align: center;
}
.modal-content label {
    display: block;
    margin-top: 12px;
    font-weight: 500;
    font-size: 14px;
    color: var(--text-color, #333);
}
.modal-content input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: border-color 0.2s ease;
}
.modal-content input:focus {
    border-color: var(--primary-color, #3498db);
    outline: none;
}
.primary-btn-a {
    display: inline-block;
    background: var(--primary-color, #3498db);
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 8px;
    margin-top: 18px;
    width: 100%;
    font-size: 15px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s ease;
}
.primary-btn:hover {
    background: #2980b9;
}
.close-btn {
    float: right;
    font-size: 22px;
    cursor: pointer;
    color: #888;
}
.close-btn:hover {
    color: #000;
}


.modal-content input,
.modal-content select {
    font-size: 15px;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid #ddd;
    outline: none;
    width: 100%;
    background: #f9f9f9;
    transition: border-color 0.2s ease;
}

.modal-content input:focus,
.modal-content select:focus {
    border-color: #3498db;
}

.modal-content select {
    appearance: none;
    background: #f9f9f9 url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%22292%22%20height%3D%22292%22%20viewBox%3D%220%200%20292%20292%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-24.9%200L146%20185.6%2030%2069.4A17.6%2017.6%200%200%200%205.1%2094.3l123%20123a17.6%2017.6%200%200%200%2024.9%200l123-123a17.6%2017.6%200%200%200%200-24.9z%22%20fill%3D%22%233498db%22/%3E%3C/svg%3E") no-repeat right 14px center;
    background-size: 12px;
    cursor: pointer;
}
@keyframes fadeIn {
    from {opacity: 0; transform: scale(0.9);}
    to {opacity: 1; transform: scale(1);}
}
</style>

</head>
<body>

<div class="dashboard-container">
    <!-- ========== SIDEBAR ========== -->
    <?php include('sidebar/sidebar.php')?>


    <!-- ========== MAIN CONTENT ========== -->
    <main class="main-content">
        <header class="main-header">
            <div class="header-left">
                <h1>Mục tiêu Tiết kiệm</h1>
                <p class="welcome-message">Đặt mục tiêu và theo dõi tiến độ để biến ước mơ thành hiện thực.</p>
            </div>
             <div class="header-right">
                <button id="openModalBtn" class="action-button primary-btn"><i class='bx bx-plus'></i> <span>Thêm mục tiêu mới</span></button>
            </div>
        </header>

        <section class="goals-grid">
            <?php foreach ($goals as $goal): 
                $percentage = ($goal['saved'] / $goal['target']) * 100;
            ?>
            <div class="goal-card animated-card">
                <div class="goal-card-header">
                    <div class="goal-card-icon">
                        <i class='bx <?= htmlspecialchars($goal['icon']) ?>' style="color: <?= htmlspecialchars($goal['color']) ?>;"></i>
                    </div>
                    <h3 class="goal-card-name"><?= htmlspecialchars($goal['name']) ?></h3>
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
                    <span><?= round($percentage) ?>%</span>
                </div>
            </div>
            <?php endforeach; ?>
        </section>

    </main>


  <!-- Modal Thêm Mục Tiêu -->
<div id="goalModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span id="closeModalBtn" class="close-btn">&times;</span>
        <h2>🎯 Thêm mục tiêu mới</h2>
        <form action="manage_goal.php" method="POST">
            <label for="name">Tên mục tiêu</label>
            <input type="text" name="name" id="name" placeholder="Ví dụ: Mua xe máy" required>

            <label for="category_id">Danh mục</label>
                <select name="category_id" id="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cate): ?>
                        <option value="<?= $cate['id'] ?>">
                            <?= htmlspecialchars($cate['name']) ?> 
                        </option>
                    <?php endforeach; ?>
                </select>
            <label for="target">Số tiền mục tiêu</label>
            <input type="number" name="target" id="target" placeholder="Nhập số tiền" required>

            <label for="deadline">Hạn hoàn thành</label>
            <input type="date" name="deadline" id="deadline">

            <!-- Icon & màu mặc định -->
            <input type="hidden" name="icon" value="bx-target-lock">
            <input type="hidden" name="color" value="#3498db">

            <button type="submit" class="primary-btn-a"><i class='bx bx-save'></i> Lưu mục tiêu</button>
        </form>
    </div>
</div>

</div>





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
<script>
// ===== Modal Open/Close =====
document.getElementById('openModalBtn').addEventListener('click', () => {
    document.getElementById('goalModal').style.display = 'flex';
});
document.getElementById('closeModalBtn').addEventListener('click', () => {
    document.getElementById('goalModal').style.display = 'none';
});
window.addEventListener('click', (e) => {
    if (e.target == document.getElementById('goalModal')) {
        document.getElementById('goalModal').style.display = 'none';
    }
});
</script>
