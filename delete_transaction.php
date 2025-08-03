<?php
include('config/config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = $_POST['id'] ?? null;
    $user_id = $_SESSION['user_id'];

    if ($transaction_id) {
  
        $stmt = $conn->prepare("DELETE FROM transactions WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $transaction_id, 'user_id' => $user_id]);

        if ($stmt->rowCount()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy giao dịch cần xóa']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu ID giao dịch']);
    }
    exit();
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
    exit();
}
?>