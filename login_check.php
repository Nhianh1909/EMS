<?php
error_reporting(0);
session_start();


include 'config/config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Truy vấn để kiểm tra thông tin đăng nhập
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        // Đăng nhập thành công
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        // Đăng nhập thất bại
        echo "<script>alert('Email hoặc mật khẩu không đúng!');</script>";
    }
}

?>