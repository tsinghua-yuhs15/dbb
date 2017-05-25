<?php
    session_start();
    include('../reg/conn.php');
    $userid = $_SESSION['userid'];
    $setting_info = $_REQUEST['inter'];
    list($sys, $monit, $monit_time, $intr, $inf_method) = explode (".", $setting_info);
    if($monit == "true"){
        $start = strrpos($monit_time, '间');
        $time = substr($monit_time,$start+3);
        echo $time;
        $monit = 1;
    }
    else{
        $monit = 0;
        $time = 0;
    }
    if($sys == "true"){
        $sys = 1;
    }
    else{
        $sys = 0;
    }
    if($intr == "true"){
        $intr = 1;
    }
    else{
        $intr = 0;
    }
    $setting_confirm_sql = "UPDATE info_setting SET sys_info='$sys' ,moni_info='$monit',moni_interval='$time',intr_info='$intr',info_method='$inf_method' WHERE userid='$userid';";
    echo $setting_confirm_sql;
    mysql_query($setting_confirm_sql);


header('HTTP/1.1 303 See Other');
header("Location: /index.html");
//确保重定向后，后续代码不会被执行
exit;
    ?>