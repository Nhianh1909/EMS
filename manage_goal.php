<?php
session_start();
include('config/config.php');

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit;
}

// =================================================================
// HÀNH ĐỘNG 1: THÊM MỘC MỤC TIÊU MỚI (LOGIC MỚI)
// =================================================================
if ($action === 'add') {
    // Không cần category_id từ form nữa
    $name = trim($_POST['name'] ?? '');
    $target = $_POST['target'] ?? 0;
    $moneyIn = $_POST['moneyIn'] ?? 0;
    $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;
    $icon = $_POST['icon'] ?? 'bx-target-lock';
    $color = $_POST['color'] ?? '#3498db';

    if (empty($name) || !is_numeric($target) || $target <= 0) {
        $_SESSION['error_message'] = "Vui lòng điền tên và số tiền mục tiêu hợp lệ.";
    } else {
        // Gọi procedure mới (ít tham số hơn)
        $stmt = $conn->prepare("CALL AddGoal(?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $target, $moneyIn, $deadline, $icon, $color]);
        $_SESSION['success_message'] = "Đã thêm mục tiêu '" . htmlspecialchars($name) . "' thành công!";
    }
}

// =================================================================
// HÀNH ĐỘNG 2: GÓP TIỀN VÀO MỤC TIÊU (LOGIC MỚI)
// =================================================================
elseif ($action === 'contribute') {
    $goal_id = $_POST['goal_id'] ?? null;
    $amount = $_POST['amount'] ?? 0;

    if (empty($goal_id) || !is_numeric($amount) || $amount <= 0) {
        $_SESSION['error_message'] = "Dữ liệu góp tiền không hợp lệ.";
    } else {
        try {
            $conn->beginTransaction();

            // 1. Lấy tên của mục tiêu từ goal_id
            $stmtGoalName = $conn->prepare("SELECT name FROM goals WHERE id = ? AND user_id = ?");
            $stmtGoalName->execute([$goal_id, $user_id]);
            $goal = $stmtGoalName->fetch(PDO::FETCH_ASSOC);
            
            if (!$goal) {
                throw new Exception("Không tìm thấy mục tiêu.");
            }
            $goalName = $goal['name'];

            // 2. Tìm danh mục CHI TIÊU có cùng tên với mục tiêu
            $stmtCategory = $conn->prepare("SELECT id FROM categories WHERE user_id = ? AND name = ? AND type = 'expense'");
            $stmtCategory->execute([$user_id, $goalName]);
            $savingCategory = $stmtCategory->fetch(PDO::FETCH_ASSOC);

            if (!$savingCategory) {
                throw new Exception("Lỗi không tìm thấy danh mục chi tiêu tương ứng với mục tiêu. Vui lòng thử xóa và tạo lại mục tiêu.");
            }
            $savingCategoryId = $savingCategory['id'];

            // 3. Cập nhật số tiền đã tiết kiệm
            $stmtUpdateGoal = $conn->prepare("UPDATE goals SET saved = saved + ? WHERE id = ? AND user_id = ?");
            $stmtUpdateGoal->execute([$amount, $goal_id, $user_id]);

            // 4. Tạo giao dịch chi tiêu mới
            $description = "Góp tiền cho mục tiêu: " . $goalName;
            $stmtCreateTransaction = $conn->prepare(
                "INSERT INTO transactions (user_id, category_id, amount, description, transaction_date) VALUES (?, ?, ?, ?, NOW())"
            );
            $stmtCreateTransaction->execute([$user_id, $savingCategoryId, $amount, $description]);

            $conn->commit();
            $_SESSION['success_message'] = "Góp " . number_format($amount) . "đ vào mục tiêu '" . htmlspecialchars($goalName) . "' thành công!";

        } catch (Exception $e) {
            $conn->rollBack();
            $_SESSION['error_message'] = "Lỗi: " . $e->getMessage();
        }
    }
}

// =================================================================
// HÀNH ĐỘNG 3: XÓA MỘT MỤC TIÊU
// =================================================================
elseif ($action === 'delete') {
    $goal_id = $_POST['goal_id'] ?? null;

    if (empty($goal_id)) {
        $_SESSION['error_message'] = "Không tìm thấy mục tiêu để xóa.";
    } else {
        $stmt = $conn->prepare("DELETE FROM goals WHERE id = ? AND user_id = ?");
        $stmt->execute([$goal_id, $user_id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = "Đã xóa mục tiêu thành công.";
        } else {
            $_SESSION['error_message'] = "Không thể xóa mục tiêu hoặc bạn không có quyền.";
        }
    }
}

else {
    $_SESSION['error_message'] = "Hành động không xác định.";
}

header("Location: goals.php");
exit;
?>