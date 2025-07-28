<?php
session_start();
include('config.php'); // $conn là PDO

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Kiểm tra có file upload không
if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] != UPLOAD_ERR_OK) {
    die("Không có file được chọn hoặc upload lỗi.");
}

// Tạo folder lưu ảnh nếu chưa có
$uploadDir = dirname(__DIR__) . "/assets/avatars/";//đi từ thư mục gốc
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Lấy ảnh cũ từ DB
$stmt = $conn->prepare("SELECT avatar FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$oldAvatar = $user ? $user['avatar'] : null;

// Lấy tên gốc và loại bỏ ký tự đặc biệt (dấu, khoảng trắng)
$originalName = basename($_FILES['avatar']['name']);
$cleanName = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName); // thay ký tự lạ bằng "_"

// Tạo tên file duy nhất
$fileName = uniqid() . "_" . $cleanName;

$targetPath = $uploadDir . $fileName;

// Kiểm tra định dạng
$allowed = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($_FILES['avatar']['type'], $allowed)) {
    die("Chỉ cho phép JPG, PNG.");
}

// Upload file
if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
    // Xóa ảnh cũ nếu có
    if (!empty($oldAvatar) && file_exists($uploadDir . $oldAvatar)) {
        unlink($uploadDir . $oldAvatar);
    }

    // Update DB với avatar mới
    $stmt = $conn->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
    $stmt->execute([
        'avatar' => $fileName,
        'id' => $userId
    ]);

    header("Location: /accounts.php?success=1");
    exit();
} else {
    die("Upload ảnh thất bại.");
}
