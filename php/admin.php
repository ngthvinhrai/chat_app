<?php
session_start();
include_once "config.php";

// Kiểm tra nếu quản trị viên đã đăng nhập
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Lấy danh sách người dùng với tìm kiếm
$search = isset($_POST['search']) ? $_POST['search'] : '';
$sql = "SELECT * FROM users WHERE fname LIKE '%$search%' OR lname LIKE '%$search%' OR email LIKE '%$search%'";
$query = mysqli_query($conn, $sql);

// Phân trang
$limit = 10; // Số người dùng hiển thị trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalUsersQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE fname LIKE '%$search%' OR lname LIKE '%$search%' OR email LIKE '%$search%'");
$totalUsers = mysqli_fetch_assoc($totalUsersQuery)['total'];
$totalPages = ceil($totalUsers / $limit);

// Lấy danh sách người dùng với phân trang
$sql .= " LIMIT $limit OFFSET $offset";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="admin_style.css"> <!-- Liên kết tới file CSS -->
</head>

<body>
    <!-- Nút mở sidebar -->
    <button id="toggle-btn" class="toggle-btn">☰</button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h3>Quản lý Admin</h3>
        <div class="admin-menu"> <!-- Thêm thẻ div để nhóm menu -->
            <ul>
                <li><a href="admin.php">Quản lý người dùng</a></li>
                <li><a href="#">Quản lý sản phẩm</a></li>
                <li><a href="#">Cài đặt</a></li>
            </ul>
        </div>
        <ul>
            <li><a href="admin_login.php">Đăng xuất</a></li>
        </ul>
    </div>

    <!-- Nội dung chính -->
    <div class="main-content" id="main-content">
        <h2>Quản lý người dùng</h2>

        <!-- Tìm kiếm người dùng -->
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Tìm kiếm người dùng..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Tìm</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Trạng thái</th>
                    <th>Hình ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?php echo $row['unique_id']; ?></td>
                        <td><?php echo $row['fname']; ?></td>
                        <td><?php echo $row['lname']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><img src="images/<?php echo $row['img']; ?>" alt="User Image" width="50"></td>
                        <td>
                            <a href="edit_user.php?user_id=<?php echo $row['unique_id']; ?>" class="edit-btn">Sửa</a>
                            <a href="delete_user.php?user_id=<?php echo $row['unique_id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">Xóa</a>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Phân trang -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php echo $page == $i ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>

    <!-- JavaScript để mở và đóng sidebar -->
    <script>
        const toggleBtn = document.getElementById('toggle-btn');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('active');

            // Thay đổi nội dung nút dựa trên trạng thái
            if (sidebar.classList.contains('active')) {
                toggleBtn.innerHTML = '✖'; // Biểu tượng đóng
            } else {
                toggleBtn.innerHTML = '☰'; // Biểu tượng mở
            }
        });
    </script>
</body>

</html>