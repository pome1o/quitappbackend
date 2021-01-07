<?php
    require_once("dbtool.php");
    $con=create_connection();
    
    $account=$_POST["logAcount"];
    $password=$_POST["password"];
    $name=$_POST["name"];
    $phonenum=$_POST["phonenum"];

    $sql="insert into doctors (account,password,name,phonenum) 
    values ('".$account."','".$password."','".$name."','".$phonenum."')";
    if (!execute_sql($con,"quitsmoking",$sql))
    {
        echo("<br/>Error: " . mysqli_error($con));
    }
    
    header("Location: index.php");

?>