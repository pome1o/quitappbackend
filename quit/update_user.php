<?php
header("Content-Type:text/html; charset=utf-8");
require_once("dbtools.inc.php");
$link = create_connection();

$act =$_POST['my_account'];
$data_name = $_POST['data_name'];
$data_value = $_POST['data_value'];
$temp_array =array();
for($i = 0;$i < count($data_name); $i++){
    $data_name[$i];
    $data_value[$i];
    $temp_array[$i] = $data_name[$i]. " = '".$data_value[$i]."'";
}
echo $sql = "UPDATE user_info SET ".join(",",$temp_array) ." WHERE _account='$act'";
execute_sql($link,$sql);
mysqli_close($link);

?>