<?php
header("Content-Type:text/html; charset=utf-8");
require_once("dbtools.inc.php");
$link = create_connection();
$user =$_POST['my_account'];
$fcm_token = $_POST['fcm_token'];

$sql_token = "UPDATE fcm_token SET _fcm_token = null WHERE _fcm_token = '$fcm_token' AND EXISTS (SELECT * FROM(SELECT 1 FROM fcm_token WHERE _fcm_token = '$fcm_token') AS temp )";

$sql= "INSERT INTO fcm_token (_account,_fcm_token) VALUES ('$user','$fcm_token') ON DUPLICATE KEY UPDATE _fcm_token = '$fcm_token'";

execute_sql($link,$sql_token);
$count  = execute_sql($link,$sql);
if($count > 0 ){
  $json['ck'] = "ok";
}

echo json_encode($json,JSON_UNESCAPED_UNICODE);

//echo $user.$fcm_token;

mysqli_close($link);
?>