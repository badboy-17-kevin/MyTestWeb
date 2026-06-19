<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 处理文件上传
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

            // 连接数据库获取旧头像路径
            $link = mysqli_connect("db", "root", "123456", "mycms");
            if (!$link) {
                die("数据库连接失败: " . mysqli_connect_error());
            }

            $username = $_SESSION['username'];
            $sql = "SELECT avatar FROM users WHERE username='$username'";
            $result = mysqli_query($link, $sql);
            $user = mysqli_fetch_assoc($result);
            $oldAvatar = ltrim($user['avatar'], '/'); // 移除前导斜杠以匹配文件系统路径

            // 移动文件到目标位置
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // 使用与注册页面一致的路径格式
                $avatarPath = '/uploads/' . $newFileName;

                // 更新数据库
                $update_sql = "UPDATE users SET avatar='$avatarPath' WHERE username='$username'";
                if (mysqli_query($link, $update_sql)) {
                    // 删除旧头像文件
                    if ($oldAvatar && file_exists($oldAvatar)) {
                        unlink($oldAvatar);
                    }
                    mysqli_close($link);
                    header("Location: profile.php?success=头像更新成功");
                    exit();
                } else {
                    // 如果数据库更新失败，删除已上传的文件
                    unlink($destPath);
                    mysqli_close($link);
                    header("Location: profile.php?error=头像更新失败");
                    exit();
                }
            } else {
                mysqli_close($link);
                header("Location: profile.php?error=文件上传失败");
                exit();
            }
        } else {
            header("Location: profile.php?error=只允许上传JPEG、PNG或GIF格式的图片");
            exit();
        }
    } else {
        header("Location: profile.php?error=请选择有效的图片文件");
        exit();
    }
} else {
    header("Location: profile.php");
    exit();
}
