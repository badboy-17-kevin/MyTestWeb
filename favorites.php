<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// 连接数据库
$link = mysqli_connect("db", "root", "123456", "mycms");

// 获取当前用户信息
$username = $_SESSION['username'];
$user_sql = "SELECT id, avatar FROM users WHERE username='$username'";
$user_result = mysqli_query($link, $user_sql);
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['id'];

// 获取收藏的文章
$favorites_sql = "SELECT articles.*, users.username, users.avatar, favorites.created_at as fav_time 
                 FROM favorites 
                 JOIN articles ON favorites.article_id = articles.id 
                 JOIN users ON articles.author_id = users.id 
                 WHERE favorites.user_id = '$user_id' 
                 ORDER BY favorites.created_at DESC";
$favorites_result = mysqli_query($link, $favorites_sql);
$favorites = mysqli_fetch_all($favorites_result, MYSQLI_ASSOC);

// 关闭连接
mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的收藏 - 个人中心</title>
    <!-- 内嵌式CSS样式 -->
    <style>
        /* 全局样式 */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f7fa;
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
            border-bottom: 1px solid #e0e6ed;
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
            border: 2px solid #e0e6ed;
        }
        
        /* 无头像提示 */
        .no-avatar {
            color: #8492a6;
            font-style: italic;
        }
        
        /* 导航菜单 */
        .nav-menu {
            margin: 25px 0;
            padding: 0;
            list-style: none;
            display: flex;
            gap: 15px;
        }
        
        .nav-menu a {
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .nav-menu a:hover {
            background-color: #edf2f7;
            color: #2d3748;
        }
        
        /* 内容区域 */
        .content-section {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        /* 收藏表格 */
        .favorites-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .favorites-table th, 
        .favorites-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
        }
        
        .favorites-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #4a5568;
        }
        
        .favorites-table tr:hover {
            background-color: #f8fafc;
        }
        
        /* 作者头像 */
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            vertical-align: middle;
            margin-left: 10px;
            object-fit: cover;
        }
        
        /* 操作链接 */
        .action-links a {
            color: #4299e1;
            text-decoration: none;
            margin-right: 10px;
            padding: 2px 5px;
            border-radius: 3px;
            transition: background-color 0.2s;
        }
        
        .action-links a:hover {
            background-color: #ebf8ff;
            text-decoration: underline;
        }
        
        /* 取消收藏链接特殊样式 */
        .action-links a.unfavorite {
            color: #e53e3e;
        }
        
        .action-links a.unfavorite:hover {
            background-color: #fff5f5;
        }
        
        /* 空状态提示 */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #718096;
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .nav-menu {
                flex-wrap: wrap;
            }
            
            .favorites-table {
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
        
        <!-- 导航菜单 -->
        <ul class="nav-menu">
            <li><a href="profile.php">个人中心</a></li>
            <li><a href="article_list.php">文章管理</a></li>
            <li><a href="welcome.php">返回首页</a></li>
            <li><a href="logout.php">退出登录</a></li>
        </ul>
        
        <!-- 内容区域 -->
        <div class="content-section">
            <h3>我的收藏</h3>
            
            <?php if (empty($favorites)): ?>
                <!-- 空状态提示 -->
                <div class="empty-state">
                    <p>您还没有收藏任何文章</p>
                </div>
            <?php else: ?>
                <!-- 收藏文章表格 -->
                <table class="favorites-table">
                    <thead>
                        <tr>
                            <th>标题</th>
                            <th>内容摘要</th>
                            <th>作者</th>
                            <th>收藏时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($favorites as $article): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($article['title']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars(substr($article['content'], 0, 100))); ?>...</td>
                            <td>
                                <?php echo htmlspecialchars($article['username']); ?>
                                <?php if ($article['avatar']): ?>
                                    <img src="<?php echo htmlspecialchars($article['avatar']); ?>" class="author-avatar">
                                <?php endif; ?>
                            </td>
                            <td><?php echo $article['fav_time']; ?></td>
                            <td class="action-links">
                                <a href="article_view.php?id=<?php echo $article['id']; ?>">查看</a>
                                <a href="favorite_remove.php?id=<?php echo $article['id']; ?>&from=favorites" class="unfavorite">取消收藏</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>