<?php
session_start();
include('config/config.php');

$user_id     = $_SESSION['user_id'] ?? 2;
$category_id = $_POST['category_id'] ?? null;
$name        = $_POST['name'] ?? '';
$target      = $_POST['target'] ?? 0;
$deadline    = !empty($_POST['deadline']) ? $_POST['deadline'] : NULL;
$icon        = $_POST['icon'] ?? 'bx-target-lock';
$color       = $_POST['color'] ?? '#3498db';

// Kiểm tra category_id có hợp lệ không
if (empty($category_id)) {
    die("Vui lòng chọn danh mục hợp lệ.");
}

// Gọi stored procedure AddGoal
$stmt = $conn->prepare("CALL AddGoal(?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $category_id, $name, $target, $deadline, $icon, $color]);

header("Location: goals.php");
exit;
?>

