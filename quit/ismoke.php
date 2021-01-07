<?php
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();

$user =$_POST['my_account'];
$size =$_POST['size'];
$act =$_POST["friend_account"];
$want = false;
if(isset($_GET['wantsmoke'])){
    $want = true;
}
$temp_data = i_smoke($user,$want,false,$link);
send_push($temp_data);
mysqli_close($link);

?>