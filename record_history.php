<?php
session_start();
if (!isset($_SESSION['username'])) {
    exit(); // 未登录不记录
}

if (!isset($_POST['article_id'])) {
    exit(); // 没有文章ID不记录
}

$article_id = $_POST['article_id'];

// 连接数据库
$link = mysqli_connect("db", "root", "123456", "mycms");

// 获取当前用户ID
$username = $_SESSION['username'];
$user_sql = "SELECT id FROM users WHERE username='$username'";
$user_result = mysqli_query($link, $user_sql);
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['id'];

// 检查是否已有相同记录
$check_sql = "SELECT id FROM browsing_history WHERE user_id='$user_id' AND article_id='$article_id'";
$check_result = mysqli_query($link, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    // 如果已有记录，则更新浏览时间
    $update_sql = "UPDATE browsing_history SET viewed_at=NOW() WHERE user_id='$user_id' AND article_id='$article_id'";
    mysqli_query($link, $update_sql);
} else {
    // 如果没有记录，则插入新记录
    $insert_sql = "INSERT INTO browsing_history (user_id, article_id) VALUES ('$user_id', '$article_id')";
    mysqli_query($link, $insert_sql);
}

// 关闭连接
mysqli_close($link);
?>