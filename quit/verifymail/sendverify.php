<?php
include("../phpmailer/class.phpmailer.php");
include("../phpmailer/class.smtp.php");
require_once("../dbtools.inc.php");
header("Content-Type:text/html; charset=utf-8");
$link = create_connection();
$user_account = mysqli_real_escape_string($link,$_POST['account']);
$user_mail = mysqli_real_escape_string($link,$_POST['email']);
$requestcode = 404;
$resendmail = false;
$changemail = false;
$check_user = false;
$check_verify = false;
if(isset($_POST['resend'])){
    if($_POST['resend'] == "1"){
        $resendmail = true;
    }
}

if(isset($_POST['mod'])){
    $mod = $_POST['mod'];
}

if($mod != "newemail"){
    
    //sprintf() 函數把格式化的字符串寫入變量中  http://www.w3school.com.cn/php/func_string_sprintf.asp
    $sql = sprintf("SELECT _account FROM user_info WHERE _account ='%s' AND _email ='%s' LIMIT 1",$user_account,$user_mail);
    $result = execute_sql($link,$sql);

    $sql_2 = sprintf("SELECT _account FROM mail_verify WHERE _account = '%s' AND _email = '%s' LIMIT 1",$user_account,$user_mail);
    $result_2 = execute_sql($link,$sql_2);
    if(mysqli_num_rows($result) > 0){
        $check_user = true;
        mysqli_free_result($result);
    }
    if(mysqli_num_rows($result_2) <= 0){
        $check_verify = true;
        mysqli_free_result($result_2);
    }
}else{
    //更新郵件地址
    $sql_change_mail = sprintf("UPDATE user_info SET _email = '%s' WHERE _account = '%s'",$user_mail,$user_account);
    $change_result = execute_sql($link,$sql_change_mail);
    $changemail = true;
}

//找到使用者但未認證 || 重信寄送 || 更改信箱

if(($check_user && $check_verify) || $resendmail || $changemail){
    $rand = rand();                   // 預設rand(0,32767)
    $md5 = md5($user_account.$rand);  //md5加密
    $verify = substr($md5,-5);        // 擷取從字尾開始算的5個字元

     //驗證信連結
     $body = "請點以下連結,進行驗證<br><a href='http://120.96.63.55/quit/verifymail/checkverify.php?account=$user_account&mod=1&verify=$verify'>http://120.96.63.55/quit/verifymail/checkverify.php?account=$user_account&mod=1&verify=$verify</a>"; 
   
    
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
    $mail->FromName = "OitMisServer"; //設定寄件者姓名        
    $mail->Subject = "驗證信 戒菸小幫手"; //設定郵件標題        
    $mail->Body = $body;//設定郵件內容        
    $mail->IsHTML(true); //設定郵件內容為HTML        
    $mail->AddAddress("$user_mail", "使用者您好"); //設定收件者郵件及名稱        

    //驗證錯誤
    if(!$mail->Send()) { 
        $requestcode = 404;  
    //回傳成功訊息
    } else { 
       $sql_3 = "INSERT INTO mail_verify (_account,_email,_verify) VALUES ('$user_account','$user_mail','$verify') ON DUPLICATE KEY UPDATE _email = '$user_mail' , _verify = '$verify'";
        execute_sql($link,$sql_3);
         $requestcode = 200;        
    }
}
echo $requestcode;

mysqli_close($link);

?>