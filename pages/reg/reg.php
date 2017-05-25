<?php
error_reporting(0);

session_start();

if(!(isset($_POST['name'])||isset($_GET['action']))){
	exit('非法访问!');}

//包含数据库连接文件
include('conn.php');

function check_name($name){
    if(!preg_match('/^[\w\x80-\xff]{1,15}$/', $name)){
	   exit('错误：昵称非法，支持1-15位长度<a href="javascript:history.back(-1);">返回</a>');}
    if(mysql_fetch_array(
        mysql_query("select user_id from user where name='$name' limit 1"))){
            echo '错误：昵称 ',$name,' 已存在。<a href="javascript:history.back(-1);">返回</a>';}
}
function check_password($password){
    if(strlen($password) < 6||strlen($password)>20){
	   exit('错误：密码长度不符合规定。<a href="javascript:history.back(-1);">返回</a>');}
}
function check_tel($tel){
    if(!preg_match('/^0?\d{11,11}$/', $tel)){
	   exit('错误：手机号格式错误。<a href="javascript:history.back(-1);">返回</a>');}
    if(mysql_fetch_array(
        mysql_query("select user_id from user where tel='$tel' limit 1"))){
            exit ('错误：手机号 '.$tel.' 已存在。<a href="javascript:history.back(-1);">返回</a>');}
}
function check_email($email){
    if(strlen($email)>0&&
       !preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/', $email)){
	   exit('错误：电子邮箱格式错误。<a href="javascript:history.back(-1);">返回</a>');}
    if(strlen($email)>0&& mysql_fetch_array(
    mysql_query("select user_id from user where email='$email' limit 1"))){
	   exit('错误：邮箱 '.$email.' 已存在。<a href="javascript:history.back(-1);">返回</a>');}
}

//每成功发送一次验证码，记录到数据库一次
function rec_send($target ,$kind,$code){
	//$user_id = $user_id;
	//$kind=$kind;
    include('conn.php');
    
	//导入数据库
	$sql="INSERT into verify_rec (kind,target,time,code) VALUES ('$kind','$target',sysdate(),'$code')";
	if(mysql_query($sql, $conn)){
		//echo  "<br/>记录成功.";
		return true;
	}else{
		//echo  "存储发送记录失败！<br/>";
        echo mysql_error();
	return false;
	};
}

if(isset($_GET['action'])){
    if($_GET['action']=='live_check'){
        if($_GET['kind']=='name') check_name($_GET['q']);
        if($_GET['kind']=='tel') check_tel($_GET['q']);
        if($_GET['kind']=='email') check_email($_GET['q']);
        echo 'OK';
    }
}

if(isset($_POST['name'])){
    $name=$_POST['name'];
    $password=$_POST['password'];
    $tel=$_POST['tel'];
    $email=$_POST['email'];
    check_name($name);
    check_password($password);
    check_tel($tel);
    check_email($email);

    //如果审查通过，开始写入数据
    $password = $_POST['password'];
    $password_md5 = MD5($_POST['password']);
    $sql = "INSERT INTO user(name,pwd,pwd_md5,tel,email,reg_date) VALUES ('$name','$password','$password_md5','$tel','$email',sysdate());";

    if(mysql_query($sql,$conn)){
        $sql="SELECT user_id,name from user where tel=".$tel.";";
        $result=mysql_fetch_array(mysql_query($sql,$conn));
        $_SESSION['userid'] = $result['user_id'];
        $_SESSION['username'] = $result['name'];
		$sql="INSERT into info_setting (user_id,sys_info,moni_info,moni_interval,intr_info,info_method) VALUES('{$_SESSION['userid']}','$sys','$monit','$time','$intr','$inf_method';";
		mysql_query($sql,$conn);
        header('Refresh: 3; url=/index.html');
        echo '注册成功！3秒后转到网站首页.';
    } else {
	   echo '错误！添加数据失败：',mysql_error(),'<br />';
	   echo '点击此处 <a href="javascript:history.back(-1);">返回</a> 并重试';
    }
}
?>