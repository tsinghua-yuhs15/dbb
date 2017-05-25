<?php

function gen_send_code($tel){
	header("Content-Type: text/html; charset=UTF-8");

	$flag = 0; 
	$params='';//要post的数据 
	$verify = rand(123456, 999999);//获取随机验证码		

	//以下信息自己填以下
	$argv = array(
        'name'=>'13717615001',
		//'name'=>'dxwxxxxxx',     //必填参数。用户账号
		'pwd'=>'A7E2DDB8A552FB0ADCE1FDFD9818', //必填参数。（web平台：基本资料中的接口密码）
		'content'=>'您正在找回密码，验证码：'.$verify.'，请不要将验证码提供给他人。',   //必填参数。发送内容（1-500 个汉字）UTF-8编码\
		'mobile'=>$tel,   //必填参数。手机号码。多个以英文逗号隔开
		'stime'=>'',   //可选参数。发送时间，填写时以填写的时间发送，不填时为当前时间发送
		'sign'=>'电宝宝',    //必填参数。用户签名。
		'type'=>'pt',  //必填参数。固定值 pt
		'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
	); 
	//print_r($argv);exit;
	//构造要post的字符串 
	//echo $argv['content'];
	foreach ($argv as $key=>$value) { 
		if ($flag!=0) { 
			$params .= "&"; 
			$flag = 1; 
		} 
		$params.= $key."="; $params.= urlencode($value);// urlencode($value); 
		$flag = 1; 
	} 
	$url = "http://web.duanxinwang.cc/asmx/smsservice.aspx?".$params; //提交的url地址
	$con= substr( file_get_contents($url), 0,1 );  //获取信息发送后的状态
	//$con=file_get_contents($url);
	//if(!preg_match("/错误/", $con)){
    if($con==0){
        rec_send($tel ,'tel',$verify);//记录到数据库
	}
    return $con;
}

function send_start_msg($username, $tel){
    header("Content-Type: text/html; charset=UTF-8");
    
	$flag = 0; 
	$params='';//要post的数据 		
	//以下信息自己填以下
	$argv = array(
        'name'=>'13717615001',
		//'name'=>'dxwxxxxxx',     //必填参数。用户账号
		'pwd'=>'A7E2DDB8A552FB0ADCE1FDFD9818', //必填参数。（web平台：基本资料中的接口密码）
		'content'=>'尊敬的'.$username.'您好，您的电池已在#1充电柜开始充电，请您关注手机信息及app内的信息提示。感谢您使用本产品。',   //必填参数。发送内容（1-500 个汉字）UTF-8编码\
		'mobile'=>$tel,   //必填参数。手机号码。多个以英文逗号隔开
		'stime'=>'',   //可选参数。发送时间，填写时以填写的时间发送，不填时为当前时间发送
		'sign'=>'电宝宝',    //必填参数。用户签名。
		'type'=>'pt',  //必填参数。固定值 pt
		'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
	); 
	//print_r($argv);exit;
	//构造要post的字符串 
	//echo $argv['content'];
	foreach ($argv as $key=>$value) { 
		if ($flag!=0) { 
			$params .= "&"; 
			$flag = 1; 
		} 
		$params.= $key."="; $params.= urlencode($value);// urlencode($value); 
		$flag = 1; 
	} 
	$url = "http://web.duanxinwang.cc/asmx/smsservice.aspx?".$params; //提交的url地址
	$con= substr( file_get_contents($url), 0,1 );  //获取信息发送后的状态
	//$con=file_get_contents($url);
	//if(!preg_match("/错误/", $con)){
}

function send_end_msg($username, $tel){
    header("Content-Type: text/html; charset=UTF-8");
    
	$flag = 0; 
	$params='';//要post的数据 
	//以下信息自己填以下
	$argv = array(
        'name'=>'13717615001',
		//'name'=>'dxwxxxxxx',     //必填参数。用户账号
		'pwd'=>'A7E2DDB8A552FB0ADCE1FDFD9818', //必填参数。（web平台：基本资料中的接口密码）
		'content'=>'尊敬的'.$username.'您好，您在#1充电柜的电池充电已完成，请尽快取走~期待您下次使用。',   //必填参数。发送内容（1-500 个汉字）UTF-8编码\
		'mobile'=>$tel,   //必填参数。手机号码。多个以英文逗号隔开
		'stime'=>'',   //可选参数。发送时间，填写时以填写的时间发送，不填时为当前时间发送
		'sign'=>'电宝宝',    //必填参数。用户签名。
		'type'=>'pt',  //必填参数。固定值 pt
		'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
	); 
	//print_r($argv);exit;
	//构造要post的字符串 
	//echo $argv['content'];
	foreach ($argv as $key=>$value) { 
		if ($flag!=0) { 
			$params .= "&"; 
			$flag = 1; 
		} 
		$params.= $key."="; $params.= urlencode($value);// urlencode($value); 
		$flag = 1; 
	} 
	$url = "http://web.duanxinwang.cc/asmx/smsservice.aspx?".$params; //提交的url地址
	$con= substr( file_get_contents($url), 0,1 );  //获取信息发送后的状态
	//$con=file_get_contents($url);
	//if(!preg_match("/错误/", $con)){
}

