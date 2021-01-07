<?php
include("phpmailer/class.phpmailer.php");
include("phpmailer/class.smtp.php");
require_once("dbtools.inc.php");
header("Content-Type:text/html; charset=utf-8");
$link = create_connection();
$requestcode = 404;
if(isset($_POST['account']) && isset($_POST['mail'])){
    
    $account = mysqli_real_escape_string($link,$_POST['account']);
    $email = mysqli_real_escape_string($link,$_POST['mail']);
    
    $sql = "SELECT _account FROM user_info WHERE _account='$account' AND _email = '$email'";
    $result = execute_sql($link,$sql);
    if(mysqli_num_rows($result) == 1){       
        $rand = rand();
        $md5 = md5($account.$rand);
        $temp_pw = substr($md5,-5);
        
        $body = "您的驗證碼:$temp_pw";
            
        $mail= new PHPMailer(); //建立新物件
        $mail->SMTPDebug = 0;
        $mail->IsSMTP(); //設定使用SMTP方式寄信        
        $mail->SMTPAuth = true; //設定SMTP需要驗證        
        $mail->Host = "120.96.63.55"; //Gamil的SMTP主機        
        $mail->Port = 25;  //Gamil的SMTP主機的SMTP埠位為465埠
        $mail->Username  = "oitmis@120.96.63.55";
        $mail->Password = "oitmis";
        $mail->CharSet = "utf-8"; //設定郵件編碼        
        $mail->From = "oitmis@120.96.63.55"; //設定寄件者信箱        
        $mail->FromName = "亞東戒菸小幫手"; //設定寄件者姓名        
        $mail->Subject = "驗證信 戒菸小幫手"; //設定郵件標題        
        $mail->Body = $body;//設定郵件內容        
        $mail->IsHTML(true); //設定郵件內容為HTML        
        $mail->AddAddress("$email", "使用者您好"); //設定收件者郵件及名稱        

        if(!$mail->Send()) {
            $requestcode = 404;  
        } else { 
            $sql_2 = "INSERT INTO password_change (_account,_temp_pw) VALUES ('$account','$temp_pw') ON DUPLICATE KEY UPDATE _temp_pw = '$temp_pw'";
            execute_sql($link,$sql_2);
            $requestcode = 200;        
        }  
    }
    mysqli_free_result($result);
}

if(isset($_POST['account']) && isset($_POST['oldPw']) && isset($_POST['newPassword'])){
    $account = mysqli_real_escape_string($link,$_POST['account']);
    $temp_pw = mysqli_real_escape_string($link,$_POST['oldPw']);
    
    
    $sql_check = "SELECT * FROM password_change WHERE _account = '$account' AND _temp_pw = '$temp_pw'";
    $result = execute_sql($link,$sql_check);
    if(mysqli_num_rows($result) == 1){
        $newpassword =  password_hash($_POST['newPassword'], PASSWORD_BCRYPT,['COST'=>5]);
        $sql_change = "UPDATE user_info SET _password ='$newpassword' WHERE _account = '$account'";
        execute_sql($link,$sql_change);
        $requestcode = 200;
    }
    mysqli_free_result($result);
}
echo $requestcode;
mysqli_close($link);



?>