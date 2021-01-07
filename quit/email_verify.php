<?php
include("./phpmailer/class.phpmailer.php");
include("./phpmailer/class.smtp.php");
require_once("dbtools.inc.php");
header("Content-Type:text/html; charset=utf-8");
//$link = create_connection();
/*$sender_account = $_POST['my_account'];
$email = $_POST['_email'];
$verify ='564SOIFJ';*/
echo "fuck";  

$mail= new PHPMailer(); //建立新物件
$mail->SMTPDebug = 4;
$mail->IsSMTP(); //設定使用SMTP方式寄信        
$mail->SMTPAuth = true; //設定SMTP需要驗證        
$mail->Host = "120.96.63.55"; //Gamil的SMTP主機        
$mail->Port = 25;  //Gamil的SMTP主機的SMTP埠位為465埠
$mail->Username  = "oitmis@120.96.63.55";
$mail->Password = "oitmis";
$mail->CharSet = "utf-8"; //設定郵件編碼        
$mail->From = "oitmis@120.96.63.55"; //設定寄件者信箱        
$mail->FromName = "測試人員"; //設定寄件者姓名        

$mail->Subject = "PHPMailer 測試信件"; //設定郵件標題        
$mail->Body = "大家好,       
這是一封測試信件!       
"; //設定郵件內容        
$mail->IsHTML(true); //設定郵件內容為HTML        
$mail->AddAddress("line6238@gmail.com", "茶米"); //設定收件者郵件及名稱        

if(!$mail->Send()) {        
echo "Mailer Error: " . $mail->ErrorInfo;  
} else {        
echo "Message sent!";        
} 



?>