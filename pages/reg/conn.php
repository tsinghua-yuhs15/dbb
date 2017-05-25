<?php

//Connect Database
$conn = @mysql_connect("localhost","root","*");
if (!$conn){
	die("连接数据库失败：" . mysql_error());}

mysql_select_db("site", $conn);
mysql_query("set character set 'utf8'");    //字符转换，读库
mysql_query("set names 'utf8'");        //写库
?>
