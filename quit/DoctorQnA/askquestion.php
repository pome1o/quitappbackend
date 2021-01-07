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
    <div role="main" class="ui-content">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <label for="textarea-1">問題內容:</label>
        <textarea name="textarea-1" id="textarea-1"></textarea>
        <input type="submit" value="送出">
       
    </form>
       
    </div>
</div>
 </body>
<?php
 $text = $_POST['textarea-1'];
 $sql = "INSERT INTO doctor_qna (_context) VALUES ('$text')";
 execute_sql($link,$sql);
 echo "<script>alert('警告：將在確認之後跳頁'); location.href = 'QnA.php';</script>";
?>

<?php include_once("footer.php"); ?>