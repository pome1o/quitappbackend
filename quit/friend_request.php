<?php

//需要取得朋友名字和朋友帳號
//別人申請我
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();
$a = $_POST['myaccount'];
$user='222222';
//echo $user."<br>";

$fc=array();
$fn=array();
//$sql_get_name = "SELECT * FROM user_info WHERE account ='$user'";
$friend_request="SELECT * FROM friend_request where _receiver='$user' and _state='0'";//我被邀請
//$account="SELECT * FROM friend_request,use_info where friend_request.receiver='$user' and friend_request.state='0'";//抓帳號用
$account="SELECT _sender,_name FROM friend_request INNER JOIN user_info on _receiver=$user AND _state='0' AND friend_request._sender=user_info._account";//帳號和名字
//$select_name="SELECT name FROM user_info where account='$requester'"; //從個人資料找名字
    
$friend_R =execute_sql($link,"quitsmoking",$friend_request);//抓到帳號
$ac_result=execute_sql($link,"quitsmoking",$account);//抓帳號用
//$name_result = execute_sql($link,"quitsmoking",$sql_get_name);


//$rowname = mysqli_fetch_assoc($name_result);
$rowfriend = mysqli_fetch_assoc($friend_R);//抓到朋友帳號
$i=0;
if($rowfriend==true)//有人邀請
{//開始抓名字和帳號
    while($row=mysqli_fetch_assoc($ac_result))
    {
        //echo $row["requester"]."<br>";//申請者帳號
        $fc[$i]=$row["_sender"];
        $fn[$i]=$row["_name"];
        //$select_name="SELECT name FROM user_info where account='$row["requester"];'";
        $i++;
    }
    
//echo $fc[1];
$json['ck'] = "ok"; //傳回android
$json['data']=$fc;
$json['name']=$fn;
//$json['fname'] = $rowfriend['name'];
//$json['account'] = $friend;

}
else{
    $json['ck']="norequest";
}

echo json_encode($json,JSON_UNESCAPED_UNICODE);
mysqli_free_result($ac_result);
mysqli_free_result($friend_R);
mysqli_close($link);
?>