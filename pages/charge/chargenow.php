<!DOCTYPE html>
<html>
<?php
    include('../reg/conn.php');
    session_start();
    if(isset($_SESSION['userid'])){
        $userid=$_SESSION['userid'];
        $boxid=1;
        $_SESSION['boxid'] = $boxid;
        $cellid=3;
        $voltage=12;
        $capacity=rand(20,100);
        $insert_query = mysql_query("INSERT INTO charge_log (user_id, box_id, cell_id, time, event, voltage, capacity) VALUES ('$userid', $boxid, $cellid, sysdate(), '1',$voltage ,$capacity)");
        //box_id待确定
        $instruction_query = mysql_query("INSERT INTO instruction_from_php_to_java VALUES('1','1','0',$boxid,$voltage)");
        
    }//未登录
    else{
        $url="/reg/login.html" ;
        Header("HTTP/1.1 303 See Other"); 
        Header("Location: $url"); 
        exit; //from www.w3sky.com  
    }
?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
        <meta http-equiv="Expires" content="0" />
        <link rel="stylesheet" href="/css/weui.min.css" />
        <link rel="stylesheet" href="/css/global.css" />
        <title>电宝宝智能充电桩</title>
    </head>

    <body>
        <p class="title-Primary">电宝宝智能充电桩 </p>
        <p class="title-Secondary">为您的电池量身设计</p>
        <hr/>
        <div>
            <div class="weui_msg">
                <div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
                <div class="weui_text_area">
                    <h2 class="weui_msg_title">充电已开始</h2>
                    <p class="weui_msg_desc">预计需要10小时充满
                        <p class="weui_msg_desc">随着使用的增加，预测时间将越来越精确。</p>
                </div>
                <div class="weui_extra_area"> <a href="">查看详情</a> </div>
            </div>
        </div>
        <br/> <a href="chargecancel.php" class="weui_btn weui_btn weui_btn_warn">取消充电</a> <a href="/monitor/index.html" class="weui_btn weui_btn_primary">状态监测</a><a href="/index.html" class="weui_btn weui_btn_plain_primary">返回首页</a> </body>

</html>