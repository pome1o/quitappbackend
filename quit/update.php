<?php
require_once("dbtools.inc.php");
$link = create_connection();

$act =$_POST['account'];
$name =$_POST["name"]; 
$years =$_POST["years"]; 
$weight =$_POST["weight"]; 
$package=$_POST["package"]; 
$smyears=$_POST["smyears"]; 
$quited=$_POST["quited"]; 
$pcs=$_POST["pcs"]; 



echo $sql = "UPDATE user_info SET name='$name',years=$years,weight=$weight,package=$package,smyears=$smyears,quited=$quited,pcs=$pcs WHERE account='$act'";
execute_sql($link,"quitsmoking",$sql);
mysqli_close($link);

?>