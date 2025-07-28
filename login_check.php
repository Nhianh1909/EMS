<?php
error_reporting(E_ALL);
session_start();


include 'config/config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Truy vấn để kiểm tra thông tin đăng nhập
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->execute(['email' => $email, 'password' => $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0) {
        // Đăng nhập thành công
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        // Đăng nhập thất bại
        echo "<script>alert('Email hoặc mật khẩu không đúng!');</script>";
    }
}

?>