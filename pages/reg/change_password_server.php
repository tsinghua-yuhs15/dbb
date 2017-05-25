<?php
    session_start();
    //error_reporting(0);
    
    include('conn.php');
    if(!isset($_POST['newpassword'])||!isset($_SESSION['userid']))
    {
        header("Refresh: 3; url='/reg/login.html'");
        echo "非法访问，3秒后跳转到登录页面.";
        exit(0);
    }

    $userid=$_SESSION['userid'];
    $newpwd=$_POST['newpassword'];
    $newpwd_md5=MD5($newpwd);
    if(strlen($newpwd) < 6||strlen($newpwd)>20){
	   exit('错误：密码长度不符合规定。<a href="javascript:history.back(-1);">返回</a>');
    }
    $sql="UPDATE user SET pwd = '$newpwd', pwd_md5 = '$newpwd_md5' 
            WHERE user_id = $userid;";
    /*---------------------
    ** 此处应同时更新Cookies.
    **-------------------*/
    mysql_query($sql);
    $sql = "INSERT INTO change_password_access(userid,time,password) VALUES ('$userid',sysdate(),'$newpwd');";
    mysql_query($sql);

    header("Refresh: 2; url='/index.html'");
    echo "密码修改成功，2秒后自动跳转到首页.";
    exit(0);
?>