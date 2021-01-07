<?php
  function create_connection(){
      
    $link = mysqli_connect("localhost", "root", "admin")
      or die("無法建立資料連接: " . mysqli_connect_error());
	  
    mysqli_query($link, "SET NAMES utf8");
			  	
    return $link;
  }
	
  function execute_sql($link, $sql){
      
    mysqli_select_db($link,"quitsmoking")
      or die("開啟資料庫失敗: " . mysqli_error($link));
						 
    $result = mysqli_query($link, $sql);
		
    return $result;
  }
   
function send_push($data){
    $curl = curl_init();
    $svkey ="AAAAnEs6wNY:APA91bHU51TidqjAzbdt4vm2yY76u-xZ5OLQ8tcmYx3_G7YzT1qMC1oY0UW9OdhRAICF0OBxNrYfK4U1MtSSsf-DYgUmaXZkKQ85IpnlbiDjwCFTsFezs-aKUsJ_fNpQVQIpibdcF27n";
    $headers = array(
    'Authorization:key='.$svkey,
    'Content-Type:application/json'
    );
    
    $path = "https://fcm.googleapis.com/fcm/send";
    
    $field = $data;
    $send = json_encode($field,JSON_UNESCAPED_UNICODE);    

    curl_setopt($curl, CURLOPT_URL,$path);
    //啟用post
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
    //以訊息文件回傳
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //no ssl
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //傳送內容
    curl_setopt($curl, CURLOPT_POSTFIELDS, $send); 

    $aaa = curl_exec($curl);
    
    if ($aaa == FALSE) {
	  echo curl_error($curl)."<br>";
	}
	
    curl_close($curl);
}

//"我抽菸了" 的推播通知
function i_smoke($sender_account,$want,$isaw,$link){

    $token_array = array();  
    $c = 0;
    $sql_get = "SELECT fcm_token._fcm_token FROM friend_request inner join fcm_token on friend_request._sender ='$sender_account' AND friend_request._receiver = fcm_token._account";
    
    $token = execute_sql($link,$sql_get);
    
    while($rowt = mysqli_fetch_row($token)){
        $token_array[$c] = $rowt[0];
        $c++;
    }
    mysqli_free_result($token);
    
    $body = "QQ我抽菸了";
    if($want){
        $body = "我想抽菸了";
    }
    if($isaw){
        $body = "有人看到他抽菸!快點提醒他";
    }
    $sql_get_name = "SELECT * FROM user_info WHERE _account ='$sender_account'";
    $name_result = execute_sql($link,$sql_get_name);
    $rowname = mysqli_fetch_assoc($name_result);

    $sender_name = $rowname['_name'];
        
    $temp = array(
    'collapse_key'=>"i_smoke",
    'priority' =>"high",
    'registration_ids'=>$token_array,
    'notification'=>array('title'=>$sender_name ,'body'=>$body),
    'data'=>array('i_smoke'=>"true","sender_account"=>$sender_account),
    'time_to_live'=>170000          	 
       );
    mysqli_free_result($name_result);
 
    
    for($i = 0; $i<count($token_array);$i++){
        if($respondata['results'][$i]['error']=="NotRegistered"){
            $sql_delt = "DELETE FROM fcm_token WHERE fcmtoken = '$token_array[$i]'";
            execute_sql($link,$sql_delt);
        }
    }   
    return $temp;
}

function send_message($receiver_account,$sender_account,$type,$server_url,$text,$havefile,$id,$reply_id,$isreply,$link,$time){
    $sql_get = "SELECT * FROM fcm_token WHERE _account ='$receiver_account'";
    $sql_get_name = "SELECT * FROM user_info WHERE _account ='$sender_account'";

    $token = execute_sql($link,$sql_get);
    $name_result = execute_sql($link,$sql_get_name);
    
    $rowname = mysqli_fetch_assoc($name_result);
    $sender_name = $rowname['_name'];
    
    $rowt = mysqli_fetch_assoc($token);
    $recipt_token = $rowt['_fcm_token'];
    $re_id = 0;
    if($isreply){
        $re_id = $reply_id;
    }
    if($havefile == true && $server_url != "null"){
         $field = array(
        'to'=>$recipt_token,
        'data'=>array('title'=>$sender_name ,'body'=>$text,'type'=>$type,'url'=>$server_url,'fromwho'=>$sender_account,'id'=>$id,'date'=>$time),
        'time_to_live'=>0
         );
    }elseif($havefile == false && $type =="text"){
         $field = array(
        'to'=>$recipt_token,             'data'=>array('type'=>'text','title'=>$sender_name,'body'=>$text,'fromwho'=>$sender_account,'id'=>$id,'isreply'=>$isreply,'reply_id'=>$re_id,'date'=>$time),
        'time_to_live'=>0
       );
    }
    
    mysqli_free_result($name_result);
    mysqli_free_result($token);
    return $field;
}

function request_supporter($receiver_account,$sender_account,$link){
    $sql_get = "SELECT * FROM fcm_token WHERE _account ='$receiver_account'";
    $token = execute_sql($link,$sql_get);
    $rowt = mysqli_fetch_assoc($token);
    $recipt_token = $rowt['_fcm_token'];
    $field = array(
        'collapse_key'=>"supporter_request",
        'to' =>$recipt_token,
        'data'=>array('new_supporter'=>"1"),
         'time_to_live'=>0
    );
    mysqli_free_result($token);
    return $field;
}

function sync($receiver_account,$link){
    $sql_get = "SELECT * FROM fcm_token WHERE _account ='$receiver_account'";
    $token = execute_sql($link,$sql_get);
    $rowt = mysqli_fetch_assoc($token);
    $recipt_token = $rowt['_fcm_token'];
    $field = array(
        'collapse_key'=>"sync",
        'to' =>$recipt_token,
        'data'=>array('sync'=>"1"),
        'time_to_live'=>0
    );
    mysqli_free_result($token);
    return $field;
}

function simple_noti($to_who,$body,$title){
    $temp = array(
    'to'=>$to_who,
    'notification'=>array('title'=>$sender_name ,'body'=>$body),
    'time_to_live'=>170000          	 
       );
    return $temp;
}

function send_topic($body){
    $field = array(
    'condition'=>"'news' in topics",
    'notification'=>array('title'=>'新消息','body'=>$body)    
    );
    return $field;
}
    
    
?>