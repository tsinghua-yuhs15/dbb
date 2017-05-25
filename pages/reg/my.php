<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>个人中心</title>
    <link rel="stylesheet" type="text/css" href="/css/global.css?v=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="theme-color" content="#f0988a">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/global.css"> </head>

<body>
    <fieldset>
        <legend>个人中心</legend>
        <?php
session_start();

//检测是否登录，若没登录则转向登录界面
if(!isset($_SESSION['userid'])){
	header("Location:login.html");
	exit();
}
//包含数据库连接文件
include('conn.php');
$userid = $_SESSION['userid'];
$user_query = mysql_query("SELECT * FROM user WHERE user_id=$userid limit 1");
$row = mysql_fetch_array($user_query);
switch($row['u_group'])
{
    case 0: {$group = "普通组";break;    }
    case 1: {$group = "管理员";break;    }
    case 2: {$group = "超级管理员";break;}
}
echo "用户ID：$userid<br />".
    "昵称：{$row['name']}<br />".
	"手机：{$row['tel']}<br />".
    "邮箱：{$row['email']}<br />".
	"注册日期：{$row['reg_date']}<br />".
    "用户组：$group<br/>";
?> <a href="login.php?action=logout">注销登录</a>&nbsp;<a href="change_password.php">修改密码</a><a href="/">首页</a>
            <br/> </fieldset>
</body>

</html>