<?php
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();
$account = $_POST['account'];
$user_date = "";
$user_time = "";
//$account = '103103';


$sql = "SELECT account,date,time  FROM smoking_record WHERE account = '".$account."' AND date >= DATE_ADD(Now(),INTERVAL -2 MONTH)  ORDER BY date DESC";


$result = execute_sql($link,$sql);
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            $user_date .= $row['date'].",";
        }
    }


 $result = execute_sql($link,$sql);

if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
        $user_time .= $row['time']."-";
        }
    }


$json['friend_all_date'] = $user_date;
$json['friend_all_time'] = $user_time;
$json['account'] = $account;
mysqli_free_result($result);
echo json_encode($json,JSON_UNESCAPED_UNICODE);
echo $account;
mysqli_close($link);





//Download MySQL Data to SQLite
// SELECT account,date,COUNT(date) AS count FROM smoking_record WHERE account  = "103103" GROUP BY date
//SELECT account,date,COUNT(date) AS count FROM smoking_record WHERE account = '103103' GROUP BY date ORDER BY `smoking_record`.`date` DESC




?>








