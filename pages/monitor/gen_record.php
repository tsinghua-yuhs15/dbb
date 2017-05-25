<?php   

    include("../reg/conn.php");

    $total_box_num=4;   //箱子数可扩展

    
        for($x=1;$x<$total_box_num + 1;$x++){
            $sql="INSERT INTO monitor_record (time, exist_abnormal, box_id, V, A, W, Wh) VALUES (sysdate(), 0, $x";
            $V=rand(300,360)/10;
            $A=rand(410,610)/100;
            $W=rand(0,300)/10;
            $Wh=rand(100,2000)/10;
            $sql.=",$V,$A,$W,$Wh);";
            $check_query = mysql_query($sql);
        }
echo mysql_error();
echo "<br/><!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<meta http-equiv='refresh' content=\"{$_GET['r']}\">
<title>数据记录生成({$_GET['r']}秒)</title>
</head><body>自动刷新中,间隔{$_GET['r']}秒...<br/><br/></body>
</html>"
?>