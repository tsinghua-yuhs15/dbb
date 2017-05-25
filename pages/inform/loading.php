<?php
    session_start();
    include('../reg/conn.php');
    if(!isset($_SESSION['userid'])){
        exit("forbidden");} //Don't change the response,it matchs JavaScript. 

    $userid = $_SESSION['userid'];
    $sql="SELECT sys_info,moni_info,moni_interval,intr_info,info_method FROM info_setting WHERE userid= '$userid' LIMIT 1;";
    //echo $sql."<br/>";
    $check_query = mysql_query($sql);
    
    $index = 0;
    $response = array();
    while($result = mysql_fetch_array($check_query)){
        $sys_info = $result['sys_info'];
        $moni_info = $result['moni_info'];
        $moni_interval =$result['moni_interval'];
        $intr_info = $result['intr_info'];
        $info_method = $result['info_method'];
        $response = array('s'=>$sys_info,
                 'mf'=>$moni_info,
                   'mt'=>$moni_interval,
                   'ii'=>$intr_info,
                 'im'=>$info_method);
    }   
    echo json_encode($response);
    $response=null;
?>