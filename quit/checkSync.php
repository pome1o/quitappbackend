<?php
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();
$iscorrect = 0;
if(isset($_POST['my_account'])){
    $account = $_POST['my_account'];
    $account = trim($account);
    $iscorrect = 1;
}

if($iscorrect ==1){
    $sql = "SELECT _sync_friend_request,_sync_new_friend,_status AS _account_status FROM user_info WHERE _account = '".mysqli_real_escape_string($link,$account)."'";
    $result = execute_sql($link,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $json['data'] = $row;
    }
   echo json_encode($json,JSON_UNESCAPED_UNICODE);
    mysqli_free_result($result);
}

mysqli_close($link);


?>