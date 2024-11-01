<?php
include_once "config.php";

// Mã hóa mật khẩu
$admin_email = 'admin@example.com';
$admin_password = password_hash('123456', PASSWORD_DEFAULT);

// Thêm quản trị viên vào cơ sở dữ liệu
$sql = "INSERT INTO admin (email, password) VALUES ('$admin_email', '$admin_password')";
if (mysqli_query($conn, $sql)) {
    echo "Tài khoản quản trị viên đã được tạo thành công!";
} else {
    echo "Lỗi khi tạo tài khoản: " . mysqli_error($conn);
}
?>
