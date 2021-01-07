
<?php
    
header("Content-Type:text/html; charset=utf-8");
require_once("dbtools.inc.php");//連接至dbtools.inc.php
$link = create_connection();//使用create_connection方法連線至資料庫

//取得MyVolley.upload_smoke 的資料集
$account =$_POST['account'];
$date=$_POST['date'];
$time=$_POST['time'];

//將資料集切割到陣列裡
$arr_acc = explode("\r\n",$account);
$arr_date = explode("\r\n",$date);
$arr_time = explode("\r\n",$time);

//SQL INSERT INTO 語法 加入資料庫
for($i = 0; $i < (count($arr_acc)-1); $i++)
{   
    $sql = "INSERT INTO smoking_record (account,date,time) VALUES ('".$arr_acc[$i]."','".$arr_date[$i]."','".$arr_time[$i]."')";
    execute_sql($link,$sql);
}

//$output=$account." ".$arr_date." ".$arr_time." 已上傳";
echo(json_encode($output));
mysqli_close($link);

?>