<?php
session_start();
include_once "php/config.php";

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
  exit(); // Thêm exit sau khi header để dừng script
}

// Lấy thông tin người dùng
$sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
if (mysqli_num_rows($sql) > 0) {
  $row = mysqli_fetch_assoc($sql);
} else {
  // Xử lý nếu không tìm thấy người dùng
  header("location: login.php");
  exit();
}

include_once "header.php"; // Bao gồm header HTML
?>

<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="header-content">
          <h1 class="app-name"><i class="fas fa-comments"></i> Chat App</h1>
          <div class="user-info">
            <img src="php/images/<?php echo htmlspecialchars($row['img']); ?>" alt="User Image">
            <div class="details">
              <span><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></span>
              <p><?php echo htmlspecialchars($row['status']); ?></p>
            </div>
            <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout <i class="fas fa-sign-out-alt"></i></a>
          </div>
        </div>
      </header>


      <div class="search">
        <span class="text">Select a user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
        <!-- Danh sách người dùng sẽ được thêm vào đây -->
      </div>
    </section>
  </div>

  <footer>
    <div class="footer-content">
      <p>© 2024 Chat App. All rights reserved.</p>
      <p>Follow us on
        <a href="#">Facebook</a>,
        <a href="#">Twitter</a>,
        <a href="#">Instagram</a>
      </p>
    </div>
  </footer>

  <script src="javascript/users.js"></script>
</body>

</html>