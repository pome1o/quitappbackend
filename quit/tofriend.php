<?php
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();

$user =$_POST['my_account'];
$friend =$_POST['friend_account'];

$message =$_POST['message'];
//$title =$_POST['title'];

$path = "https://fcm.googleapis.com/fcm/send";

$svkey = "AAAAnEs6wNY:APA91bHU51TidqjAzbdt4vm2yY76u-xZ5OLQ8tcmYx3_G7YzT1qMC1oY0UW9OdhRAICF0OBxNrYfK4U1MtSSsf-DYgUmaXZkKQ85IpnlbiDjwCFTsFezs-aKUsJ_fNpQVQIpibdcF27n";


$sql_get = "SELECT * FROM fcm_token WHERE _account ='$friend'";
$sql_get_name = "SELECT * FROM user_info WHERE _account ='$user'";

$name_result = execute_sql($link,$sql_get_name);
$token = execute_sql($link,$sql_get);


$rowname = mysqli_fetch_assoc($name_result);


$sender_name = $rowname['_name'];   
 
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
    $recipt_token = $rowt['_fcm_token'];


$field = array(
    'to'=>$recipt_token,
    'notification'=>array('title'=>$sender_name ,'icon'=>"http://120.96.63.55/quit/123",'body'=>$message)
                    	 
       );

$send = json_encode($field,JSON_UNESCAPED_UNICODE);
echo $send."<br>";

curl_setopt($curl, CURLOPT_POSTFIELDS, $send); //傳送內容

$aaa = curl_exec($curl);
echo $aaa;
if ($aaa == FALSE) {
	  echo curl_error($curl)."<br>";
	}
    sleep(0.25);
}

curl_close($curl);

mysqli_free_result($name_result);
mysqli_free_result($token);
mysqli_close($link);
?>