<?php
session_start();
include('config/config.php');

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $action = $_POST['action'] ?? '';
        $user_id = $_SESSION['user_id'];
        if($action == 'add'){
            $budgetsAmount = $_POST['budget_amount'];
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $trans_date = $_POST['trans_date'] ?? date('Y-m-d');
            $category_id = isset($_POST['budget_category_id']) ? trim($_POST['budget_category_id']) : '';
            // echo $category_id;

            if (empty($category_id)) {
                die('Thiếu category_id!');
            }


            //kiểm tra xem ngân sách có phù hợp ko
            $sql_check = $conn->prepare("SELECT id FROM budgets WHERE user_id = :u_id AND category_id = :c_id
            AND :tran_date BETWEEN start_date AND end_date LIMIT 1");
            $sql_check->execute([
                'u_id' => $user_id,
                'c_id' => $category_id,
                'tran_date' => $trans_date,
            ]);
            $budgets = $sql_check->fetchAll(PDO::FETCH_ASSOC);
        
            if ($budgets) {
                    // ❗ Ngân sách đã tồn tại → hiện alert và quay lại
                    echo "<script>
                        alert('Ngân sách đã tồn tại!');
                        window.location.href = 'budgets.php';
                    </script>";
                    exit();
            }//nếu ko có thì thêm mới
            $sql_add = $conn->prepare(
                    "INSERT INTO budgets (user_id, category_id, amount, start_date, end_date, created_at) 
                    VALUES (:u_id, :c_id, :amount, :start_date, :end_date, NOW())"
                );
                $sql_add->execute([
                    "u_id" => $user_id,
                    "c_id" => $category_id,
                    "amount" => $budgetsAmount,
                    "start_date" => $startDate,
                    "end_date" => $endDate
                ]);

                
                header("Location: budgets.php");
                exit();
            
    }
    if($action == 'edit'){
        $category_id = $_POST['category_id'];
        $amount = $_POST['new_amount'];
        //lấy data 
        $sql_check = $conn->prepare("SELECT * FROM budgets WHERE user_id = :u_id AND category_id = :c_id");
        $sql_check->execute([
            'u_id' => $user_id,
            'c_id' => $category_id,
        ]);
        $exisiting = $sql_check->fetchAll(PDO::FETCH_ASSOC);
        //nếu tồn tại thì update
        if($exisiting){
            $sql = $conn->prepare("UPDATE budgets SET amount = :amount WHERE user_id = :u_id AND category_id = :c_id");
            $sql->execute([
                'u_id' => $user_id,
                'c_id' => $category_id,
                'amount' => $amount,
            ]);
            echo '<script>console.log("cập nhật ngân sách thành công")</script>';
            header("Location: budgets.php");
            exit();
        }
        
    }
}
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['category_id'])){
    $category_id = $_GET['category_id'];
    $user_id = $_SESSION['user_id'];

    $sql = $conn->prepare("DELETE FROM budgets WHERE user_id = :u_id AND category_id = :c_id");
    $sql->execute([
        'u_id' => $user_id,
        'c_id' => $category_id,
    ]);
    echo '<script>console.log("Xóa ngân sách thành công")</script>';
    header("Location: budgets.php");
    exit();

}

?>