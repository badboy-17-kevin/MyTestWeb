<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$link = mysqli_connect("db", "root", "123456", "mycms");

// 获取文章详情
$sql = "SELECT articles.*, users.username 
        FROM articles 
        JOIN users ON articles.author_id = users.id 
        WHERE articles.id = $id";
$result = mysqli_query($link, $sql);
$article = mysqli_fetch_assoc($result);

// 检查是否是作者
if ($_SESSION['username'] != $article['username']) {
    mysqli_close($link);
    header("Location: article_list.php");
    exit();
}

// 获取当前用户信息
$username = $_SESSION['username'];
$user_sql = "SELECT avatar FROM users WHERE username='$username'";
$user_result = mysqli_query($link, $user_sql);
$user = mysqli_fetch_assoc($user_result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $sql = "UPDATE articles SET title='$title', content='$content' WHERE id=$id";
    mysqli_query($link, $sql);
    mysqli_close($link);
    
    header("Location: article_view.php?id=$id");
    exit();
}

mysqli_close($link);
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑文章</title>
    <!-- 内嵌式CSS样式 -->
    <style>
        /* 全局样式 - 保持与添加文章页面一致 */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        /* 主容器 */
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* 用户信息区域 */
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        /* 用户头像 */
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 2px solid #e0e0e0;
        }
        
        /* 欢迎标题 */
        h2 {
            color: #2c3e50;
            margin: 0;
            font-weight: 500;
        }
        
        /* 编辑文章标题 */
        h3 {
            color: #2c3e50;
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        /* 表单组 */
        .form-group {
            margin-bottom: 20px;
        }
        
        /* 表单标签 */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        /* 输入框 */
        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        /* 输入框聚焦效果 */
        input[type="text"]:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
        }
        
        /* 文本区域 */
        textarea {
            min-height: 300px; /* 编辑页面文本区域更高 */
            resize: vertical;
        }
        
        /* 按钮容器 */
        .button-group {
            margin-top: 20px;
            display: flex;
            gap: 10px; /* 按钮间距 */
        }
        
        /* 提交按钮 */
        button[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        /* 取消按钮 */
        .cancel-btn {
            background-color: #95a5a6;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        /* 按钮悬停效果 */
        button[type="submit"]:hover {
            background-color: #2980b9;
        }
        
        .cancel-btn:hover {
            background-color: #7f8c8d;
        }
        
        /* 导航链接 */
        .nav-links {
            margin-top: 30px;
            text-align: center;
        }
        
        /* 单个链接样式 */
        .nav-link {
            color: #3498db;
            text-decoration: none;
            margin: 0 10px;
            padding: 5px 0;
            position: relative;
        }
        
        /* 链接悬停效果 */
        .nav-link:hover {
            text-decoration: underline;
        }
        
        /* 链接分隔符 */
        .nav-link:not(:last-child)::after {
            content: "|";
            color: #ccc;
            margin-left: 10px;
        }
        
        /* 无头像提示 */
        .no-avatar {
            color: #7f8c8d;
            font-style: italic;
        }
        
        /* 响应式设计 - 小屏幕适配 */
        @media (max-width: 600px) {
            .user-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .avatar {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 用户信息区域 -->
        <div class="user-info">
            <?php if ($user['avatar']): ?>
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="用户头像" class="avatar">
            <?php else: ?>
                <div class="no-avatar">无头像</div>
            <?php endif; ?>
            <h2>欢迎, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        </div>
        
        <!-- 编辑文章表单 -->
        <h3>编辑文章</h3>
        <form method="post">
            <div class="form-group">
                <label for="title">标题:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required placeholder="请输入文章标题">
            </div>
            <div class="form-group">
                <label for="content">内容:</label>
                <textarea id="content" name="content" required placeholder="请输入文章内容"><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>
            
            <!-- 按钮组 -->
            <div class="button-group">
                <button type="submit">更新文章</button>
                <a href="article_view.php?id=<?php echo $id; ?>" class="cancel-btn">取消</a>
            </div>
        </form>
        
        <!-- 导航链接 -->
        <div class="nav-links">
            <a href="article_list.php" class="nav-link">返回个人文章列表</a>
            <a href="profile.php" class="nav-link">个人中心</a>
            <a href="logout.php" class="nav-link">退出登录</a>
        </div>
    </div>
</body>
</html>