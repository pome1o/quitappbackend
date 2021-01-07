<?php
require_once("dbtools.inc.php");
header("Content-Type:text/html; charset=utf-8"); 
$link = create_connection();
if(isset($_POST['account'])){
    $thesmoker = $_POST['account'];
    $sqlsmoker = "SELECT _fcm_token From fcm_token WHERE _account = '$thesmoker'";
    $result = execute_sql($link,$sqlsmoker);
    while($row = mysqli_fetch_row($result)){
        $thesmoker_token = $row[0];
    }
    mysqli_free_result($result);
    send_push(simple_noti($thesmoker_token,"有人看到你抽菸了了!記得紀錄","小幫手"));
    send_push(i_smoke($thesmoker,false,true,$link));    
}
mysqli_close($link);

?>