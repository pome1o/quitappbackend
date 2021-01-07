<?php
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();

$user =$_POST['myaccount'];
$friend =$_POST['friendaccount'];

$message =$_POST['msg'];
//$title =$_POST['title'];

$path = "https://fcm.googleapis.com/fcm/send";

$svkey = "AAAAnEs6wNY:APA91bHU51TidqjAzbdt4vm2yY76u-xZ5OLQ8tcmYx3_G7YzT1qMC1oY0UW9OdhRAICF0OBxNrYfK4U1MtSSsf-DYgUmaXZkKQ85IpnlbiDjwCFTsFezs-aKUsJ_fNpQVQIpibdcF27n";

$sql_get = "SELECT * FROM fcm_token WHERE _account ='$friend'";
$sql_get_name = "SELECT * FROM user_info WHERE _account ='$user'";
$sql_friend = "SELECT * FROM user_info WHERE _account = '$friend'";
$sql_friend_request="INSERT INTO friend_request (_sender,_receiver,_agree) VALUES ('$user','$friend','0')";//朋友請求0629
$request_check="SELECT * FROM friend_request WHERE _sender='$user' AND _receiver='$friend'";//檢查是否已有發送好友要求0629
    
$name_result = execute_sql($link,"quitsmoking",$sql_get_name);
$token = execute_sql($link,"quitsmoking",$sql_get);
$friend_R =  execute_sql($link,"quitsmoking",$sql_friend);

$friend_check=execute_sql($link,"quitsmoking",$request_check);//0629



$rowfriend = mysqli_fetch_assoc($friend_R);
$rowname = mysqli_fetch_assoc($name_result);
$rowrequest=mysqli_fetch_assoc($friend_check);

if($rowrequest!=0)
{
    $json['ck']="requesting";//已發送申請
}
elseif($rowfriend==true)//有找到朋友
{
$sender_name = $rowname['name'];  //
 
//echo $fromwho."::::::".$sender."<br>";
//echo $towho."::::::".$recipt."<br";

$curl = curl_init();

$headers = array(
    'Authorization:key='.$svkey,
    'Content-Type:application/json'
);
curl_setopt($curl, CURLOPT_URL,$path);
curl_setopt($curl, CURLOPT_POST, true);//啟用post
curl_setopt($curl, CURLOPT_HTTPHEADER,$headers); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //以訊息文件回傳
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //no ssl

while($rowt = mysqli_fetch_assoc($token))
{
    $recipt_token = $rowt['_fcmtoken'];


$field = array(
    'to'=>$recipt_token,
    'data'=>array('friend'=>$sender_name,'account'=>$user)
                    	 
       );

$send = json_encode($field);
//echo $send."<br>";

curl_setopt($curl, CURLOPT_POSTFIELDS, $send); //傳送內容

$aaa = curl_exec($curl);
/*echo $aaa;
if ($aaa == FALSE) {
	  echo curl_error($curl)."<br>";
	}*/
    
}

    
$json['ck'] = "ok"; //傳回android
$json['fname'] = $rowfriend['_name'];
$json['account'] = $friend;
$friend_request=execute_sql($link,"quitsmoking",$sql_friend_request);//朋友請求0629
curl_close($curl);
}

else{
$json['ck'] = "noaccount";
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
mysqli_free_result($name_result);
mysqli_free_result($token);
mysqli_free_result($friend_R);
mysqli_close($link);
?>