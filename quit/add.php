<?PHP

header("Content-Type:text/html; charset=utf-8");
require_once("dbtools.inc.php");
$link = create_connection();


$name = $_POST['user_name'];
$account =$_POST['my_account'];
$mail = $_POST['email'];
$user_type = $_POST['user_type'];

//帳號重複
$sql_check ="SELECT * FROM user_info WHERE _account = '$account'";
$ch_result = execute_sql($link,$sql_check);
$mycheck = mysqli_num_rows($ch_result);
if($mycheck!=0)
{
  $jsn['ck'] = 'repeat';  
  
}
else
{

    //password_hash 加密
    $hash_pwd = password_hash($_POST['password'], PASSWORD_BCRYPT,['COST'=>5]);
     $sql = "INSERT INTO user_info (_name,_account,_password,_email,_type) VALUES ('$name','$account','$hash_pwd','$mail','$user_type')";
    $re = execute_sql($link,$sql);
    if($re==true)
    {     
        $jsn['ck'] = 'nice';
    }else{
          $jsn['ck'] = 'fail';
        }
}
echo  json_encode($jsn,JSON_UNESCAPED_UNICODE);
mysqli_free_result($ch_result);
mysqli_close($link);



?>