<!DOCTYPE html>
<html>
<head>
   <?php include_once("../htm-head.php") ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" >
            function expend(){
                if(document.getElementById('myid').style.display=='none'){
                    document.getElementById('myid').style.display = 'block';
                }
                else{
                    document.getElementById('myid').style.display='none'
                }
            }
            function back(){
                window.location.href='client_mobile.php'
            }
            function cleandef(obj){
                
                if(obj.value=="把您想回覆的答案打在這裡：(限255個字)")
                {
                    obj.value="";
                }
            }
            
        </script>
</head>
<body>
    <?php
    session_start();
    require_once("../dbtool.php");
    $con=create_connection();

//    echo $_GET['num']."<br/>";
    $num=$_GET['num'];
    
    $sql="SELECT * from qna where number = ".$num;
    $result=execute_sql($con,"quitsmoking",$sql);
    $row=mysqli_fetch_assoc($result);
    
    
    echo "<h3 class='ui-bar ui-bar-a ui-corner-all'>病患發問內容:</h3>";
//    echo "<h3 class='ui-bar ui-bar-a ui-corner-all'>發問時間:".$row['time']."</h3>";
    echo "<div class='ui-body ui-body-a ui-corner-all>'";
    echo "<p>".$row['question']."</p>";
    echo "</div>";
    
    echo "<form action='answer.php?num=".$num."' method='post'>";
    if(!empty($row['answer'])){
        
        echo "<h3 class='ui-bar ui-bar-a ui-corner-all'>".$row['respondent']."醫師回答:</h3>";
        echo "<div class='ui-body ui-body-a ui-corner-all>'";
        echo "<p>".$row['answer']."</p>";
        echo "</div>";
        
    }
    
    ?>
    </form>
    <button type='submit' onclick='back()'>返回</button>
</body>
</html>

