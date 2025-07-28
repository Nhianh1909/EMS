<?php
session_start();
include('config/config.php');


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    //hash mật khẩu để bảo mật
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    //thêm dữ liệu vào db
     $sql_info = $conn->prepare("
        INSERT INTO users(username, email, password, created_at, avatar) 
        VALUES (:username, :email, :password, NOW(), NULL)
    ");

    try{
        $sql_info->execute([
            'username'=>$username,
            'email'=>$email,
            'password'=>$hashedPassword
        ]);


        //lấy id user vừa tạo và lưu vào session
        $_SESSION['user_id'] = $conn->lastInsertId();
        header('Location: dashboard.php');
        exit();

    }catch(PDOException $e){
        if($e->getcode() == 23000){
            die('Email đã tồn tại');
        }
        die('Lỗi: ' . $e->getMessage());
    }
}
?>