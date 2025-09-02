<?php
session_start();
include('config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $confirm_pw = $_POST['confirm_password'];

    //  Kiểm tra password và confirm_password
    if ($password !== $confirm_pw) {
        die(' Password và Confirm Password không khớp!');
    }


    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


    $sql_info = $conn->prepare("
        INSERT INTO users(username, email, password, created_at, avatar) 
        VALUES (:username, :email, :password, NOW(), NULL)
    ");

    try {
        $sql_info->execute([
            'username' => $username,
            'email'    => $email,
            'password' => $hashedPassword
        ]);

   
        $_SESSION['user_id'] = $conn->lastInsertId();
        header('Location: dashboard.php');
        exit();

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            die(' Email đã tồn tại!');
        }
        die(' Lỗi: ' . $e->getMessage());
    }
}
?>
