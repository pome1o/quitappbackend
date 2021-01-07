<?php
    session_start();
    require_once("dbtool.php");
    $con=create_connection();
    
    $account=$_POST["logaccount"];
    $password=$_POST["logpassword"];
    

    $sql="select * from doctors where account = '".$account."'";
    
    if (!execute_sql($con,"quitsmoking",$sql))
    {
        echo("<br/>Error: " . mysqli_error($con));
    }else{
        $result=execute_sql($con,"quitsmoking",$sql);
        $row=mysqli_fetch_assoc($result);
    }
    

    if(!empty($row['account'])){
        if($row['password']==$password){
            $_SESSION['name']=$row['name'];
            header("Location: computer/client_web.php");
        }
        echo "<script>alert('密碼錯誤'); location.href = 'index.php';</script>";
    }else{
        echo "<script>alert('無此帳號'); location.href = 'index.php';</script>";
    }

?>