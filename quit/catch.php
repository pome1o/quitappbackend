<?php

header("Content-Type:text/html; charset=utf-8");
require_once("dbtools.inc.php");
$account =$_POST['my_account'];
$link = create_connection();
$sql="SELECT * FROM  user_info where  account='$account'"; //同樣接post
$result=execute_sql($link,$sql);
$json1 = array();
 /*while($row=mysqli_fetch_assoc($result)){
 $tmp[]=$row;
 }*/
while($row=mysqli_fetch_object($result))
{
$json['sex'] = $row->_sex;
$json['name'] = $row->_name;
$json['years'] = $row->_years;
$json['weight'] = $row->_weight;
}



//echo json_encode($tmp);


echo json_encode($json,JSON_UNESCAPED_UNICODE);
mysqli_close($link);


?>