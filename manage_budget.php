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
            $enDate = $_POST['end_date'];
            $category_id = isset($_POST['budget_category_id']) ? trim($_POST['budget_category_id']) : '';
            echo $category_id;

            if (empty($category_id)) {
                die('Thiếu category_id!');
            }


            //lấy dữ liệu
            $sql_check = $conn->prepare("SELECT * FROM budgets WHERE user_id = :u_id AND category_id = :c_id");
            $sql_check->execute([
                'u_id' => $user_id,
                'c_id' => $category_id,
            ]);
            $budget = $sql_check->fetchAll(PDO::FETCH_ASSOC);
            //check xem ngân sách này đã tồn tại chưa
            if($budget){
                echo"<script><alert>Ngân sách này đã tồn tại</alert></script>";
            
            }else{
                $sql = $conn->prepare("INSERT INTO budgets(user_id, category_id, amount, start_date, end_date, created_at)
                                        VALUES(:u_id, :c_id, :amount, :start_date, :end_date, NOW())
                ");
                $sql->execute([
                    'u_id' => $user_id,
                    'c_id' => $category_id,
                    'amount' => $budgetsAmount,
                    'start_date' => $startDate,
                    'end_date' => $enDate,
                ]);

                header("Location: budgets.php");
                exit();
            }
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