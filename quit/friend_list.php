<?php
//需要取得名字和帳號 sender是我 reveiver是我
//別人申請我
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();

$a =$_POST['myaccount'];
$user="222222";
$fc=array();
$fn=array();
$get_friend1 = "SELECT _sender FROM friend_request WHERE _receiver='$user' and _state='1'";
$get_friend2 = "SELECT _receiver FROM friend_request WHERE _sender='$user' and _state='1'";

$get_friend11 = "SELECT _sender,_name FROM friend_request INNER JOIN user_info on _receiver='$user' AND _state='1' AND friend_request._sender=user_info._account";
//$get_friend22 = "SELECT _receiver,_name FROM friend_request INNER JOIN user_info on _sender='$user' AND _state='1' AND friend_request._receiver=user_info._account";

$ex1_friend=execute_sql($link,"quitsmoking",$get_friend1);
//$ex2_friend=execute_sql($link,"quitsmoking",$get_friend2);

$ex11_friend=execute_sql($link,"quitsmoking",$get_friend11);
//$ex22_friend=execute_sql($link,"quitsmoking",$get_friend22);

$rowfriend1=mysqli_fetch_assoc($ex1_friend);
//$rowfriend2=mysqli_fetch_assoc($ex2_friend);

$i=0;
if($rowfriend1!=0)//有朋友
{
    while($row=mysqli_fetch_assoc($ex11_friend))//抓追隨我的
    {
        $fc[$i]=$row["_sender"];
        $fn[$i]=$row["_name"];
        $i++;
        
    }
    /*while($row2=mysqli_fetch_assoc($ex22_friend))//抓我追隨別人
    {
        $fc[$i]=$row2["_receiver"];
        $fn[$i]=$row2["_name"];
        $i++;
    }*/    
$json['ck'] = "work"; //傳回android
$json['account']=$fc;
$json['name']=$fn;
}
//
else{
     $json['ck'] = "no friend";
}

echo json_encode($json,JSON_UNESCAPED_UNICODE);

mysqli_free_result($ex1_friend);
mysqli_free_result($ex11_friend);
mysqli_close($link);
?>