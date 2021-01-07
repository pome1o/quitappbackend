<?php
header("Content-Type:text/html; charset=utf-8"); 
require_once("../dbtools.inc.php");
$interval = $_POST['interval'];
$id = $_GET['id'];
$link = create_connection();
date_default_timezone_set('Asia/Taipei');
$date = new DateTime('now');
$b = array();

$data = mcrypt_decrypt("rijndael-128","smokingisnotgood1234567quitsmoke",base64_decode($id),"cbc","ABCDEF12345678PL");
$padding = ord($data[strlen($data)-1]);
$realId = substr($data,0,-$padding);    


switch($interval){
    case 1:
        $day = new DateInterval('P1D');
        for($i = 0; $i<7;$i++){
            $time = $date->format('Y-m-d');
            $date->sub($day);
            $sql = "SELECT COUNT(*) AS per  FROM smoking_record WHERE account = '$realId' AND date = '$time'";
            $result = execute_sql($link,$sql);
            while($row = mysqli_fetch_assoc($result)){
                $per = $row['per'];
            }
            $array_t = array("date"=>$time,"per"=>$per);
            $b[$i] = $array_t;
        }
        break;
    case 2:
        break;
    case 3:
        break;
}
echo json_encode($b);

?>