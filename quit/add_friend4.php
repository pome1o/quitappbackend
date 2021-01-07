<?php
//需要取得名字和帳號
//別人申請我
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();

$a =$_POST['myaccount'];
$user="222222";
$fc=$_POST['friend_account'];

//$sql_get_name = "SELECT * FROM user_info WHERE account ='$user'";
//$friend_request="SELECT name FROM friend_request where receiver='$user'";

$addfriend="INSERT INTO friend_request (_sender,_receiver,_state) VALUES ('$user','$fc','0')";
$sql_friend = "SELECT * FROM user_info WHERE _account = '$fc'";
$request_check="SELECT * FROM friend_request WHERE _sender='$user' AND _receiver='$fc'";//檢查是否已有發送好友要求0629
//$request_check2="SELECT * FROM friend_request WHERE sender='$fc' AND receiver='$user'";

$friend_exist =  execute_sql($link,"quitsmoking",$sql_friend);

$request_exist=execute_sql($link,"quitsmoking",$request_check);
//$request_exist2=execute_sql($link,"quitsmoking",$request_check2);

$rowfriend = mysqli_fetch_assoc($friend_exist);
//$name_result = execute_sql($mysqli_fetch_assoc($friend_exist);link,"quitsmoking",$sql_get_name);
$rowcheck=mysqli_fetch_assoc($request_exist);
//$rowcheck2=mysqli_fetch_assoc($request_exist2);

if($rowcheck!=0)
{
    $json['ck']="requesting_exist";//已追隨
}
elseif($rowfriend==true)//有找到人
{

if(execute_sql($link,"quitsmoking",$addfriend))
{

$json['ck'] = "add_work"; //傳回android

    }
}
else{
    $json['ck'] = "add_fail";
    
}

echo json_encode($json,JSON_UNESCAPED_UNICODE);

mysqli_free_result($friend_exist);
mysqli_free_result($request_exist);


mysqli_close($link);
?>