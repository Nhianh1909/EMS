<?php
$host = 'localhost';
$dbname = 'ems';
$username = 'root';
$password = '';
// Kết nối đến cơ sở dữ liệu
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>