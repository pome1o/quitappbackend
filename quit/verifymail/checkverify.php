<?php
require_once("../dbtools.inc.php");
header("Content-Type:text/html; charset=utf-8");
$link = create_connection();
if(isset($_GET['mod']) && isset($_GET['verify']) && isset($_GET['account'])){
    $mod = $_GET['mod'];
    $account = $_GET['account'];
    $verify = mysqli_real_escape_string($link,$_GET['verify']);
    if($mod == 1){
        
    //在mail_verity 尋找是否有相對應的 verity
    //驗證成功後將其刪除 並更新user_info裡的 status (認證狀態)
        $sql = "SELECT * FROM mail_verify WHERE _account = '$account' AND _verify = '$verify' LIMIT 1";
        $result = execute_sql($link,$sql);
        if(mysqli_num_rows($result) > 0){
            echo "驗證成功";
            $sql_de = "DELETE FROM mail_verify WHERE _account ='$account' AND _verify = '$verify'";
            $sql = "UPDATE user_info SET _status = 1 WHERE _account ='$account'";
            execute_sql($link,$sql_de);
            execute_sql($link,$sql);
        }else{
            echo "驗證失敗";
        }
    }
}
mysqli_free_result($result);
mysqli_close($link);

?>