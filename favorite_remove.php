<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: welcome.php");
    exit();
}

$article_id = $_GET['id'];

// 连接数据库
$link = mysqli_connect("db", "root", "123456", "mycms");

// 获取当前用户ID
$username = $_SESSION['username'];
$user_sql = "SELECT id FROM users WHERE username='$username'";
$user_result = mysqli_query($link, $user_sql);
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['id'];

// 移除收藏
$sql = "DELETE FROM favorites WHERE user_id='$user_id' AND article_id='$article_id'";
mysqli_query($link, $sql);

// 关闭连接
mysqli_close($link);

/* // 返回原页面
header("Location: welcome.php");
exit(); */

// 根据来源决定返回哪个页面
$from = isset($_GET['from']) ? $_GET['from'] : 'welcome';
if ($from == 'favorites') {
    header("Location: favorites.php");
} else {
    header("Location: welcome.php");
}
exit();
?>