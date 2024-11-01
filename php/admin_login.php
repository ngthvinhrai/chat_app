<?php
session_start();
include_once "config.php";

if (isset($_POST['login'])) {
    $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
    $admin_password = mysqli_real_escape_string($conn, $_POST['admin_password']);

    // Kiểm tra email và mật khẩu của quản trị viên
    $sql = "SELECT * FROM admin WHERE email = '{$admin_email}'";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        // Kiểm tra mật khẩu
        if (password_verify($admin_password, $row['password'])) {
            $_SESSION['admin_id'] = $row['admin_id'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "Mật khẩu không chính xác!";
        }
    } else {
        $error = "Email không tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị viên</title>
    <link rel="stylesheet" href="adminLogin_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <h2>Đăng nhập quản trị viên</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="email" name="admin_email" placeholder="Email" required>
            <input type="password" name="admin_password" placeholder="Mật khẩu" required>
            <button type="submit" name="login">Đăng nhập</button>
        </form>
        <div class="footer">
            <p>&copy; 2024 Admin Panel</p>
        </div>
    </div>
</body>

</html>