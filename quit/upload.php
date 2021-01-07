<?php
header("Content-Type:text/html; charset=utf-8");
require_once("dbtools.inc.php");
$link = create_connection();
/*$path = "https://fcm.googleapis.com/fcm/send";
$svkey = "AAAAnEs6wNY:APA91bHU51TidqjAzbdt4vm2yY76u-xZ5OLQ8tcmYx3_G7YzT1qMC1oY0UW9OdhRAICF0OBxNrYfK4U1MtSSsf-DYgUmaXZkKQ85IpnlbiDjwCFTsFezs-aKUsJ_fNpQVQIpibdcF27n";*/
$target_dir = "./uploads/";
$server_url_r = "http://120.96.63.55/quit/uploads/";
$server_url = null;
$uploadOk = 0;
$dataisget = 0;
$havefile =false;
$type = "text";
$n = rand(0,999999);
$n1 = rand(0,999999);
date_default_timezone_set('Asia/Taipei');
$micro = str_replace(",","",number_format(round(microtime(true)*1000)));
$micro_last_three = substr($micro,-3,3);
$date = date('Y-m-d H:i:s',$micro/1000).".".$micro_last_three;
// Check if image file is a actual image or fake image
$jsnob = $_POST['job'];
$original_type;

$isj = json_decode($jsnob,true);

$towho = "321";
$fromwho = "321";

$towho =trim($isj['friend_account']);
$fromwho =trim($isj['my_account']);     
$text =$isj['message'];

$issend = "no";
$isreply = false;
$reply_id;
if(isset($_GET['reply'])){
    $isreply = true;
    $reply_id = $_POST['id'];
    $towho =$_POST['friend_account'];
    $fromwho =$_POST['my_account'];     
    $text =$_POST['message'];
}

$sql_check_from = "SELECT * FROM user_info WHERE _account ='".mysqli_real_escape_string($link,$fromwho)."' LIMIT 1";
$sql_check_to = "SELECT * FROM user_info WHERE _account ='".mysqli_real_escape_string($link,$towho)."' LIMIT 1";

if(strlen($fromwho)>=6 || strlen($towho)>=6){
    $result_from =  execute_sql($link,$sql_check_from);
    $result_to = execute_sql($link,$sql_check_to);
    if(mysqli_num_rows($result_from) >0 && mysqli_num_rows($result_to) >0){
        $dataisget = 1;
        $uploadOk = 1;
       
    }
    mysqli_free_result($result_from);
    mysqli_free_result($result_to);
}
/*
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


$sql_get = "SELECT * FROM fcm_token WHERE _account ='$towho'";
$sql_get_name = "SELECT * FROM user_info WHERE _account ='$fromwho'";

$name_result = execute_sql($link,$sql_get_name);
$token = execute_sql($link,$sql_get);
$rowname = mysqli_fetch_assoc($name_result);
$sender_name = $rowname['_name'];
*/

