<!DOCTYPE html>
<html>

<head>
    <title>修改密码</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#f0988a">
    <?php
        session_start();
        if(!isset($_SESSION['userid'])){
            header("Refresh: 3; url='/reg/login.html'");
            echo "身份识别错误，3秒后跳转到登录页面.";
            exit(0);
        }
    ?>
        <link rel="stylesheet" type="text/css" href="/css/global.css?v=1.01">
        <link rel="stylesheet" type="text/css" href="/css/change_password.css?v=1.01">
        <script>
            function check_valid() {
                var pass1 = document.getElementById("password1");
                var pass2 = document.getElementById("password2");
                if (pass1.value.length < 6 || pass1.value.length > 20) {
                    document.getElementById("error_area").innerHTML = "密码长度不符，应为6~20位字符.";
                    return false;
                }
                else {
                    document.getElementById("error_area").innerHTML = "&nbsp;";
                }
                if (pass1.value != pass2.value) {
                    document.getElementById("error_area").innerHTML = "两次输入的密码不一致";
                    return false;
                }
                else {
                    document.getElementById("error_area").innerHTML = "&nbsp;";
                }
            }
        </script>
</head>

<body>
    <div id="login">
        <form name="LoginForm" method="post" action="change_password_server.php">
            <h2>修改密码</h2>
            <fieldset class="inputs">
                <input id="password1" type="password" name="newpassword" placeholder="新密码" autofocus required>
                <input id="password2" type="password" placeholder="确认密码" required>
                <lable id="error_area">&nbsp;</lable>
            </fieldset>
            <fieldset id="actions">
                <input type="submit" id="submit" value="确    认" onclick="return check_valid();"> </form>
    </div>
</body>

</html>