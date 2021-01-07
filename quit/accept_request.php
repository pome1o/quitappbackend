<?php
//需要取得名字和帳號
//別人申請我
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();
$user="222222";
$a =$_POST['myaccount'];
$fc=$_POST['friend_account'];
    
//$sql_get_name = "SELECT * FROM user_info WHERE account ='$user'";
//$friend_request="SELECT name FROM friend_request where receiver='$user'";

$yes="UPDATE friend_request SET _state = '1' WHERE _sender = '$fc' and _receiver='$user'";


//$name_result = execute_sql($link,"quitsmoking",$sql_get_name);

if(execute_sql($link,"quitsmoking",$yes))//刪成功
{

$json['ck'] = "update work"; //傳回android

$json['account'] = $fc;

}
else{
    $json['ck'] = "update fail";
    
}

echo json_encode($json,JSON_UNESCAPED_UNICODE);

mysqli_free_result($yes);
mysqli_close($link);
?>