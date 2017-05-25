<?php
    session_start();
    include('../reg/conn.php');
    if(!isset($_SESSION['userid'])||!isset($_GET['y'])){
        exit("forbidden");} //Don't change the response,it matchs JavaScript. 

    $year = $_GET['y'];
    $month = $_GET['m'];
    $userid = $_SESSION['userid'];
    $date1 = date_create("$year-$month-01 00:00:00"); //date1:Datetime Object
    $date2 = date_create("$year-$month-01 00:00:00"); //date2:Datetime Object
    date_add($date2,date_interval_create_from_date_string("1 month")); //date2: Datetime Object
	$time_1 = date("Y-m-d H:i:s",date_timestamp_get($date1));//."-01 00:00:00";   //time_1:String
	$time_2 = date("Y-m-d H:i:s",date_timestamp_get($date2));//."-01 00:00:00";   //time_2:String
    //echo "time1:$time_1<br/>time2:$time_2<br/>";
    $sql="SELECT time,event,capacity FROM charge_log WHERE user_id= '$userid' and event = 1 and time between '$time_1' and '$time_2' ORDER BY time asc;";
    //echo $sql."<br/>";
    $check_query = mysql_query($sql);
    
    $index = 0;
    $response = array();
    while($result = mysql_fetch_array($check_query)){
        $day = preg_replace('/^0+/','',substr($result['time'],8,2));
        $n = array('i'=>$index++,
                 'd'=>$day,
                 'c'=>$result['capacity']);
        $response[] = $n;
        $n=null;
    }
    echo json_encode($response);
?>