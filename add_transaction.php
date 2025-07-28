<?php 
session_start();
include('config/config.php');

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
}