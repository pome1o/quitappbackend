<?php
header("Content-Type:text/html; charset=utf-8"); 
require_once("dbtools.inc.php");
$link = create_connection();
$account = $_POST['account'];
$interval = $_POST['interval'];
$date = $_POST['date'];
$interval = (int)$interval;

$monthORday = true; 
//$monthORday判斷是否小於一天 true   代表大於一天
//                          false 代表小於一天
$user_date = "";
$user_time = "";
if($interval <= 0){
    $interval = 23;
    $monthORday = false;
}



for($i = 0; $i <= $interval; $i++){
    if($interval>0){
        $sql = " SELECT * FROM smoking_record WHERE date = date('$date' - INTERVAL $i day) AND account = '$account' ORDER BY date ASC , time ASC ";
    }else if($interval <=0){
        if($i<10){
            $i = '0'.$i;
        }
        for($i = 0;$i<=23;$i++){
            $sql = "SELECT * FROM smoking_record FROM smoking_record WHERE time >time('$i:00:00') and time<time('$i:59:59') and account = '$account' and date = '$date'";
        }
    }
    $result = execute_sql($link,$sql);
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            $user_date .= $row['date']."*";
            $user_time .= $row['time']."*";
        }
    }else{
        $user_date .= date('Y-m-d',strtotime("$date -$i day"))."*";
        $user_time .= "沒抽菸"."*";
    }
}
$json['friend_all_date'] = $user_date;
$json['friend_all_time'] = $user_time;
mysqli_free_result($result);

$user_count ="";

//SQL指令搜尋資料並用JSOM格式回傳
if($monthORday){
      for($i = 0; $i <= $interval; $i++){
                                                                        //大於一天 一週 或一個月的資料用date
        $sql = " SELECT *,COUNT(date) AS count FROM smoking_record WHERE date = date('$date' - INTERVAL $i DAY) and account = '$account' GROUP BY date";

        $result = execute_sql($link,$sql);
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
            $user_count .= $row['count']."-";
            }
        }else{
            $user_count .= "0"."-";
        }  
    }  
}
else{
    for($i = 0; $i <= $interval; $i++){
                                                                    //小於一天的資料改用time
        $sql = "SELECT *,COUNT(time) AS count FROM smoking_record WHERE time >time('$i:00:00') and time<time('$i:59:59') and account = '$account' and date = '$date'";

        $result = execute_sql($link,$sql);
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
            $user_count .= $row['count']."-";
            }
        }else{
            $user_count .= "0"."-";
        }  
    }  
}

$json['friend_select_count'] = $user_count;
mysqli_free_result($result);
echo json_encode($json,JSON_UNESCAPED_UNICODE);
mysqli_close($link);
?>