<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$link = mysqli_connect("db", "root", "123456", "mycms");

// 获取文章作者
$sql = "SELECT users.username 
        FROM articles 
        JOIN users ON articles.author_id = users.id 
        WHERE articles.id = $id";
$result = mysqli_query($link, $sql);
$article = mysqli_fetch_assoc($result);

// 检查是否是作者
if ($_SESSION['username'] == $article['username']) {
    $delete_sql = "DELETE FROM articles WHERE id = $id";
    mysqli_query($link, $delete_sql);
}

mysqli_close($link);
header("Location: article_list.php");
exit();
?>