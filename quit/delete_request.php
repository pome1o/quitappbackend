<?php
//拒絕別人追隨我
//別人申請我
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();

$user =$_POST['myaccount'];
$fc=$_POST['friend_account'];
    
//$sql_get_name = "SELECT * FROM user_info WHERE account ='$user'";
//$friend_request="SELECT name FROM friend_request where receiver='$user'";

$refuse="DELETE FROM friend_request WHERE _sender = '$fc' and _receiver='$user'";


//$name_result = execute_sql($link,"quitsmoking",$sql_get_name);

if(execute_sql($link,"quitsmoking",$refuse))//刪成功
{

$json['ck'] = "delete work"; //傳回android

$json['account'] = $friend;

}
else{
    $json['ck'] = "delete fail";
    
}

echo json_encode($json,JSON_UNESCAPED_UNICODE);

mysqli_free_result($refuse);
mysqli_close($link);
?>