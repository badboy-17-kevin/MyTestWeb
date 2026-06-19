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

// 处理更新用户
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['id']);
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $is_admin = intval($_POST['is_admin']);
    
    // 构建更新SQL
    $update_sql = "UPDATE users SET username='$new_username', is_admin=$is_admin";
    if (!empty($new_password)) {
        $update_sql .= ", password='$new_password'";
    }
    $update_sql .= " WHERE id=$user_id";
    
    if (mysqli_query($link, $update_sql)) {
        header("Location: admin.php?success=用户更新成功");
    } else {
        header("Location: admin.php?error=用户更新失败");
    }
}

mysqli_close($link);
exit();
?>