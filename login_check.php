<?php
error_reporting(E_ALL);
session_start();

include 'config/config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Chỉ tìm theo email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Đăng nhập thành công
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        // Sai email hoặc mật khẩu
        echo "<script>alert('Email hoặc mật khẩu không đúng!');</script>";
        header("Refresh:0; url=login.php");
        exit();
    }
}
?>
