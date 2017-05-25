<?php   
//加入非法访问安全保护，仅限admin访问
session_start();
error_reporting(0);
include("../reg/conn.php");
//检测是否登录，若没登录则转向登录界面

if(!isset($_SESSION['userid'])){
    exit("forbidden");
}
//if为超级用户
    function is_abnormal($x,$num){
        switch($x){
            case 0: //电压
                if($num<45||$num>53.5) return 1;
                return 0;
            case 1: //电流
                if($num<1||$num>3) return 1;
                return 0;
            case 2: //功率
                if($num<30||$num>120) return 1;
                return 0;
            case 3: //电量
                if($num<0||$num>180) return 1;
                return 0;
            default:
                break;
        }
    }

    $user_last_id=$_GET['id'];
    $total_box_num=4;   //箱子数可扩展
    //$check_query = mysql_query("select * from monitor_record where id>'{$user_last_id}' order by id desc limit 1");
    mysql_query("INSERT INTO monitor_access(userid,time) VALUES ('{$_SESSION['userid']}',sysdate());");
    
    $exist_abnormal=0;


    $fld_str=array('V', 'A', 'W', 'Wh');
    $Unit_str=array('V','A','W','Wh');
    $table="<tr><th id='Number'>箱号</th><th id='Voltage'>电压</th><th id='Current'>电流</th><th id='Power'>功率</th><th id='Capacity'>电量</th></tr><tbody>";
    //echo $_SESSION['u_group'];
    if($_SESSION['u_group']==1||$_SESSION['u_group']==2)
    {//0=,1=Admin,2=SuperAdmin
        $box_start = 1;
        $box_end = $total_box_num;
    }else{
        $sql="SELECT cell_id FROM charge_log WHERE user_id = {$_SESSION['userid']} AND event = 1 order by time desc LIMIT 1;";
        //echo $sql;
        $result = mysql_fetch_array(mysql_query($sql));
        if($result){
            $box_start = $result['cell_id'];
            $box_end = $result['cell_id'];
            //echo "$box_start,$box_end";   
        }else{
            exit("you have no charging battery");
        }
    }
    
    $any_result=0;//初始化空表变量
    for($box_id=$box_start;$box_id<$box_end+1;$box_id++)
    {//箱号循环
        $sql="SELECT * FROM monitor_record WHERE id>'{$user_last_id}' and box_id = $box_id order by id desc LIMIT 1;";
        $result = mysql_fetch_array(mysql_query($sql));
        
        if($result)
            $any_result=1;
        else continue;
        
        $response['id']=$result['id'];
        $response['time']=$result['time'];
        //print_r($result);
        //echo $box_id.$box_start;
        if($box_id == $box_start){
            $response['V1']=$result['V'];
            $response['A1']=$result['A'];
            $response['W1']=$result['W'];
            $response['Wh1']=$result['Wh'];
            //print_r($response);
        }
        
        
        $table.="<tr><td>{$box_id}</td>";
        for ($x=0; $x<4; $x++)
        {//字段循环输出
            if(is_abnormal($x,$result[$fld_str[$x]])){
                $table.= "<td class='warn'>{$result[$fld_str[$x]]}{$Unit_str[$x]}</td>";
                $exist_abnormal=1;
            }else{
                $table.= "<td>{$result[$fld_str[$x]]}{$Unit_str[$x]}</td>";    //输出四个数据
            }
        }
        $table.="</tr>";
    }
    if(!$any_result) exit("nope");
    $table.="</tr></tbody>";

    $response['table']=$table;
    $response['abnormal']=$exist_abnormal;
        

    echo json_encode($response);
?>