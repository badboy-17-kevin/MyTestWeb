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

// 处理添加用户
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $is_admin = intval($_POST['is_admin']);
    
    // 检查用户名是否已存在
    $check_sql = "SELECT id FROM users WHERE username='$new_username'";
    $check_result = mysqli_query($link, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        mysqli_close($link);
        die("用户名已存在！<a href='admin.php'>返回管理后台</a>");
    }
    
    // 插入新用户
    $insert_sql = "INSERT INTO users (username, password, is_admin) VALUES ('$new_username', '$new_password', $is_admin)";
    
    if (mysqli_query($link, $insert_sql)) {
        header("Location: admin.php?success=用户添加成功");
    } else {
        header("Location: admin.php?error=用户添加失败");
    }
}

mysqli_close($link);
exit();
?>