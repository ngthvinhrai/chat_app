<?php
session_start();
include_once "config.php";

if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $sql = mysqli_query($conn, "SELECT emotion_status FROM users WHERE unique_id = '{$user_id}'");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);
        echo $row['emotion_status']; // Trả về trạng thái cảm xúc
    } else {
        echo "Trạng thái cảm xúc không tìm thấy";
    }
}
