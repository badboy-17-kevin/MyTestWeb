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

// 获取要编辑的用户信息
$edit_id = intval($_GET['id']);
$sql = "SELECT * FROM users WHERE id=$edit_id";
$result = mysqli_query($link, $sql);
$edit_user = mysqli_fetch_assoc($result);

if (!$edit_user) {
    mysqli_close($link);
    die("用户不存在！<a href='admin.php'>返回管理后台</a>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>编辑用户 - 后台管理系统</title>
    <!-- 内嵌式CSS样式 -->
    <style>
        /* 全局样式 - 应用于整个页面 */
        body {
            font-family: 'Arial', sans-serif; /* 使用Arial字体 */
            background-color: #f5f5f5; /* 浅灰色背景 */
            margin: 0; /* 去除默认边距 */
            padding: 20px; /* 添加内边距 */
            color: #333; /* 深灰色文字 */
        }
        
        /* 容器样式 - 包裹主要内容 */
        .container {
            max-width: 800px; /* 最大宽度 */
            margin: 0 auto; /* 水平居中 */
            background-color: #fff; /* 白色背景 */
            padding: 30px; /* 内边距 */
            border-radius: 5px; /* 圆角边框 */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* 轻微阴影效果 */
        }
        
        /* 标题样式 */
        h2 {
            color: #2c3e50; /* 深蓝色标题 */
            border-bottom: 2px solid #3498db; /* 底部蓝色边框 */
            padding-bottom: 10px; /* 标题与边框间距 */
            margin-top: 0; /* 去除默认上边距 */
        }
        
        /* 表单样式 */
        form {
            margin-top: 20px; /* 上边距 */
        }
        
        /* 输入框样式 */
        input[type="text"], 
        input[type="password"] {
            width: 100%; /* 宽度100% */
            padding: 10px; /* 内边距 */
            margin: 8px 0; /* 外边距 */
            border: 1px solid #ddd; /* 浅灰色边框 */
            border-radius: 4px; /* 圆角 */
            box-sizing: border-box; /* 盒模型计算方式 */
        }
        
        /* 单选按钮样式 */
        input[type="radio"] {
            margin: 10px 5px; /* 外边距 */
        }
        
        /* 提交按钮样式 */
        input[type="submit"] {
            background-color: #3498db; /* 蓝色背景 */
            color: white; /* 白色文字 */
            padding: 12px 20px; /* 内边距 */
            border: none; /* 无边框 */
            border-radius: 4px; /* 圆角 */
            cursor: pointer; /* 鼠标指针变为手形 */
            font-size: 16px; /* 字体大小 */
            transition: background-color 0.3s; /* 背景色过渡效果 */
        }
        
        /* 提交按钮悬停效果 */
        input[type="submit"]:hover {
            background-color: #2980b9; /* 深蓝色背景 */
        }
        
        /* 水平线样式 */
        hr {
            border: 0; /* 去除默认边框 */
            height: 1px; /* 高度 */
            background-color: #ddd; /* 浅灰色背景 */
            margin: 20px 0; /* 外边距 */
        }
        
        /* 链接样式 */
        a {
            color: #3498db; /* 蓝色链接 */
            text-decoration: none; /* 去除下划线 */
        }
        
        /* 链接悬停效果 */
        a:hover {
            text-decoration: underline; /* 添加下划线 */
        }
        
        /* 标签样式 - 表单前面的文字 */
        label {
            display: block; /* 块级显示 */
            margin: 15px 0 5px; /* 外边距 */
            font-weight: bold; /* 加粗 */
        }
        
        /* 响应式设计 - 当屏幕小于600px时的样式 */
        @media (max-width: 600px) {
            .container {
                padding: 15px; /* 减少内边距 */
            }
            input[type="submit"] {
                width: 100%; /* 按钮宽度100% */
            }
        }
    </style>
</head>
<body>
    <!-- 主容器 -->
    <div class="container">
        <h2>编辑用户</h2>
        
        <!-- 编辑表单 -->
        <form action="admin_update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $edit_user['id']; ?>">
            
            <!-- 用户名输入框 -->
            <label for="username">用户名:</label>
            <input type="text" name="username" id="username" 
                   value="<?php echo htmlspecialchars($edit_user['username']); ?>" required>
            
            <!-- 密码输入框 -->
            <label for="password">密码:</label>
            <input type="password" name="password" id="password" placeholder="留空则不修改">
            
            <!-- 管理员选项 -->
            <label>是否为管理员:</label>
            <input type="radio" name="is_admin" id="admin_yes" value="1" 
                   <?php echo $edit_user['is_admin'] ? 'checked' : ''; ?>>
            <label for="admin_yes" style="display: inline;">是</label>
            
            <input type="radio" name="is_admin" id="admin_no" value="0" 
                   <?php echo !$edit_user['is_admin'] ? 'checked' : ''; ?>>
            <label for="admin_no" style="display: inline;">否</label>
            
            <br><br>
            
            <!-- 提交按钮 -->
            <input type="submit" value="更新用户">
        </form>
        
        <hr>
        <!-- 返回链接 -->
        <a href="admin.php">← 返回管理后台</a>
    </div>
</body>
</html>

<?php mysqli_close($link); ?>