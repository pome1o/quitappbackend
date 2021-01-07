<?php
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();
$havefriend = false;
$iscorrect = false;
$request = "error";
if(isset($_POST['my_account']) && isset($_POST['_time'])){
    $account = trim($_POST['my_account']);
    $account = (string)mysqli_real_escape_string($link,$account);
    $time = (string)$_POST['_time'];
    $iscorrect =true;
    $request = "correct";
}

if($iscorrect ==1){
    $friend = array();
    $sql_friend = "SELECT * FROM friend_request WHERE _sender ='".$account."' AND _state =1";
    $result_friend = execute_sql($link,$sql_friend);
    $i = 0;
    if(mysqli_num_rows($result_friend)>0){
        while($row = mysqli_fetch_assoc($result_friend)){
            $friend[$i] = $row['_receiver'];
            $i++;
        }
        $havefriend = true;
    }
    mysqli_free_result($result_friend);
}

if($iscorrect && $havefriend){
    $c = 0;
    $array = array();
    for($x = 0; $x<count($friend); $x++){
        if(strlen($time)>2){
            $sql_1 = "SELECT file_path.*,_name FROM file_path INNER JOIN user_info ON (_from_who ='".$account."' AND _to_who = '".$friend[$x]."') AND _time > TIMESTAMP('".mysqli_real_escape_string($link,$time)."') AND file_path._from_who = user_info._account";
           $sql_2 = "SELECT file_path.*,_name FROM file_path INNER JOIN user_info ON (_from_who ='".$friend[$x]."' AND _to_who = '".$account."') AND _time > TIMESTAMP('".mysqli_real_escape_string($link,$time)."') AND file_path._from_who = user_info._account";
        }else{
            $sql_1 = "SELECT file_path.*,_name FROM file_path INNER JOIN user_info ON (_from_who ='".$account."' AND _to_who = '".$friend[$x]."') AND file_path._from_who = user_info._account AND _time > DATE_ADD(Now(),INTERVAL -2 MONTH)";
            $sql_2 = "SELECT file_path.*,_name FROM file_path INNER JOIN user_info ON (_from_who ='".$friend[$x]."' AND _to_who = '".$account."') AND file_path._from_who = user_info._account AND _time > DATE_ADD(Now(),INTERVAL -2 MONTH)";
        }
        $result_message_1 =  execute_sql($link,$sql_1);
        $result_message_2 = execute_sql($link,$sql_2);
        if(mysqli_num_rows($result_message_1) > 0 ){
            while($row = mysqli_fetch_assoc($result_message_1)){
                $array[$c] = $row;
                $c++;
                }
            }
        if(mysqli_num_rows($result_message_2) > 0){
            while($row = mysqli_fetch_assoc($result_message_2)){
                $array[$c] = $row;
                $c++;
                }
            }
        }
    if(count($array)>0){
        $request = "have_data";
    }else{
        $request = "no_data";
        $array = null;
    }
}

$json['request'] = $request;
$json['message'] = $array;

echo json_encode($json,JSON_UNESCAPED_UNICODE);
mysqli_close($link);


?>