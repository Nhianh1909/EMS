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
            $existing = $sql_check->fetch(PDO::FETCH_ASSOC);

            if($existing){
                //nếu đã tồn tại danh mục ở dạng is_deleted = 0 thì chuyển nó thành 1 để hiển thị
                if($existing['is_deleted']==1){
                    $sql_restore = $conn->prepare("UPDATE categories SET is_deleted = 0, type = :type WHERE id = :category_id AND user_id = :u_id");
                    $sql_restore->execute([
                        'type' => $type,
                    'category_id' => $existing['id'],
                    'u_id' => $user_id
                    ]);
                    header("Location: transactions.php");
                    exit();
                }else{
                    echo "<script>alert('Danh mục đã tồn tại!'); window.location.href='transactions.php';</script>";
                    exit();

                }
            }
         
            //nếu ko có thì tạo mới
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
    $sql_delete = $conn->prepare(" UPDATE categories 
  SET is_deleted = 1 
  WHERE id = :category_id AND user_id = :user_id");
    $sql_delete->execute([
        'category_id' => $category_id,
        'user_id' => $user_id
    ]);
    header('location: transactions.php');
    exit();
}



?>