<?php
session_start();

if(!isset($_POST['step'])&&!isset($_GET['step']))
 {
     exit("访问错误！");
 }

//包含数据库连接文件
include('conn.php');

//包含发送验证码文件
include('verify_code.php'); //发送验证码，成功返回数字，（显示信息），失败返回-1 
//使用gen_send_code($sms_tel)即可

//$userid='100';
if(isset($_GET['step']) && $_GET['step']=='send'){
    
    $tel = $_GET['tel'];
    if(check_exist($tel) && check_valid($tel,'tel') &&
        preg_match('/^0?\d{11,11}$/', $tel)){
        
         $result=gen_send_code($_GET['tel']);

        echo var_dump($result);

         if($result==0){
             echo "发送成功!";
         }else{
             echo "发送失败!";
         }
         echo "</br>验证码功能尚在测试，可能存在延迟，也有一定几率不能发送.<br/>每天只能发送三次验证码哦！";
         exit();
     }
}

if(isset($_POST['inputcode'])){
   if($_POST['step']==='verify'){
        $tel=$_POST['teloremail'];
        $sql="SELECT code,used from verify_rec where target= $tel and used <1 and TIMEDIFF(now(),time)<'00:30:00' order by time desc limit 1;";
        //echo "$sql";
        $result=mysql_fetch_array(mysql_query($sql));
        if(!$result){
            //var_dump($result);
            exit("<scrit>javascript:\"alert('未发送过验证码或验证码已失效.')\";</script><br/><a href='javascript:history.back(-1);'>返回</a>");}
        if($result['used']<-2){
            //echo var_dump($result);
            exit("错误尝试次数过多，请重新发送验证码！<a href='javascript:history.back(-1);'>返回</a>");}
        if($_POST['inputcode']==$result['code']){
            //如果验证码正确
            $sql="UPDATE verify_rec SET used = '1' WHERE target=$tel;";
            mysql_query($sql);
            header('HTTP/1.1 303 See Other');
            header("Location: /reg/change_password.html"); 
        }else{
            $sql="UPDATE verify_rec SET used = used-1 WHERE target= $tel and used<1 and TIMEDIFF(now(),time)<'00:30:00'";
            mysql_query($sql);
            echo "验证码错误！<a href='javascript:history.back(-1);'>返回</a>";
        }
    }
}


//每成功发送一次验证码，记录到数据库一次
function rec_send($target ,$kind,$code){
	//$user_id = $user_id;
	//$kind=$kind;
    include('conn.php');
    
	$time = date("Y-m-d H:i:s",time());	//记录发送时间
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

//确认这个账户是不是我们的已注册用户 
function check_exist($tel){
    if(mysql_fetch_array(
        mysql_query("select user_id from user where tel='$tel' limit 1"))){
		//echo "用户{$tel}存在";
		return true;
		}else{
			exit( '发送失败：未注册过的手机号！');
			return false;
		}
};

//如果是合法用户，开始找回密码
//确认不是恶意骗短信验证码，造成损失

//验证是否超过次数，每天最多只能发送三条验证码（每条成本1角）
function check_valid($tel,$kind){
	$date= date("Y-m-d",time());
	$time_1 = $date." 00:00:00";
	$time_2 = $date." 23:59:59";
	$sql="Select count(*) as a from verify_rec where target = $tel and kind='$kind' and time between '$time_1' and '$time_2';";
    $query = mysql_query($sql);
	$row = mysql_fetch_array($query);
    //print_r($row);
    //echo mysql_error();
    $times=$row['a'];
	if($times<3){
	   //echo "<p style='color:#AAAAAA;font-size=90%;'>(今日已经发送过".$times."次)</p>";
	   return true;
	}else{
        echo "<p style='color:#888888;font-size=90%;'>发送失败：今日已发送过{$row['a']}次, 不可以再次请求啦。</p><p style='font-size: 78%;padding-right:50px;'>&nbsp产品尚在初期阶段，可能会存在一些不稳定因素，若给您造成不便，请您毫不犹豫地<a href=/about\contact_us.html>联系我们</a>（24小时校内待命），我们会努力改进产品!</p>";
		return false;
    }
}

?>