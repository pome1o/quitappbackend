<?php
    require_once("../dbtool.php");
    $con=create_connection();
    $que=htmlentities($_POST["question"],ENT_QUOTES,"utf-8");
    echo $que;
    $sql="insert into qna (question) values ('".$que."')";
    if (!execute_sql($con,"quitsmoking",$sql))
    {
        echo("<br/>Error: " . mysqli_error($con));
    }

    $sql="SELECT * from qna ORDER BY number desc LIMIT 1";
    $result=execute_sql($con,"quitsmoking",$sql);
    $row=mysqli_fetch_assoc($result);
    //echo $row['number'];
    header("Location:question.php?num=".$row['number']."");

?>