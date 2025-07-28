<?php
include('config/config.php');
session_start();

//lấy thông tin user trong db
$stmt= $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id'=> $_SESSION['user_id']]);//thay id bằng $_SESSION['user_id'] hiện tại
$user_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra avatar từ DB
// Nếu người dùng đã có avatar trong DB
if (!empty($user_info['avatar'])) {
    $userAvatar = '/assets/avatars/' . $user_info['avatar'];  // "/" để đi từ gốc web
} else {
    $userAvatar = '/assets/default-avatar.png'; // Ảnh mặc định nếu chưa có
}


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản - Quản lý chi tiêu</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<div class="dashboard-container">
   <?php include('sidebar/sidebar.php');?>

    <!-- ========== MAIN CONTENT ========== -->
    <main class="main-content">
        <header class="main-header">
            <div class="header-left">
                <h1>Thông tin Tài khoản</h1>
                <p class="welcome-message">Cập nhật thông tin cá nhân và cài đặt bảo mật của bạn.</p>
            </div>
        </header>

        <section class="profile-settings-container animated-card">
        
         <!-- Form đổi avatar -->
            <!-- <div class="avatar-section">
                <img src="<?php echo htmlspecialchars($avatarUrl); ?>" alt="Avatar" class="large-avatar">
                <button type="button" class="btn btn-secondary" onclick="openModal()">Thay đổi ảnh</button>
            </div> -->
             <div class="avatar-section">
                <img src="<?php echo htmlspecialchars($userAvatar ?: $defaultAvatar); ?>" alt="Avatar" class="large-avatar">
                <button type="button" class="btn btn-secondary" onclick="openModal()">
                    <?php echo $userAvatar ? 'Thay đổi ảnh' : 'Thêm ảnh'; ?>
                </button>
            </div>

            <!-- Modal Upload Ảnh -->
            <div id="avatarModal" class="modal">
                <div class="modal-content">
                    <h3>Cập nhật ảnh đại diện</h3>
                    
                    <form action="config/avatar.php" method="POST" enctype="multipart/form-data">
                        <!-- Input file ẩn -->
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none;" onchange="previewImage(event)">

                        <!-- Nút chọn ảnh -->
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('avatarInput').click()">Chọn ảnh</button>

                        <!-- Ảnh Preview -->
                        <div class="preview-box">
                            <img id="preview" src="#" alt="Preview" style="display:none; max-width:150px; margin-top:10px; border-radius:8px;">
                        </div>

                        <!-- Nút hành động -->
                        <div class="modal-actions">
                            <button type="submit" class="btn btn-primary">Lưu ảnh</button>
                            <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="profile-form">
                <form action="#" method="POST">
                    <div class="form-group-modal">
                        <label for="full-name">Họ và Tên</label>
                        <input type="text" id="full-name" name="full_name" value="<?php echo htmlspecialchars($user_info['username']); ?>">
                    </div>
                    <div class="form-group-modal">
                        <label for="email">Địa chỉ Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" readonly>
                    </div>
                    <hr class="form-divider">
                    <div class="form-group-modal">
                        <label for="new-password">Mật khẩu mới</label>
                        <input type="password" id="new-password" name="new_password" placeholder="••••••••">
                    </div>
                    <div class="form-group-modal">
                        <label for="confirm-password">Xác nhận mật khẩu mới</label>
                        <input type="password" id="confirm-password" name="confirm_password" placeholder="••••••••">
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

</body>
</html>
<style>
/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    width: 320px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.btn {
    display: inline-block;
    padding: 8px 15px;
    margin: 5px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}
.btn-primary {
    background-color: #4e73df;
    color: white;
}
.btn-secondary {
    background-color: #e0e0e0;
    color: #333;
}
.btn:hover { opacity: 0.9; }
#preview {
   margin: auto;
}

</style>

<script>
function openModal() {
    document.getElementById("avatarModal").style.display = "flex";
}
function closeModal() {
    document.getElementById("avatarModal").style.display = "none";
}
function previewImage(event) {
    const preview = document.getElementById("preview");
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = "block";
}
</script>