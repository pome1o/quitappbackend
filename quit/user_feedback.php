<?php
header("Content-Type:text/html; charset=utf-8");
require_once("dbtools.inc.php");

if(isset($_POST['sdk_version']) && isset($_POST['brand']) && isset($_POST['feedback'])){
    $link = create_connection();
    $sdk = $_POST['sdk_version'];
    $brand = $_POST['brand'];
    $feedback = $_POST['feedback'];
    $sql = "INSERT INTO user_feedback (_android_version,_brand,_feedback) VALUES ('$sdk','$brand','$feedback')";
    execute_sql($link,$sql);
    mysqli_close($link);
    $request = "correct";
}else{
    $request = "error";
}
$json['request'] = $request;
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>