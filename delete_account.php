<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    
    // 连接数据库
    $link = mysqli_connect("db", "root", "123456", "mycms");
    if (!$link) {
        die("数据库连接失败: " . mysqli_connect_error());
    }
    
    $username = $_SESSION['username'];
    $sql = "SELECT password, avatar FROM users WHERE username='$username'";
    $result = mysqli_query($link, $sql);
    $user = mysqli_fetch_assoc($result);
    
    // 验证密码（实际项目中应该使用password_verify）
    if ($password == $user['password']) {
        // 删除头像文件
        if (!empty($user['avatar']) && file_exists($user['avatar'])) {
            unlink($user['avatar']);
        }
        
        // 删除账户
        $delete_sql = "DELETE FROM users WHERE username='$username'";
        if (mysqli_query($link, $delete_sql)) {
            // 注销session
            session_unset();
            session_destroy();
            header("Location: index.php?success=账户已成功删除");
            exit();
        } else {
            header("Location: profile.php?error=账户删除失败");
            exit();
        }
    } else {
        header("Location: profile.php?error=密码不正确");
        exit();
    }
    
    /* mysqli_close($link); */
} else {
    header("Location: profile.php");
    exit();
}
?>