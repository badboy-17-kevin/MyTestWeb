<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户注册</title>
    <!-- 内嵌式CSS样式 -->
    <style>
        /* 全局样式设置 */
        body {
            font-family: 'Arial', sans-serif;  /* 设置字体 */
            line-height: 1.6;  /* 行高设置，增加可读性 */
            background-color: #f5f5f5;  /* 浅灰色背景 */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;  /* 水平居中 */
            align-items: center;  /* 垂直居中 */
            min-height: 100vh;  /* 至少占满整个视口高度 */
            color: #333;  /* 主要文字颜色 */
        }

        /* 主容器样式 */
        .container {
            background-color: white;  /* 白色背景 */
            padding: 30px;  /* 内边距 */
            border-radius: 8px;  /* 圆角边框 */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);  /* 轻微阴影效果 */
            width: 100%;  /* 宽度 */
            max-width: 400px;  /* 最大宽度 */
        }

        /* 标题样式 */
        h2 {
            text-align: center;  /* 居中 */
            color: #2c3e50;  /* 深蓝色标题 */
            margin-bottom: 25px;  /* 底部外边距 */
            font-weight: 500;  /* 中等粗细 */
        }

        /* 表单元素组样式 */
        .form-group {
            margin-bottom: 20px;  /* 底部外边距 */
        }

        /* 输入框标签样式 */
        label {
            display: block;  /* 块级元素，独占一行 */
            margin-bottom: 8px;  /* 底部外边距 */
            font-weight: 500;  /* 中等粗细 */
        }

        /* 输入框通用样式 */
        input[type="text"],
        input[type="password"],
        input[type="file"] {
            width: 100%;  /* 宽度100% */
            padding: 10px;  /* 内边距 */
            border: 1px solid #ddd;  /* 边框 */
            border-radius: 4px;  /* 圆角 */
            font-size: 14px;  /* 字体大小 */
            box-sizing: border-box;  /* 盒模型计算方式 */
        }

        /* 文件上传输入框特殊样式 */
        input[type="file"] {
            padding: 5px;
            background-color: #f9f9f9;
        }

        /* 提交按钮样式 */
        input[type="submit"] {
            background-color: #3498db;  /* 蓝色背景 */
            color: white;  /* 白色文字 */
            border: none;  /* 无边框 */
            padding: 12px 20px;  /* 内边距 */
            border-radius: 4px;  /* 圆角 */
            cursor: pointer;  /* 鼠标指针变为手形 */
            font-size: 16px;  /* 字体大小 */
            width: 100%;  /* 宽度100% */
            transition: background-color 0.3s;  /* 颜色过渡动画 */
        }

        /* 鼠标悬停时按钮效果 */
        input[type="submit"]:hover {
            background-color: #2980b9;  /* 深蓝色 */
        }

        /* 返回链接样式 */
        .return-link {
            display: inline-block;  /* 行内块元素 */
            margin-top: 15px;  /* 顶部外边距 */
            text-align: center;  /* 居中 */
            width: 100%;  /* 宽度100% */
            color: #7f8c8d;  /* 灰色文字 */
            text-decoration: none;  /* 无下划线 */
            font-size: 14px;  /* 字体大小 */
        }

        /* 鼠标悬停时链接效果 */
        .return-link:hover {
            color: #3498db;  /* 蓝色 */
            text-decoration: underline;  /* 下划线 */
        }

        /* 底部链接容器 */
        .link-container {
            text-align: center;  /* 居中 */
            margin-top: 20px;  /* 顶部外边距 */
        }
    </style>
</head>

<body>
    <!-- 主容器 -->
    <div class="container">
        <h2>用户注册</h2>
        
        <!-- 注册表单 -->
        <form action="./do_register.php" method="post" enctype="multipart/form-data">
            <!-- 用户名输入组 -->
            <div class="form-group">
                <label for="username">用户名：</label>
                <input type="text" id="username" name="username" placeholder="请输入用户名" required>
            </div>
            
            <!-- 密码输入组 -->
            <div class="form-group">
                <label for="password">密码：</label>
                <input type="password" id="password" name="password" placeholder="请输入密码" required>
            </div>
            
            <!-- 确认密码输入组 -->
            <div class="form-group">
                <label for="confirm_password">确认密码：</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="请再次输入密码" required>
            </div>
            
            <!-- 头像上传组 -->
            <div class="form-group">
                <label for="avatar">头像上传：</label>
                <input type="file" id="avatar" name="avatar" accept="image/*">
            </div>
            
            <!-- 提交按钮 -->
            <input type="submit" value="注册">
            
            <!-- 返回登录链接 -->
            <div class="link-container">
                <a href="index.php" class="return-link">已有账号？返回登录</a>
            </div>
        </form>
    </div>
</body>
</html>