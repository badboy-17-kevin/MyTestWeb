<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// 连接数据库获取用户信息
$link = mysqli_connect("db", "root", "123456", "mycms");
$username = $_SESSION['username'];
$sql = "SELECT avatar FROM users WHERE username='$username'";
$result = mysqli_query($link, $sql);
$user = mysqli_fetch_assoc($result);//mysqli_fetch_assoc() 是 PHP 中的一个函数，用于从结果集中获取下一行数据，并将其返回为一个关联数组（associative array）。


// 获取所有文章及作者信息
$articles_sql = "SELECT articles.*, users.username, users.avatar 
                FROM articles 
                JOIN users ON articles.author_id = users.id 
                ORDER BY created_at DESC";
$articles_result = mysqli_query($link, $articles_sql);//获取查询结果
$articles = mysqli_fetch_all($articles_result, MYSQLI_ASSOC);//使用mysqli_fetch_all()函数将结果集中的所有数据获取到，并存储在$articles数组中

// 检查每篇文章是否已被当前用户收藏
foreach ($articles as &$article) {
    $check_fav_sql = "SELECT id FROM favorites WHERE user_id='$user_id' AND article_id='{$article['id']}'";
    $check_fav_result = mysqli_query($link, $check_fav_sql);
    $article['is_favorite'] = (mysqli_num_rows($check_fav_result) > 0);//这行代码检查查询结果中的行数,>0说明至少有一条记录
}


// 关闭数据库连接
mysqli_close($link);
?>

<html>

<head>
    <title>欢迎页面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4CAF50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .user-avatar {
            margin-right: 20px;
        }

        .user-avatar img {
            border-radius: 50%;
        }

        .nav-links {
            margin: 20px 0;
        }

        .nav-links a {
            color: #4CAF50;
            text-decoration: none;
            margin-right: 15px;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .nav-links a:hover {
            background-color: #f0f0f0;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .action-links a {
            color: #4CAF50;
            text-decoration: none;
            margin-right: 10px;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .author-info {
            display: flex;
            align-items: center;
        }

        .author-info img {
            border-radius: 50%;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>欢迎, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <div class="user-info"></div>
        <div class="user-avatar">
            <?php if ($user['avatar']): ?>
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="用户头像" width="100">
        </div>
    <?php else: ?>
        <p>您还没有设置头像</p>
    <?php endif; ?>
    </div>
    <div class="nav-links">
        <p><a href="profile.php">个人中心</a> | <a href="article_list.php">文章管理</a> |<a href="history.php">浏览历史</a> | <a href="logout.php">退出登录</a></p>
    </div>
    <h3>最新文章</h3>

    <table border="3" cellpadding="10">
        <tr>
            <th>标题</th>
            <th>内容摘要</th>
            <th>作者</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($articles as $article): ?>
            <tr>
                <td><?php echo htmlspecialchars($article['title']); ?></td>
                <td><?php echo nl2br(htmlspecialchars(substr($article['content'], 0, 100))); ?>...</td>
                <td>
                <div class="author-info">    
                <?php echo htmlspecialchars($article['username']); ?>
                    <?php if ($article['avatar']): ?>
                        <img src="<?php echo htmlspecialchars($article['avatar']); ?>" width="50">
                    <?php endif; ?>
                </div>
                </td>
                <td><?php echo $article['created_at']; ?></td>
                <td class="action-links">
                    <a href="article_view.php?id=<?php echo $article['id']; ?>">查看</a>
                    <?php if ($_SESSION['username'] == $article['username']): ?>
                        | <a href="article_edit.php?id=<?php echo $article['id']; ?>">编辑</a>
                        | <a href="article_delete.php?id=<?php echo $article['id']; ?>" onclick="return confirm('确定删除吗？')">删除</a>
                    <?php endif; ?>
                    |
                    <?php if ($article['is_favorite']): ?>
                        <a href="favorite_remove.php?id=<?php echo $article['id']; ?>">取消收藏</a>
                    <?php else: ?>
                        <a href="favorite_add.php?id=<?php echo $article['id']; ?>">收藏</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
   
   <script>
        function recordHistory(articleId) {
            // 使用AJAX记录浏览历史
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "record_history.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("article_id=" + articleId);
        }
    </script>
</body>

</html>