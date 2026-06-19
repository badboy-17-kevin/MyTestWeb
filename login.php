<?php
// 登录处理
session_start();

// 检查验证码是否正确
if (isset($_POST['captcha']) && isset($_SESSION['captcha'])) {
    if ($_POST['captcha'] !== $_SESSION['captcha']) {
        die("验证码错误！<a href='index.php'>返回登录</a>");
    }
}


// 获取用户提交的表单数据
$username = $_POST['username'];
$password = $_POST['password'];

// 1. 连接数据库
$link = mysqli_connect("db", "root", "123456", "mycms");

// 检查连接是否成功
if (!$link) {
    die("数据库连接失败: " . mysqli_connect_error());
}

// 2. 查询数据库中的用户信息     
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($link, $sql);

// 3. 判断用户是否存在
if (mysqli_num_rows($result) == 1) {
    // 用户存在，获取用户数据
    $user = mysqli_fetch_assoc($result);
    
    // 4. 验证密码是否正确
    if ($password == $user['password']) {
        // 登录成功，设置session
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin']; // 将管理员状态也存入session
        
        // 5. 根据用户权限跳转到不同页面
        if ($user['is_admin'] == 1) {
            // 管理员跳转到后台管理页面
            header("Location: admin.php");
        } else {
            // 普通用户跳转到欢迎页面
            header("Location: welcome.php");
        }
        exit();
    } else {
        echo "密码错误！<a href='index.php'>返回登录</a>";
    }
} else {
    echo "用户不存在！<a href='index.php'>返回登录</a>";
}

// 关闭数据库连接
mysqli_close($link);
?>