<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户登录</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h2>好文章分享平台</h2>
    <form action="./login.php" method="post">
        用户名：
        <input type="text" name="username"><br><br>
        密码:
        <input type="password" name="password"><br><br>
        <input type="submit" value="登录"><br><br>
        <label for="captcha">验证码:</label>
        <input type="text" id="captcha" name="captcha" required>
        <span id="captchaText" style="font-weight: bold; color: #333; background: #f0f0f0; padding: 2px 5px;"></span>
        <button type="button" onclick="generateCaptcha()">刷新验证码</button><br><br>
        没有账号？
        <a href="register.php" style="margin-left: 20px;">注册新账号</a>

    </form>
    <script>
        // 生成验证码
        function generateCaptcha() {
            const captchaText = Math.floor(1000 + Math.random() * 9000); // 生成4位随机数
            document.getElementById('captchaText').textContent = captchaText;//把生成的数字显示出来
            sessionStorage.setItem('captcha', captchaText); // 存储在sessionStorage中,把验证码临时保存在浏览器中（关闭浏览器会消失）
        }

        // 验证验证码
        function validateCaptcha() {
            const userInput = document.getElementById('captcha').value;//拿到用户填写的验证码
            const storedCaptcha = sessionStorage.getItem('captcha'); //取出之前保存的正确验证码

            if (userInput !== storedCaptcha) {
                alert('验证码错误，请重新输入！');
                generateCaptcha(); // 重新生成验证码
                document.getElementById('captcha').value = ''; // 清空输入框
                return false;
            }
            return true;
        }

        // 页面加载时生成验证码
        window.onload = generateCaptcha;
        //这个验证码是在用户浏览器生成的，不是最安全的（高级用户可以绕过)
        //实际网站通常会在服务器端也验证一次验证码
    </script>
</body>

</html>