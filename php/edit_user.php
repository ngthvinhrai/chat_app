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
    
    // Lấy thông tin người dùng
    $sql = "SELECT * FROM users WHERE unique_id = '{$user_id}'";
    $query = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($query);
}

if (isset($_POST['update_user'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE users SET fname = '{$fname}', lname = '{$lname}', email = '{$email}', status = '{$status}' WHERE unique_id = '{$user_id}'";
    if (mysqli_query($conn, $sql)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Cập nhật thất bại: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa người dùng</title>
    <link rel="stylesheet" href="adminEdit_style.css">
</head>
<body>
    <div class="container">
        <h2>Sửa người dùng</h2>
        <form action="" method="POST">
            <input type="text" name="fname" value="<?php echo $user['fname']; ?>" required>
            <input type="text" name="lname" value="<?php echo $user['lname']; ?>" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <select name="status">
                <option value="Active now" <?php echo $user['status'] === 'Active now' ? 'selected' : ''; ?>>Hoạt động</option>
                <option value="Offline now" <?php echo $user['status'] === 'Offline now' ? 'selected' : ''; ?>>Ngoại tuyến</option>
            </select>
            <button type="submit" name="update_user">Cập nhật</button>
        </form>
    </div>
</body>
</html>
