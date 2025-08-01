<?php 
session_start();
include('config/config.php');

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = $_POST['action'] ?? '';
    if($action == 'add'){
        $type = $_POST['trans_type'] ?? '';
        $amount = $_POST['trans_amount'] ?? 0;
        $category_id = $_POST['trans_category'] ?? '';
        $transDate = $_POST['trans_date'] ?? date('Y-m-d');
        $description = $_POST['trans_description'] ?? '';

        $user_id = $_SESSION['user_id'];


        if(!$category_id){
            die('không tìm thấy id danh mục, danh mục không tồn tại');
        }
        //thêm giao dịch vào transactions
        $sql_transaction = $conn->prepare("
            INSERT INTO transactions(user_id, category_id, amount, description, transaction_date, created_at) 
            VALUES (:u_id, :c_id, :amount, :description, :trans_date, NOW())
        ");
        $sql_transaction->execute([
            'u_id'=>$user_id,
            'c_id'=>$category_id,
            'amount'=>$amount,
            'description'=>$description,
            'trans_date'=>$transDate
        ]);
        //quay lại trang chủ
        header('location: transactions.php');
        exit();
    }

    if($action == 'edit'){
        $trans_id = $_POST['transaction_id'] ?? '';
        $user_id = $_SESSION['user_id'];
        $amount = $_POST['trans_amount'] ?? '';
        $description = trim($_POST['trans_description'] ?? '');

        if(!isset($_POST['transaction_id'])){
            die('không tìm thấy id trans, trans không tồn tại');
        }
        //check dữ liệu
        $sql = $conn->prepare("SELECT * FROM transactions WHERE id = :id AND user_id = :u_id");
        $sql->execute([
            'id'=>$trans_id,
            'u_id'=>$user_id
        ]);
        $transaction = $sql->fetchAll(PDO::FETCH_ASSOC);
        if($transaction){
            //update giao dịch
            $sql_transaction = $conn->prepare("UPDATE transactions SET amount = :amount, description = :description WHERE id = :id AND user_id = :u_id");
            $sql_transaction->execute([
                'amount'=>$amount,
                'description'=>$description,
                'id'=>$trans_id,
                'u_id'=>$user_id
            ]);
            echo '<script>console.log("cập nhật giao dịch thành công")</script>';
            header("Location: transactions.php");
            exit();
        }
    }

}