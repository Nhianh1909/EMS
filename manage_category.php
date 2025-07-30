<?php
session_start();
include('config/config.php');

if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    exit();
}
//add category
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $category_id = isset($_POST['category_id']) ? trim($_POST['category_id']) : '';
    $category_name = trim($_POST['category_name']);
    $type = $_POST['category_type'];
    $user_id = $_SESSION['user_id'];
    if (!empty($category_name)) {
        if (!empty($category_id)) {
            // check trùng tên trước 
            $sql_check = $conn->prepare("SELECT * FROM categories WHERE name = :category_name AND type = :type AND user_id = :u_id AND id != :category_id");
            $sql_check->execute([
                'category_name' => $category_name,
                'type' => $type,
                'u_id' => $user_id,
                'category_id' => $category_id
            ]);
            if ($sql_check->rowCount() > 0) {
                echo "<script>alert('Danh mục đã tồn tại!'); window.location.href='transactions.php';</script>";
                exit();
            }
            //Update 
            $sql_updateCat = $conn->prepare("UPDATE categories SET name = :category_name, type = :type WHERE id = :category_id");
            $sql_updateCat->execute([
                'category_name' => $category_name,
                'type' => $type,
                'category_id' => $category_id
            ]);
            echo "<script>alert('Cập nhật danh mục thành công!'); window.location.href='transactions.php';</script>";
            exit();

        } else {
            // check trùng tên
            $sql_check = $conn->prepare("SELECT * FROM categories WHERE name = :category_name AND type = :type AND user_id = :u_id");
            $sql_check->execute([
                'category_name' => $category_name,
                'type' => $type,
                'u_id' => $user_id
            ]);
            if ($sql_check->rowCount() > 0) {
                echo "<script>alert('Danh mục đã tồn tại!'); window.location.href='transactions.php';</script>";
                exit();
            }
            // insert
            $sql_category = $conn->prepare("INSERT INTO categories (name, type, user_id, created_at) VALUES (:category_name, :type, :u_id, NOW())");
            $sql_category->execute([
                'category_name' => $category_name,
                'type' => $type,
                'u_id' => $user_id
            ]);
            header('location: transactions.php');
            exit();
        }
    }
}


if(isset($_GET['action'])&&$_GET['action']=='delete'&&isset($_GET['id'])){
    $category_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    //chỉ xóa danh mục của user hiện tại
    $sql_delete = $conn->prepare("DELETE FROM categories WHERE id = :category_id AND user_id = :user_id");
    $sql_delete->execute([
        'category_id' => $category_id,
        'user_id' => $user_id
    ]);
    header('location: transactions.php');
    exit();
}



?>