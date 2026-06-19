<?php
session_start();
// 检查用户是否登录
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// 连接数据库获取用户信息
$link = mysqli_connect("db", "root", "123456", "mycms");
if (!$link) {
    die("数据库连接失败: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($link, $sql);
$user = mysqli_fetch_assoc($result);
mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>个人中心 - <?php echo htmlspecialchars($user['username']); ?></title>
    <!-- 内嵌CSS样式 -->
    <style>
        /* 全局样式 */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        
        /* 容器样式 */
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* 标题样式 */
        h2 {
            color: #4CAF50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-top: 0;
        }
        
        h3 {
            color: #4CAF50;
            margin-top: 25px;
        }
        
        /* 头像区域 */
        .avatar-section {
            text-align: center;
            margin: 20px 0;
        }
        
        .avatar-section img {
            border-radius: 50%;
            border: 3px solid #4CAF50;
        }
        
        /* 表单样式 */
        form {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        /* 警告区域 */
        .warning {
            color: #d9534f;
            font-weight: bold;
        }
        
        /* 水平线 */
        hr {
            border: 0;
            height: 1px;
            background-color: #eee;
            margin: 30px 0;
        }
        
        /* 导航链接 */
        .nav-links {
            margin-top: 30px;
            text-align: center;
        }
        
        .nav-links a {
            color: #4CAF50;
            text-decoration: none;
            margin: 0 10px;
            padding: 5px 10px;
            border-radius: 4px;
        }
        
        .nav-links a:hover {
            background-color: #f0f0f0;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>个人中心 - <?php echo htmlspecialchars($user['username']); ?></h2>
        
        <!-- 显示头像 -->
        <div class="avatar-section">
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="头像" width="150">
            <?php else: ?>
                <p>您还没有设置头像</p>
            <?php endif; ?>
        </div>
        
        <!-- 更新头像表单 -->
        <h3>更新头像</h3>
        <form action="update_avatar.php" method="post" enctype="multipart/form-data">
            <input type="file" name="avatar" accept="image/*"><br><br>
            <input type="submit" value="更新头像">
        </form>
        
        <hr>
        
        <!-- 修改密码表单 -->
        <h3>修改密码</h3>
        <form action="update_password.php" method="post">
            当前密码: <input type="password" name="current_password" required><br><br>
            新密码: <input type="password" name="new_password" required><br><br>
            确认新密码: <input type="password" name="confirm_password" required><br><br>
            <input type="submit" value="修改密码">
        </form>
        
        <hr>
        
        <!-- 账户注销 -->
        <h3>账户注销</h3>
        <p class="warning">警告：此操作将永久删除您的账户！</p>
        <form action="delete_account.php" method="post" onsubmit="return confirm('确定要永久删除您的账户吗？此操作不可撤销！');">
            请输入密码确认: <input type="password" name="password" required><br><br>
            <input type="submit" value="永久删除账户" style="background-color: #d9534f;">
        </form>
        
        <hr>
        
        <!-- 导航链接 -->
        <div class="nav-links">
            <a href="index.php">返回首页</a>
            <a href="welcome.php">返回主页</a>
            <a href="logout.php">退出登录</a>
        </div>
    </div>
</body>
</html>