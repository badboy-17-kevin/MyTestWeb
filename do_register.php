<?php
// 注册处理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 获取用户提交的数据
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 验证两次密码是否一致
    if ($password != $confirm_password) {
        die("两次输入的密码不一致！<a href='register.php'>返回注册</a>");
    }
    
    // 处理文件上传
    $avatarPath = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        // 上传文件信息
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileType = $_FILES['avatar']['type'];
        
        // 允许的文件类型
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        // 检查文件类型
        if (in_array($fileType, $allowedTypes)) {
            // 创建上传目录（如果不存在）
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // 生成唯一文件名
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;
            
            // 移动文件到目标位置
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $avatarPath ='/uploads/' . $newFileName;/* $destPath */
            } else {
                die("文件上传失败！<a href='register.php'>返回注册</a>");
            }
        } else {
            die("只允许上传JPEG、PNG或GIF格式的图片！<a href='register.php'>返回注册</a>");
        }
    }
    
    // 连接数据库
    $link = mysqli_connect("db", "root", "123456", "mycms");
    
    if (!$link) {
        die("数据库连接失败: " . mysqli_connect_error());
    }
    
    // 检查用户名是否已存在
    $check_sql = "SELECT * FROM users WHERE username='$username'";
    $check_result = mysqli_query($link, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        // 如果用户名已存在，删除已上传的头像（如果有）
        if ($avatarPath && file_exists($avatarPath)) {
            unlink($avatarPath);
        }
        die("用户名已存在！<a href='register.php'>返回注册</a>");
    }
    
    // 插入新用户（包括头像路径）
    $insert_sql = "INSERT INTO users (username, password, avatar) VALUES ('$username', '$password', '$avatarPath')";
    
    if (mysqli_query($link, $insert_sql)) {
        echo "注册成功！<a href='index.php'>前往登录</a>";
    } else {
        // 如果数据库插入失败，删除已上传的头像（如果有）
        if ($avatarPath && file_exists($avatarPath)) {
            unlink($avatarPath);
        }
        echo "注册失败: " . mysqli_error($link);
    }
    
    // 关闭连接
    mysqli_close($link);
} else {
    header("Location: register.php");
}
?>