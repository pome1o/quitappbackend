<?php
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();
$user =$_POST['my_account'];
$fc=$_POST['friend_account'];
$method=$_GET['friend_method'];
//$sql_get_name = "SELECT * FROM user_info WHERE account ='$user'";
//$friend_request="SELECT name FROM friend_request where receiver='$user'";
function refuse($user,$fc,$link){
    
    $refuse="DELETE FROM friend_request WHERE _sender = '$fc' and _receiver='$user'";
    $refuse="DELETE FROM friend_request WHERE _receiver = '$fc' and _sender='$user'";
//$name_result = execute_sql($link,$sql_get_name);
    if(execute_sql($link,$refuse)){
        $json['ck'] = "delete work"; //傳回android
        $json['account'] = $friend;
    }else{
        $json['ck'] = "delete fail";
    }
    echo json_encode($json,JSON_UNESCAPED_UNICODE);
}

function accept($user,$fc,$link){
    $yes="UPDATE friend_request SET _state = '1' WHERE _sender = '$fc' and _receiver='$user'";
    $yes2="UPDATE friend_request SET _state = '1' WHERE _receiver = '$fc' and _sender='$user'";
    //$name_result = execute_sql($link,$sql_get_name);
    if(execute_sql($link,$yes)){ 
       execute_sql($link,$yes2);
        $json['ck'] = "update work"; 
        $json['account'] = $fc;
        $sql = "UPDATE user_info SET _sync_new_friend = '1' WHERE _account = '$fc'";
        execute_sql($link,$sql);
        $temp_data = sync($fc,$link);
        send_push($temp_data);
    }else{
        $json['ck'] = "update fail";
    }
    echo json_encode($json,JSON_UNESCAPED_UNICODE);
}

function add($user,$fc,$link){
   
    $sql_friend = "SELECT * FROM user_info WHERE _account = '$fc'";
    $request_check="SELECT * FROM friend_request WHERE _sender='$user' AND _receiver='$fc'";

    $friend_exist = execute_sql($link,$sql_friend);
    $request_exist = execute_sql($link,$request_check);
   
    $rowfriend = mysqli_fetch_assoc($friend_exist);
    $rowcheck = mysqli_fetch_assoc($request_exist);

    if($rowcheck!=0){
        $json['ck']="requesting_exist";//已追隨
    }elseif($rowfriend==true && $rowcheck == 0){
        $type = $rowfriend['_type'];
        $addfriend1="INSERT INTO friend_request (_sender,_receiver,_state,_type) VALUES ('$user','$fc','0','$type')";
        $addfriend2 ="INSERT INTO friend_request (_receiver,_sender,_state,_type) VALUES ('$user','$fc','0','$type')";
        if(execute_sql($link,$addfriend1)){
            execute_sql($link,$addfriend2);
            $json['ck'] = "add_work"; //傳回android
            
            $sql = "UPDATE user_info SET _sync_friend_request ='1' WHERE _account ='$fc'";
            execute_sql($link,$sql);
            
            $temp_data = request_supporter($fc,$user,$link);
            send_push($temp_data);
        }
    }else{
        $json['ck'] = "add_fail";
    }
    echo json_encode($json,JSON_UNESCAPED_UNICODE);
    mysqli_free_result($friend_exist);
    mysqli_free_result($request_exist);

}

function sponsor_list($user,$link){
    $fc=array();
    $fn=array();
    $user_type = array();
    $get_supporter = "SELECT _receiver,_name,user_info._type FROM friend_request INNER JOIN user_info on _sender='$user' AND _state='1' AND friend_request._receiver=user_info._account";
    
    $result_supporter = execute_sql($link,$get_supporter);
 
    if(mysqli_num_rows($result_supporter) > 0){
            $i =0;
        while($row = mysqli_fetch_assoc($result_supporter)){
            $fc[$i]=$row["_receiver"];
            $fn[$i]=$row["_name"];
            $user_type[$i] = $row['_type'];
            $i++;
            }
        
        $json['ck'] = "work"; //傳回android
        $json['account']=$fc;
        $json['name']=$fn;
        $json['user_type'] = $user_type;
    }else{
     $json['ck'] = "no friend";
    }
echo json_encode($json,JSON_UNESCAPED_UNICODE);
mysqli_free_result($result_supporter);
}

