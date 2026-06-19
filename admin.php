<?php
session_start();
// 检查是否登录
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
    die("你不是管理员，无权访问此页面！<a href='index.php'>返回首页</a>");
}

// 获取所有用户
$sql = "SELECT * FROM users";
$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>用户管理后台</title>
    <!-- 内嵌式CSS样式 -->
    <style>
        /* 全局样式 */
        body {
            font-family: 'Arial', sans-serif; /* 使用Arial字体 */
            background-color: #f0f2f5; /* 浅灰色背景 */
            margin: 0;
            padding: 20px;
            color: #333; /* 主要文字颜色 */
        }
        
        /* 主容器样式 */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white; /* 白色背景 */
            padding: 20px;
            border-radius: 8px; /* 圆角边框 */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* 阴影效果 */
        }
        
        /* 标题样式 */
        h2 {
            color: #2c3e50; /* 深蓝色标题 */
            border-bottom: 2px solid #3498db; /* 蓝色下划线 */
            padding-bottom: 10px;
            margin-top: 0;
        }
        
        h3 {
            color: #3498db; /* 蓝色副标题 */
        }
        
        /* 欢迎信息样式 */
        .welcome {
            font-size: 18px;
            color: #27ae60; /* 绿色文字 */
            margin-bottom: 20px;
        }
        
        /* 表单样式 */
        form {
            background-color: #f8f9fa; /* 浅灰色背景 */
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        input[type="text"], 
        input[type="password"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
        }
        
        input[type="submit"] {
            background-color: #3498db; /* 蓝色按钮 */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        input[type="submit"]:hover {
            background-color: #2980b9; /* 深蓝色悬停效果 */
        }
        
        /* 表格样式 */
        table {
            width: 100%;
            border-collapse: collapse; /* 合并边框 */
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd; /* 底部边框 */
        }
        
        th {
            background-color: #3498db; /* 蓝色表头 */
            color: white;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2; /* 交替行颜色 */
        }
        
        tr:hover {
            background-color: #e9f7fe; /* 悬停行颜色 */
        }
        
        /* 链接样式 */
        a {
            color: #3498db; /* 蓝色链接 */
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline; /* 悬停下划线 */
        }
        
        /* 操作按钮样式 */
        .action-links a {
            margin-right: 10px;
            padding: 5px 10px;
            border-radius: 3px;
        }
        
        .action-links a:first-child {
            background-color: #2ecc71; /* 绿色编辑按钮 */
            color: white;
        }
        
        .action-links a:last-child {
            background-color: #e74c3c; /* 红色删除按钮 */
            color: white;
        }
        
        /* 水平分割线 */
        hr {
            border: 0;
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }
        
        /* 底部导航样式 */
        .footer-nav {
            text-align: center;
            margin-top: 20px;
        }
        
        .footer-nav a {
            margin: 0 10px;
            padding: 8px 15px;
            background-color: #34495e; /* 深蓝色背景 */
            color: white;
            border-radius: 4px;
        }
        
        .footer-nav a:hover {
            background-color: #2c3e50; /* 更深蓝色悬停 */
            text-decoration: none;
        }
        
        /* 头像图片样式 */
        .avatar-img {
            border-radius: 50%; /* 圆形头像 */
            border: 2px solid #3498db; /* 蓝色边框 */
        }
        
        /* 管理员标识样式 */
        .admin-badge {
            display: inline-block;
            background-color: #e74c3c; /* 红色背景 */
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>用户管理后台</h2>
        <p class="welcome">欢迎管理员：<?php echo htmlspecialchars($_SESSION['username']); ?> <span class="admin-badge">ADMIN</span></p>
        
        <!-- 添加用户表单 -->
        <h3>添加新用户</h3>
        <form action="admin_add.php" method="post">
            用户名: <input type="text" name="username" required><br><br>
            密码: <input type="password" name="password" required><br><br>
            是否为管理员: 
            <input type="radio" name="is_admin" value="1">是
            <input type="radio" name="is_admin" value="0" checked>否<br><br>
            <input type="submit" value="添加用户">
        </form>
        
        <hr>
        
        <!-- 用户列表 -->
        <h3>用户列表</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>用户名</th>
                <th>头像</th>
                <th>管理员</th>
                <th>操作</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td>
                    <?php if (!empty($row['avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($row['avatar']); ?>" width="50" class="avatar-img">
                    <?php else: ?>
                        <span style="color: #999;">无头像</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['is_admin']): ?>
                        <span class="admin-badge">是</span>
                    <?php else: ?>
                        否
                    <?php endif; ?>
                </td>
                <td class="action-links">
                    <a href="admin_edit.php?id=<?php echo $row['id']; ?>">编辑</a>
                    <a href="admin_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('确定要删除此用户吗？')">删除</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <hr>
        
        <div class="footer-nav">
            <a href="index.php">返回首页</a>
            <a href="logout.php">退出登录</a>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($link); ?>