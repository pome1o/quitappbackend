<?php
header("Content-Type:text/html; charset=utf-8");
require_once("dbtools.inc.php");
$link = create_connection();
  
if(isset($_POST['my_account'])&& isset($_POST['password'] )){
  $account =$_POST['my_account'] ; 
  $pwd =$_POST['password'];
}else{
    $account = "qwertyuiolkjhgfrfcy";
    $pwd = "aaa";
}
  $sql = "SELECT _password FROM  user_info where _account = '$account'";
  $result = execute_sql($link,$sql);

  if(mysqli_num_rows($result)<=0)
   {
	     $jsonobj[0] = array('log'=>'no_account');
   }
   else{
       
   $data_pw = mysqli_fetch_assoc($result);    
   $pwsv = $data_pw['_password'];
   
       //用password_verify()驗證 hash 加密
   if(password_verify($pwd, $pwsv)){   
       
        $sqli = "SELECT _name,_type,_sex,_years,_email,_package,_pcs,_smoke_years,_smoking_score,_quited,_weight,_package_money,_status FROM  user_info where _account = '$account'";
        $result_data = execute_sql($link,$sqli);
        $data = mysqli_fetch_assoc($result_data);
        $jsonobj[0] = array('log'=>'correct');
        $jsonobj[1] = array('data'=>$data);
        mysqli_free_result($result_data);
       
	}else{
	    $jsonobj[0] = array('log'=>'wrongpw');
    }	   
  }

echo json_encode($jsonobj,JSON_UNESCAPED_UNICODE);
mysqli_free_result($result);
mysqli_close($link);

?>