function supporter_request($user,$link){
    $fc=array();
    $fn=array();
    $user_type = array();
    $friend_request="SELECT * FROM friend_request where _receiver='$user' and _state='0'";
    //帳號和
    $account="SELECT _sender,_name,user_info._type FROM friend_request INNER JOIN user_info on _receiver='$user' AND _state='0' AND friend_request._sender=user_info._account";
    //抓到帳號
    $friend_R =execute_sql($link,$friend_request);
    //抓帳號用
    $ac_result=execute_sql($link,$account);
    $rowfriend = mysqli_fetch_assoc($friend_R);
    $i=0;
    //開始抓名字和帳號
    if($rowfriend==true){
        while($row=mysqli_fetch_assoc($ac_result)){
            $fc[$i]=$row["_sender"];
            $fn[$i]=$row["_name"];
            $user_type[$i] = $row["_type"];
            $i++;
        }    
        $json['ck'] = "ok";
        $json['data'] = $fc;
        $json['name'] = $fn;
        $json['user_type'] = $user_type;
    }else{
        $json['ck']="norequest";
    }
    echo json_encode($json,JSON_UNESCAPED_UNICODE);
    mysqli_free_result($ac_result);
    mysqli_free_result($friend_R);
    
}

function delete_supporter($user,$fc,$link){
    $re1_check="SELECT * FROM friend_request where _sender = '$fc' and _receiver='$user' and _state='1'";
    $re2_check="SELECT * FROM friend_request where _sender = '$user' and _receiver='$fc' and _state='1'";

    $rerow1=execute_sql($link,$re1_check);
    $rerow2=execute_sql($link,$re2_check);
    
    $refuse1="DELETE FROM friend_request WHERE _sender = '$fc' and _receiver='$user' and _state='1'";
    $refuse2="DELETE FROM friend_request WHERE _sender = '$user' and _receiver='$fc' and _state='1'";
    //$name_result = execute_sql($link,$sql_get_name);
    if(mysqli_num_rows($rerow1) > 0){
        
        $sql = "UPDATE user_info SET _sync_new_friend ='1' WHERE _account ='$fc'";
        execute_sql($link,$sql);
        
        if(execute_sql($link,$refuse1))//刪成功
        {
            $json['ck'] = "delete work"; //傳回android
            $json['account'] = $friend;
            $temp_data = sync($fc,$link);
            send_push($temp_data);
        }
        else{
            $json['ck'] = "delete fail";
        }
    }else{
        $json['ck'] = "delete fail";
    }
    if(mysqli_num_rows($rerow2) > 0){
        if(execute_sql($link,$refuse2)){
            $json['ck'] = "delete work"; //傳回android
            $json['account'] = $friend;
        }
        else{
            $json['ck'] = "delete fail";
        }
    }
    else{
        $json['ck'] = "delete fail";
    }
   echo json_encode($json,JSON_UNESCAPED_UNICODE);

}

switch($method){
    case "addfriend":
        add($user,$fc,$link);
        break;
    case "refuse":
        refuse($user,$fc,$link);
        break;
    case "accept":
        accept($user,$fc,$link);
        break;
    case "supporter_list":
        supporter_list($user,$link);
        break;
    case "supporter_request":
        supporter_request($user,$link);
        break;
    case "delete_supporter":
        delete_supporter($user,$fc,$link);
        break;
    case "sponsor_list":
        sponsor_list($user,$link);
        break;
    default:
        break;           
    }

mysqli_close($link);
?>