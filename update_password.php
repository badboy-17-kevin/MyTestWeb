<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 验证新密码和确认密码是否一致
    if ($new_password != $confirm_password) {
        header("Location: profile.php?error=新密码和确认密码不一致");
        exit();
    }
    
    // 连接数据库
    $link = mysqli_connect("db", "root", "123456", "mycms");
    if (!$link) {
        die("数据库连接失败: " . mysqli_connect_error());
    }
    
    $username = $_SESSION['username'];
    $sql = "SELECT password FROM users WHERE username='$username'";
    $result = mysqli_query($link, $sql);
    $user = mysqli_fetch_assoc($result);
    
    // 验证当前密码（实际项目中应该使用password_verify）
    if ($current_password == $user['password']) {
        // 更新密码（实际项目中应该使用password_hash）
        $update_sql = "UPDATE users SET password='$new_password' WHERE username='$username'";
        if (mysqli_query($link, $update_sql)) {
            header("Location: profile.php?success=密码修改成功");
            exit();
        } else {
            header("Location: profile.php?error=密码修改失败");
            exit();
        }
    } else {
        header("Location: profile.php?error=当前密码不正确");
        exit();
    }
    
  
} else {
    header("Location: profile.php");
    exit();
}
?>