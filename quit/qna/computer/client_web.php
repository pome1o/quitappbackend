<?php session_start();
header("Content-Type:text/html; charset=utf-8");?>
<!doctype html>
<html>
   <?php include_once("../htm-head.php");
    require_once("../dbtool.php");?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" >
            function checkdata(){
                if($('#que').val()==""||
                  $('#que').val()=="把您想問的問題打在這裡：(限255個字)"){
                    alert("欄位不可以空白！");
                    $('#que').text("");
                    $('#que').focus();
                }
                else  
                {
                    queform.submit();    
                }
            }
            function cleandef(){
                if($('#que').val()=="把您想問的問題打在這裡：(限255個字)")
                {
                    $('#que').text("");
                }
            }
        </script>
    </head>
    
    <body>
       <div data-role="page">
        <div role="main" class="ui-content">

        <ul data-role="listview" data-inset="true" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
        <?php
//            echo "您好，".$_SESSION['name']."<br>";
            
            $con=create_connection();
            $sql="SELECT * from qna order by number desc";
            $result=execute_sql($con,"quitsmoking",$sql);
            echo "<h3 class='ui-bar ui-bar-a ui-corner-all'>問題清單</h3>";
            if(!mysqli_num_rows($result)==0){
                while($row=mysqli_fetch_assoc($result))
                {
                    $que=html_entity_decode($row['question'],ENT_QUOTES,"utf-8");
                    if(mb_strlen($que,'utf-8')>25){
                        $que=mb_substr($que,0,25,"utf-8")."...";
                    }
                    echo "<li>";
                    echo "<a href='question.php?num=".$row['number']."'  data-ajax='false'>";
                    echo "<h2>".$que."</h2>";  
                    echo "<p>".$row['time']."</p></a>";
                    echo "</li>";
                }
            }else{
                echo "目前沒有患者有任何問題";
            }
            
//                echo $count."<br/>";
            mysqli_free_result($result);
            mysqli_close($con);
        ?>
        
            
        </div>
    </div>
    </body>
</html>