<?php
header("Content-Type:text/html; charset=utf-8");
require_once("dbtools.inc.php");
$link = create_connection();
$message ="test";             //$_POST['message']
$title = "asidjaoidjaiodjaiodja";                //$_POST['title']

$path = "https://fcm.googleapis.com/fcm/send";

$svkey ="AAAAnEs6wNY:APA91bHU51TidqjAzbdt4vm2yY76u-xZ5OLQ8tcmYx3_G7YzT1qMC1oY0UW9OdhRAICF0OBxNrYfK4U1MtSSsf-DYgUmaXZkKQ85IpnlbiDjwCFTsFezs-aKUsJ_fNpQVQIpibdcF27n";

$sql = "SELECT * FROM fcm_token ";
$result = execute_sql($link,$sql);
$fetch = mysqli_fetch_row($result);
$key = $fetch[1];

$headers = array(
    'Authorization:key='.$svkey,
    'Content-Type:application/json'

);
echo $key."<br>";
$field = array(
          'data'=>array('a'=>'b','c'=>'d'),
           'to'=>$key		 


       );

$send = json_encode($field,JSON_UNESCAPED_UNICODE);
echo $send."<br>";
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL,$path);
curl_setopt($curl, CURLOPT_POST, true);//啟用post
curl_setopt($curl, CURLOPT_HTTPHEADER,$headers); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //以訊息文件回傳
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //no ssl
curl_setopt($curl, CURLOPT_POSTFIELDS, $send); //傳送內容

$aaa = curl_exec($curl);
echo $aaa;
if ($aaa == FALSE) {
	  echo curl_error($curl)."<br>";
	}
	
curl_close($curl);
mysqli_free_result($sql);
mysqli_close($link);
    
    
    
    
    
    
    


?>