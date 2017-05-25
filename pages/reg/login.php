<?php
session_start();
error_reporting(0);

//注销登录
if($_GET['action'] == "logout"){
	unset($_SESSION['userid']);
	unset($_SESSION['username']);
    
    header('HTTP/1.1 303 See Other');
    header("Location: /index.html"); 
    //确保重定向后，后续代码不会被执行 
    exit;
}

//首页查询用户名
if($_GET['action']=="getusername")
{
    if(!isset($_SESSION['userid'])){
        exit("guest");
    }else{
        exit ("{$_SESSION['name']}");
    }
}

//登录
if(!isset($_POST['submit'])){
	exit('非法访问!');
}
$username = htmlspecialchars($_POST['username']);
$pwd_hash = $_POST['pwd_hash'];
//包含数据库连接文件
include('conn.php');
//检测用户名为手机号还是邮箱
if(strpos($username,"@") > 0){
	$kind="email";
} else if(strlen($username)==11){
	$kind="tel";
} else exit('用户名不合法！<br/>');
$check_query = mysql_query("SELECT user_id,name,tel,u_group FROM user WHERE $kind='$username' and pwd_md5='$pwd_hash' LIMIT 1");
if($result = mysql_fetch_array($check_query)){
	//登录成功
	$_SESSION[$kind] = $username;  //$tel=$电话号
	$_SESSION['userid'] = $result['user_id'];  //user_id
    $_SESSION['name']= $result['name']; //用户称呼
    $_SESSION['u_group']= $result['u_group']; //用户组
    //echo $_SESSION['group'];
    
    mysql_query("UPDATE user SET last_login_succ_datetime = sysdate() WHERE $kind='$username';");
    mysql_query("INSERT INTO login_log(_name,_pwd,_ifsuccess,time,user_id_if_success) VALUES ('{$_POST['username']}','{$_POST['pwd_hash']}',1,sysdate(),{$result['user_id']});");
    header('HTTP/1.1 303 See Other');
    header("Location: /index.html"); 
    //确保重定向后，后续代码不会被执行 
    exit;
    } else { 
    mysql_query("INSERT INTO login_log(_name,_pwd,_ifsuccess,time) VALUES ('{$_POST['username']}','{$_POST['pwd_hash']}',0,sysdate());");
    exit('登录失败,用户名或密码错误.</br>点击此处 <a href="javascript:history.back(-1);">返回</a> 重试'); } ?>