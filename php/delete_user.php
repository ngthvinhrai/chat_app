<?php
session_start();
include_once "config.php";

// Kiểm tra nếu quản trị viên đã đăng nhập
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    
    // Xóa người dùng
    $sql = "DELETE FROM users WHERE unique_id = '{$user_id}'";
    if (mysqli_query($conn, $sql)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Xóa thất bại: " . mysqli_error($conn);
    }
}
?>
