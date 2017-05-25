<?php
    session_start();

    if(!isset($_SESSION['userid'])){
        exit("forbidden");
    }

    include('../reg/conn.php');
    $userid = $_SESSION['userid'];
    $check_query = mysql_query("SELECT time,event,voltage,capacity FROM charge_log WHERE user_id= '$userid' ORDER by time desc LIMIT 200;");
    $index=0;
    $response = array();
    while($result = mysql_fetch_array($check_query)){
        $time = $result['time'];
        $voltage = $result['voltage'];
        $event = $result['event'];
        $capacity = $result['capacity'];
        $n = array('i'=>$index++,
                 't'=>$time,
                 'v'=>$voltage,
                 'e'=>$event,
                 'c'=>$capacity);
        $response[] = $n;
        $n=null;
    }
    echo json_encode($response);
?>