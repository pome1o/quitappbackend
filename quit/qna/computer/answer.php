<?php
    session_start();
    echo $_POST['answer']."<br>";
    echo $_GET['num']."<br>";
    require_once("../dbtool.php");
    $con=create_connection();
    
    $ans=htmlentities($_POST["answer"],ENT_QUOTES,"utf-8");
    $num=$_GET['num'];
    $sql="update qna SET answer = '".$ans."',respondent = '".$_SESSION['name']."' where number = '".$num."'";
    if (!execute_sql($con,"quitsmoking",$sql))
    {
        echo("<br/>Error: " . mysqli_error($con));
    }

    header("Location:question.php?num=".$num);
?>