<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$link = mysqli_connect("db", "root", "123456", "mycms");

// 获取当前用户ID而不仅是头像
$username = $_SESSION['username'];
$user_sql = "SELECT id, avatar FROM users WHERE username='$username'";
$user_result = mysqli_query($link, $user_sql);
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['id'];

// 在获取文章详情前记录浏览历史
// 检查是否已有相同记录
$check_sql = "SELECT id FROM browsing_history WHERE user_id='$user_id' AND article_id='$id'";
$check_result = mysqli_query($link, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    // 更新浏览时间
    $update_sql = "UPDATE browsing_history SET viewed_at=NOW() WHERE user_id='$user_id' AND article_id='$id'";
    mysqli_query($link, $update_sql);
} else {
    // 插入新记录
    $insert_sql = "INSERT INTO browsing_history (user_id, article_id) VALUES ('$user_id', '$id')";
    mysqli_query($link, $insert_sql);
}

// 检查当前用户是否已收藏该文章
$favorite_sql = "SELECT id FROM favorites WHERE user_id='$user_id' AND article_id='$id'";
$favorite_result = mysqli_query($link, $favorite_sql);
$is_favorite = (mysqli_num_rows($favorite_result) > 0);

// 获取文章详情
$sql = "SELECT articles.*, users.username, users.avatar 
        FROM articles 
        JOIN users ON articles.author_id = users.id 
        WHERE articles.id = $id";
$result = mysqli_query($link, $sql);
$article = mysqli_fetch_assoc($result);

mysqli_close($link);

if (!$article) {
    header("Location: article_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($article['title']); ?> - 文章详情</title>
    <!-- 内嵌式CSS样式 -->
    <style>
        /* 全局样式 */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        /* 主容器 */
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        
        /* 用户信息区域 */
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        /* 用户头像 */
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #dee2e6;
            margin-right: 20px;
        }
        
        /* 无头像提示 */
        .no-avatar {
            color: #6c757d;
            font-style: italic;
        }
        
        /* 文章标题 */
        .article-title {
            color: #212529;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        /* 文章内容 */
        .article-content {
            font-size: 16px;
            line-height: 1.8;
            color: #495057;
            margin-bottom: 30px;
        }
        
        /* 文章元信息 */
        .article-meta {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            color: #6c757d;
            font-size: 14px;
        }
        
        /* 作者信息 */
        .author-info {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        
        /* 作者头像 */
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        
        /* 收藏按钮 */
        .favorite-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #f8f9fa;
            color: #212529;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            margin-right: 10px;
        }
        
        .favorite-btn:hover {
            background-color: #e9ecef;
            text-decoration: none;
        }
        
        /* 导航链接 */
        .nav-links {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .nav-links a {
            color: #6c757d;
            text-decoration: none;
            margin-right: 15px;
            transition: color 0.3s ease;
        }
        
        .nav-links a:hover {
            color: #495057;
            text-decoration: underline;
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin-top: 15px;
            }
            
            .user-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .avatar {
                margin-bottom: 15px;
            }
            
            .article-meta {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .author-info {
                margin-bottom: 10px;
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
                <p class="no-avatar">您还没有设置头像</p>
            <?php endif; ?>
            <div>
                <h2>当前登录用户: <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            </div>
        </div>
        
        <!-- 文章标题 -->
        <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
        
        <!-- 文章内容 -->
        <div class="article-content">
            <?php echo nl2br(htmlspecialchars($article['content'])); ?>
        </div>
        
        <!-- 文章元信息 -->
        <div class="article-meta">
            <div class="author-info">
                <?php if ($article['avatar']): ?>
                    <img src="<?php echo htmlspecialchars($article['avatar']); ?>" class="author-avatar">
                <?php endif; ?>
                <span>作者: <?php echo htmlspecialchars($article['username']); ?></span>
            </div>
            <div>发布时间: <?php echo $article['created_at']; ?></div>
        </div>
        
        <!-- 收藏按钮 -->
        <div>
            <?php if ($is_favorite): ?>
                <a href="favorite_remove.php?id=<?php echo $id; ?>&from=view" class="favorite-btn">取消收藏</a>
            <?php else: ?>
                <a href="favorite_add.php?id=<?php echo $id; ?>&from=view" class="favorite-btn">收藏文章</a>
            <?php endif; ?>
        </div>
        
        <!-- 导航链接 -->
        <div class="nav-links">
            <a href="welcome.php">返回首页</a>
            <a href="history.php">我的浏览历史</a>
            <a href="profile.php">个人中心</a>
            <a href="logout.php">退出登录</a>
        </div>
    </div>
</body>
</html>