<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
}
?>

<?php include_once "header.php"; ?>

<style>
/* CSS để bố trí giao diện */
.wrapper {
    display: flex;
    height: 100vh;
}

/* Bên trái: Danh sách người dùng */
.users-section {
    width: 30%;
    border-right: 1px solid #ccc;
    overflow-y: auto;
    padding: 20px;
}

/* Bên phải: Khu vực chat */
.chat-section {
    width: 70%;
    padding: 20px;
    overflow-y: auto;
}

/* Khu vực chat box */
.chat-box {
    height: calc(100vh - 180px);
    overflow-y: auto;
}

.typing-area {
    position: sticky;
    bottom: 0;
    background: white;
    padding: 10px;
}

.typing-area input {
    width: 80%;
}

.typing-area button {
    width: 18%;
}
</style>

<body>
    <div class="wrapper">
        <!-- Bên trái: Gọi file users.php -->
        <div class="users-section">
            <?php include_once "users.php"; ?>
        </div>

        <!-- Bên phải: Gọi file chat.php -->
        <div class="chat-section">
            <?php include_once "chat.php"; ?>
        </div>
    </div>

    <script src="javascript/chat.js"></script>
    <script src="javascript/users.js"></script>
</body>

</html>