if(isset($_FILES["uploadedfile"]["name"]) && !$isreply){
    $target_name =$_FILES["uploadedfile"]["name"];
    $target_file = $target_dir.$target_name;
    $server_url = $server_url_r.$target_name;
    $type = pathinfo($target_file,PATHINFO_EXTENSION);
    if($text=="沒有文字"){
        if($type =="aac" || $type =="3gp"){
            $text ="你有新語音";
        }else{
            $text = "你有張新圖片";
        }
    }
    
    if($type != "mp4" && $type !="PNG" && $type !="png" && $type !="jpg" && $type !="jpeg" && $type !="JPEG" && $type !="aac" && $type !="3gp") {
        $issend = "no";
        $uploadOk = 0;
    }
    if($type =="aac"){
        $type = "aac";
    }else if($type =="3gp"){
     $type = "3gp";
    }else{
        $original_type = $type;
        $type = "jpg";
    }
   
        
    $fileName =$micro."_".$fromwho.".".$type;
    $target_file = $target_dir.$fileName;
    $server_url = $server_url_r.$fileName;
    
    if($type =="aac" || $type =="3gp"){
        $type = "audio";
    }else{
        $type = "image";
    }

if ($_FILES["uploadedfile"]["tmp_name"] > 50000000) {
    $issend = "no";
    $uploadOk = 0;
}

if ($uploadOk != 0 && $type =="audio") {
    if (move_uploaded_file($_FILES["uploadedfile"]["tmp_name"],$target_file )) {
        $havefile = true;
        $sql_save = "INSERT INTO file_path (_from_who,_to_who,_message,_path,_type,_time) VALUES ('$fromwho','$towho','$text','$server_url','$type','$date')"; 
        if(execute_sql($link,$sql_save)){
             $issend = "yes";
            }
        } else {
         $issend = "no";
    }
  }
    
if ($uploadOk != 0 && $type =="image") {
    if(convert_image($_FILES["uploadedfile"]["tmp_name"],$target_file,$original_type)){
        $havefile = true;
        $sql_save = "INSERT INTO file_path (_from_who,_to_who,_message,_path,_type,_time) VALUES ('$fromwho','$towho','$text','$server_url','$type','$date')"; 
        if(execute_sql($link,$sql_save)){
             $issend = "yes";
            }
        } else {
         $issend = "no";
    }
  }
}



if((($text != "null" || $isreply) &&  $havefile == false) && $dataisget ==1){
    $sql_save = "INSERT INTO file_path (_from_who,_to_who,_message,_path,_type,_time) VALUES ('$fromwho','$towho','$text','$server_url','$type','$date')"; 
    if($isreply){
   $sql_save = "INSERT INTO file_path (_reply,_from_who,_to_who,_message,_path,_type,_time) VALUES ('$reply_id','$fromwho','$towho','$text','$server_url','$type','$date')"; 
    }
    if(execute_sql($link,$sql_save)){
        $issend = "yes";
    }else{
        $issend = "no";
    }
}

if(($text != "null" || $havefile ==true) && $issend=="yes"){
    $id = mysqli_insert_id($link);
    $temp_data = send_message($towho,$fromwho,$type,$server_url,$text,$havefile,$id,$reply_id,$isreply,$link,$date);
    send_push($temp_data);
    $json['issent'] = $issend;
    $json['id'] = $id;
    $json['type'] = $type;
    $json['file_name'] = $fileName;
    $json['time'] = $date;
}else{
    $json['issent'] = "no";
}

echo json_encode($json,JSON_UNESCAPED_UNICODE);
mysqli_close($link);


function convert_image($original,$outputfile,$original_type){
    if($original_type =="jpg" || $original_type =="jpeg"){
        $imageTemp = imagecreatefromjpeg($original);
    }else if($original_type =="png" || $original_type =="PNG"){
        $imageTemp = imagecreatefrompng($original);
    }else if($original_type =="bmp"){
        $imageTemp = imagecreatefromwbmp($original);
    }else if($original_type =="gif"){
        $imageTemp = imagecreatefromgif($original);
    }else{
        return false;
    }
    imagejpeg($imageTemp,$outputfile,60);
    return true;
}
/* while($rowt = mysqli_fetch_assoc($token))
{
    $recipt_token = $rowt['_fcm_token'];
    
    if($havefile){
         $field = array(
        'to'=>$recipt_token,
        'data'=>array('title'=>$sender_name ,'body'=>$text,'type'=>$type,'url'=>$server_url,'fromwho'=>$fromwho)
       
             );
    }
    else{
        
         $field = array(
        'to'=>$recipt_token,
        'data'=>array('type'=>'text','title'=>$sender_name ,'body'=>$text,'fromwho'=>$fromwho)       
       );
    }
  
$send = json_encode($field); 

curl_setopt($curl, CURLOPT_POSTFIELDS, $send); //傳送內容

$aaa = curl_exec($curl);

if ($aaa == FALSE) {
	  echo curl_error($curl)."<br>";
	}
    sleep(0.25);
}
echo $aaa;
curl_close($curl);
mysqli_free_result($name_result);
mysqli_free_result($token);
mysqli_close($link);*/

?>
