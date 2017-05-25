<?php
include('../reg/conn.php');
session_start();

if(!isset($_SESSION['userid'])){
        $url="/reg/login.html" ;
        Header("HTTP/1.1 303 See Other"); 
        Header("Location: $url"); 
        exit; //from www.w3sky.com  
}
else{
    //取消充电指令
    $box_id = $_SESSION['boxid'];
    $instruction_query = mysql_query("INSERT INTO instruction_from_php_to_java VALUES('1','0','0','$box_id','0')");
    echo $instruction_query;
}
?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <link rel="stylesheet" href="/css/weui.min.css" />
        <link rel="stylesheet" href="/css/global.css" />
        <style type="text/css">
            body {
                background-color: #fbf9fe
            }
            
            td {
                text-align: center;
            }
            
            .qa {
                font-family: 楷体;
            }
        </style>
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
                    <h2 class="weui_msg_title">
    <?php
    include('../reg/conn.php');

    $userid = $_SESSION['userid'];
    $checkcharge_query=mysql_query("SELECT * FROM charge_log WHERE user_id= '$userid' and event= '1'");
    //查询到该用户的充电记录
    if($result = mysql_fetch_array($checkcharge_query)){
        $userid=$_SESSION['userid'];
        $boxid=1;
        $cellid=3;
        $voltage=12;
        $capacity=rand(20,100);
        $insert_query = mysql_query("INSERT INTO charge_log (user_id, box_id, cell_id, time, event, voltage, capacity) VALUES ('$userid', $boxid, $cellid, sysdate(), '2',$voltage ,$capacity)");
        echo '充电已取消'; } else{ echo '你还没充过电！'; }
    ?>
                    </h2> </div>
                <div class="weui_extra_area"> <a href="JavaScript:alert('相关功能建设中，请期待！');">查看详情</a> </div>
            </div>
        </div>
        <br/> <a href="/index.html" class="weui_btn weui_btn_plain_primary">返回首页</a> </body>

    </html>