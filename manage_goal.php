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
    $category_id = $_POST['category_id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $target = $_POST['target'] ?? 0;
    $moneyIn = $_POST['moneyIn'] ?? 0;
    $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;
    $icon = $_POST['icon'] ?? 'bx-target-lock';
    $color = $_POST['color'] ?? '#3498db';

    if (empty($name) || !is_numeric($target) || $target <= 0) {
        $_SESSION['error_message'] = "Vui lòng điền tên và số tiền mục tiêu hợp lệ.";
    } else {
        $stmt = $conn->prepare("INSERT INTO goals (user_id, name, target, moneyIn, deadline, icon, color) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([$user_id, $name, $target, $moneyIn, $deadline, $icon, $color]);
        $_SESSION['success_message'] = "Đã thêm mục tiêu '" . htmlspecialchars($name) . "' thành công!";
    }
}

// =================================================================
// HÀNH ĐỘNG 2: GÓP TIỀN VÀO MỤC TIÊU (LOGIC MỚI - ĐÃ SỬA)
// =================================================================
// =================================================================
// HÀNH ĐỘNG 2: GÓP TIỀN VÀO MỤC TIÊU (LOGIC MỚI - DÙNG monthly_surplus)
// =================================================================
elseif ($action === 'contribute') {
    $goal_id = $_POST['goal_id'] ?? null;
    $amount = $_POST['amount'] ?? 0;

    if (empty($goal_id) || !is_numeric($amount) || $amount <= 0) {
        $_SESSION['error_message'] = "Dữ liệu góp tiền không hợp lệ.";
    } else {
        try {
            $conn->beginTransaction();

            $currentMonth = date('Y-m');

            // Lấy dòng tiền dư hiện tại từ bảng monthly_surplus
            $stmt = $conn->prepare("
                SELECT surplus 
                FROM monthly_surplus 
                WHERE user_id = ? AND month = ?
            ");
            $stmt->execute([$user_id, $currentMonth]);
            $monthlySurplus = $stmt->fetchColumn();

            if ($monthlySurplus === false) {
                throw new Exception("Chưa có dữ liệu dòng tiền dư cho tháng này.");
            }

            if ($amount > $monthlySurplus) {
                throw new Exception("Không đủ dòng tiền dư để góp.");
            }

            // Cập nhật số tiền đã tiết kiệm cho goal
            $stmtUpdateGoal = $conn->prepare("
                UPDATE goals 
                SET saved = saved + ? 
                WHERE id = ? AND user_id = ?
            ");
            $stmtUpdateGoal->execute([$amount, $goal_id, $user_id]);

            // Trừ trực tiếp dòng tiền dư trong monthly_surplus
            $stmtUpdateSurplus = $conn->prepare("
                UPDATE monthly_surplus 
                SET surplus = surplus - ? 
                WHERE user_id = ? AND month = ?
            ");
            $stmtUpdateSurplus->execute([$amount, $user_id, $currentMonth]);

            $conn->commit();
            $_SESSION['success_message'] = "Góp " . number_format($amount) . "đ vào mục tiêu thành công!";

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
