
function sub($user,$link){
$fc=array();
$fn=array();
$get_friend1 = "SELECT _receiver FROM friend_request WHERE _sender='$user' and _state='1'";

$get_friend11 = "SELECT _receiver,_name FROM friend_request INNER JOIN user_info on _sender='$user' AND _state='1' AND friend_request._receiver=user_info._account";

$ex1_friend=execute_sql($link,"quitsmoking",$get_friend1);

$ex11_friend=execute_sql($link,"quitsmoking",$get_friend11);

$rowfriend1=mysqli_fetch_assoc($ex1_friend);
$i=0;
if($rowfriend1!=0)//有朋友
{
    while($row=mysqli_fetch_assoc($ex11_friend))//抓追隨我的
    {
        $fc[$i]=$row["_receiver"];
        $fn[$i]=$row["_name"];
        $i++;
        
    }
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
}