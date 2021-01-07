<!DOCTYPE html>
<?php
header("Content-Type:text/html; charset=utf-8");
require_once("../dbtools.inc.php");
$link = create_connection();

if(isset($_POST['textarea-1'])){
 $text = $_POST['textarea-1'];
 $sql2 = "INSERT INTO doctor_qna (_context) VALUES ('$text')";
 execute_sql($link,$sql2);
 header("Location:QnA.php");
    
}
mysqli_close($link);

?>