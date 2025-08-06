<?php
session_start();
include('config/config.php');
// --- MÔ PHỎNG DỮ LIỆU ĐỘNG ---
$userName = trim(" Khánh");
$userEmail = "duykhanh@gmail.com";
$userAvatar = "https://scontent.fsgn21-1.fna.fbcdn.net/v/t39.30808-6/474189628_1310277973503688_3036333816967852750_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_ohc=hMqcP2MP3hMQ7kNvwEysxR9&_nc_oc=AdmRy2aZYjM-Q9iwxXPO7EnyU9y8I4N4-r31oBvb1bv3Yc0YYB1M-VXyn56PtGCFM2AaUSbYKZBD2yLKF6QUMmWa&_nc_zt=23&_nc_ht=scontent.fsgn21-1.fna&_nc_gid=2VfzLP0HwXXmY6gmfFxWqw&oh=00_AfRS2AS7qmPGz9dSe3jvA1Tx5pWDZOF9KwbBA6Q7rky9vg&oe=687D3C9E";

// Dữ liệu mẫu cho các mục tiêu tiết kiệm
$goals = [
    ['name' => 'Mua Macbook Pro M4', 'target' => 60000000, 'saved' => 45000000, 'icon' => 'bxl-apple', 'color' => '#1a202c'],
    ['name' => 'Du lịch Nhật Bản', 'target' => 40000000, 'saved' => 15000000, 'icon' => 'bxs-plane-alt', 'color' => '#ef4444'],
    ['name' => 'Quỹ khẩn cấp', 'target' => 30000000, 'saved' => 30000000, 'icon' => 'bxs-shield-plus', 'color' => '#22c55e'],
];

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
                <button class="action-button primary-btn"><i class='bx bx-plus'></i> <span>Thêm mục tiêu mới</span></button>
            </div>
        </header>

        <section class="goals-grid">
            <?php foreach ($goals as $goal): 
                $percentage = ($goal['saved'] / $goal['target']) * 100;
            ?>
            <div class="goal-card animated-card">
                <div class="goal-card-header">
                    <div class="goal-card-icon">
                        <i class='bx <?php echo htmlspecialchars($goal['icon']); ?>' style="color: <?php echo htmlspecialchars($goal['color']); ?>;"></i>
                    </div>
                    <h3 class="goal-card-name"><?php echo htmlspecialchars($goal['name']); ?></h3>
                </div>
                <div class="goal-card-body">
                    <div class="goal-progress-bar">
                        <div class="progress-fill" style="width: <?php echo min($percentage, 100); ?>%; background-color: <?php echo htmlspecialchars($goal['color']); ?>;"></div>
                    </div>
                    <div class="goal-details">
                        <p>Đã tiết kiệm</p>
                        <span><?php echo number_format($goal['saved'], 0, ',', '.'); ?>đ</span>
                    </div>
                </div>
                <div class="goal-card-footer">
                    <span>Mục tiêu: <?php echo number_format($goal['target'], 0, ',', '.'); ?>đ</span>
                    <span><?php echo round($percentage); ?>%</span>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
    </main>
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
