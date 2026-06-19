<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$link = mysqli_connect("db", "root", "123456", "mycms");

// 获取当前用户信息
$username = $_SESSION['username'];
$user_sql = "SELECT id, avatar FROM users WHERE username='$username'";
$user_result = mysqli_query($link, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// 修改SQL查询，只获取当前用户的文章
$sql = "SELECT articles.*, users.username, users.avatar 
        FROM articles 
        JOIN users ON articles.author_id = users.id 
        WHERE users.username = '$username'
        ORDER BY created_at DESC";
$result = mysqli_query($link, $sql);
$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的文章 - 个人中心</title>
    <!-- 内嵌式CSS样式 -->
    <style>
        /* 全局样式 */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        
        /* 主容器 */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* 头部区域 */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eaeaea;
        }
        
        /* 用户信息区域 */
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        /* 用户头像 */
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }
        
        /* 无头像提示 */
        .no-avatar {
            color: #888;
            font-style: italic;
        }
        
        /* 导航链接 */
        .nav-links {
            margin: 20px 0;
        }
        
        .nav-links a {
            margin-right: 15px;
            text-decoration: none;
            color: #3498db;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .nav-links a:hover {
            background-color: #f0f7ff;
            color: #2980b9;
        }
        
        /* 文章表格 */
        .article-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .article-table th, 
        .article-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
        }
        
        .article-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
        }
        
        .article-table tr:hover {
            background-color: #f9f9f9;
        }
        
        /* 文章作者头像 */
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            vertical-align: middle;
            margin-left: 10px;
        }
        
        /* 操作链接 */
        .action-links a {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
            padding: 2px 5px;
            border-radius: 3px;
        }
        
        .action-links a:hover {
            background-color: #e8f4fc;
        }
        
        /* 空文章提示 */
        .empty-articles {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-top: 20px;
        }
        
        .empty-articles a {
            color: #3498db;
            text-decoration: none;
        }
        
        /* 底部导航 */
        .footer-nav {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
            text-align: center;
        }
        
        .footer-nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #666;
        }
        
        .footer-nav a:hover {
            color: #3498db;
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .article-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 头部区域 -->
        <div class="header">
            <div class="user-info">
                <h2>欢迎, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <?php if ($user['avatar']): ?>
                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="用户头像" class="avatar">
                <?php else: ?>
                    <p class="no-avatar">您还没有设置头像</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 导航链接 -->
        <div class="nav-links">
            <a href="article_add.php">添加新文章</a>
            <a href="favorites.php">我的收藏</a>
        </div>
        
        <h3>我的文章</h3>
        
        <?php if (empty($articles)): ?>
            <!-- 空文章提示 -->
            <div class="empty-articles">
                <p>您还没有发表任何文章，<a href="article_add.php">点击这里添加新文章</a></p>
            </div>
        <?php else: ?>
            <!-- 文章表格 -->
            <table class="article-table">
                <thead>
                    <tr>
                        <th>标题</th>
                        <th>内容摘要</th>
                        <th>作者</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars(substr($article['content'], 0, 100))); ?>...</td>
                        <td>
                            <?php echo htmlspecialchars($article['username']); ?>
                            <?php if ($article['avatar']): ?>
                                <img src="<?php echo htmlspecialchars($article['avatar']); ?>" class="author-avatar">
                            <?php endif; ?>
                        </td>
                        <td><?php echo $article['created_at']; ?></td>
                        <td class="action-links">
                            <a href="article_view.php?id=<?php echo $article['id']; ?>">查看</a>
                            <a href="article_edit.php?id=<?php echo $article['id']; ?>">编辑</a>
                            <a href="article_delete.php?id=<?php echo $article['id']; ?>" onclick="return confirm('确定删除吗？')">删除</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <!-- 底部导航 -->
        <div class="footer-nav">
            <a href="profile.php">个人中心</a>
            <a href="logout.php">退出登录</a>
            <a href="welcome.php">返回首页</a>
        </div>
    </div>
</body>
</html>