function send_abnormal_warn($tel,$message){
	header("Content-Type: text/html; charset=UTF-8");

	$flag = 0; 
	$params='';//要post的数据 	

	//以下信息自己填以下
	$argv = array(
        'name'=>'13717615001',
		//'name'=>'dxwxxxxxx',     //必填参数。用户账号
		'pwd'=>$message,   //必填参数。发送内容（1-500 个汉字）UTF-8编码
        //'content'=>'尊敬的 刁锐，您的电池在3号柜中检测到高温异常，请您尽快前往查看，以免造成危险！',
		'mobile'=>$tel,   //必填参数。手机号码。多个以英文逗号隔开
		'stime'=>'',   //可选参数。发送时间，填写时以填写的时间发送，不填时为当前时间发送
		'sign'=>'电宝宝',    //必填参数。用户签名。
		'type'=>'pt',  //必填参数。固定值 pt
		'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
	); 
	//print_r($argv);exit;
	//构造要post的字符串 
	//echo $argv['content'];
	foreach ($argv as $key=>$value) { 
		if ($flag!=0) { 
			$params .= "&"; 
			$flag = 1; 
		}
		$params.= $key."="; $params.= urlencode($value);// urlencode($value); 
		$flag = 1; 
	} 
	$url = "http://web.duanxinwang.cc/asmx/smsservice.aspx?".$params; //提交的url地址
	$con= substr( file_get_contents($url), 0,1 );  //获取信息发送后的状态

    if($con==0){
        rec_send($tel ,'tel',$verify);//记录到数据库
	}
    return $con;
}


/*
//必须先include('conn.php');
//辅助函数
function send_get($url, $get_data) {
	$postdata = http_build_query($get_data);
	$options = array(
		'http' => array(
			'method' => 'GET',//or POST
			'header' => 'Content-type:application/x-www-form-urlencoded',
			'content' => $get_data,
			'timeout' => 15 * 60 // 超时时间（单位:s）
			)
		);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	return $result;
}
//发送验证码，成功返回数字，失败返回-1
function gen_send_code($sms_tel){
	$code=rand(123456,999999);
    $content="%e6%82%a8%e6%ad%a3%e5%9c%a8%e6%b3%a8%e5%86%8c%e7%94%b5%e5%ae%9d%e5%ae%9d%ef%bc%8c%e9%aa%8c%e8%af%81%e7%a0%81%e4%b8%ba".$code."%ef%bc%8c%e4%b8%8d%e8%a6%81%e5%91%8a%e8%af%89%e5%88%ab%e4%ba%ba%e5%93%a6%ef%bc%81%e8%8b%a5%e4%b8%8d%e6%85%8e%e5%85%b3%e9%97%ad%e7%bd%91%e9%a1%b5%ef%bc%8c%e7%8e%b0%e5%9c%a8%e9%87%8d%e6%96%b0%e8%bf%9b%e5%85%a5%e4%be%9d%e7%84%b6%e6%9c%89%e6%95%88%e5%93%a6%ef%bc%81%e3%80%90%e7%94%b5%e5%ae%9d%e5%ae%9d%e3%80%91";
    $result = file_get_contents("http://45.121.52.71:8087/Service.asmx/sendsms?zh=GT45453&mm=dbbtzz147852&hm=".$sms_tel."&nr=".$content."&dxlbid=12&extno=",false);
    
		返回值说明
		参数	参数说明
		0	短信被服务器接纳并已进入队列或执行的操作成功
		-1	参数不全（某参数为空或参数数据类型不正确）
		-2	用户名或密码验证错误
		-3	发送短信余额不足（账户中必须有存款大于1条）
		-4	没有手机号码或手机号码超过100个
		-5	发送手机里含有错误号码
		-6	内容超长
		-7  非法字/短信中含有非法字符或非法词汇（内容含被过滤的关键字）
		-8	未开放HTTP接口
		-9	IP地址认证失败
		-10	发送时间限制
		-11	短信类别ID不存在或不正确
		-12	提交的短信条数不正确

		URL提交示例：
		http://45.121.52.71:8087/Service.asmx/sendsms?zh=帐号&mm=密码&hm=手机号码&nr=短信内容&dxlbid=短信类别ID&extno=
		查询余额：'http://103.193.161.210:8087/Service.asmx/Balance?zh=帐号&mm=密码&dxlbid=短信类别ID'
        if(!preg_match("/ERROR/", $result)){
        rec_send($sms_tel ,'tel',$code);//记录到数据库
    }
    return $result;//."http://45.121.52.71:8087/Service.asmx/sendsms?zh=GT45453&mm=dbbtzz1555&hm=".$sms_tel."&nr=".$content."&dxlbid=12&extno=";
}
*/

?>