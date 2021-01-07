<!DOCTYPE html>
<?php
header("Content-Type:text/html; charset=utf-8");
require_once("../dbtools.inc.php");
$link = create_connection();
?>
<?php include_once("htm-head.php") ?>
<script type="text/javascript" ></script>
</head>
 <body>
 <div data-role="page">
   <?php
    if(isset($_GET['num'])){
    $num = $_GET['num'];    
    }
    $sql = "SELECT * FROM doctor_qna WHERE _id = $num OR _reply = $num";
    $result = execute_sql($link,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $reply = $row['_reply'];
        if($reply == 0){
            echo "<h3 class='ui-bar ui-bar-a ui-corner-all'>病患發問內容:</h3>";
            echo "<div class='ui-body ui-body-a ui-corner-all>'";
            echo "<p>".$row['_context']."</p>";
            echo "</div>";
        }else{
            echo "<h3 class='ui-bar ui-bar-a ui-corner-all'>".$row['_name']."醫師回答:</h3>";
            echo "<div class='ui-body ui-body-a ui-corner-all>'";
            echo "<p>你好,想要更容易戒菸可以多出去運動到處走走或是做一些可以轉移目標的事情,讓心情放輕鬆較不容易想抽菸</p>";
            echo "</div>";
        }
    }
    mysqli_free_result($result);
    mysqli_close($link);
    ?>
     <input type="button" id="goback" value="返回">
</div>
</body>
<script>
$("#goback").click(function(){
  window.location = "QnA.php"; 
});
</script>
<?php include_once("footer.php"); ?>