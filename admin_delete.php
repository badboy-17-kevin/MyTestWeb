<?php
session_start();
// 检查管理员权限
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// 连接数据库检查是否是管理员
$link = mysqli_connect("db", "root", "123456", "mycms");
if (!$link) {
    die("数据库连接失败: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$sql = "SELECT is_admin FROM users WHERE username='$username'";
$result = mysqli_query($link, $sql);
$user = mysqli_fetch_assoc($result);

if ($user['is_admin'] != 1) {
    mysqli_close($link);
    die("你不是管理员，无权执行此操作！<a href='index.php'>返回首页</a>");
}

// 处理删除用户
$delete_id = intval($_GET['id']);
$sql = "DELETE FROM users WHERE id=$delete_id";

if (mysqli_query($link, $sql)) {
    header("Location: admin.php?success=用户删除成功");
} else {
    header("Location: admin.php?error=用户删除失败");
}

mysqli_close($link);
exit();
?>