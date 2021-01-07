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
  
  <ul data-role="listview" data-inset="true" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
    <?php
    $sql = "SELECT * FROM doctor_qna WHERE _reply = 0 ORDER BY _time DESC";
    $reuslt = execute_sql($link,$sql);
    while($row = mysqli_fetch_assoc($reuslt)){
        echo "<li>";
        $id = $row['_id'];
        echo "<a href='qnacontent.php?num=$id'  data-ajax='false'>";
        echo "<h2>".$row['_context']."</h2>";  
        echo "<p>".$row['_time']."</p></a>";
        echo "</li>";
    }
    mysqli_free_result($reuslt);
    mysqli_close($link);
    
      /*
     echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
      
     echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
      
     echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
      
     echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
     
      echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
      
       echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
      
       echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
      
       echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
      
       echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
      
       echo "<li>";
     echo "<a href='#'>";
     echo "<h2>標題:我該如何戒菸</h2>";     
     echo "<p>time</p></a>";
     echo "</li>";
     
     
     
     */
     
     
     ?>
     </ul>
     <form method="post" action="postquest.php">
        <label for="textarea-1">問題內容:</label>
        <textarea name="textarea-1" id="textarea-1" required></textarea>
        <input type="submit" value="送出" >
    </form>
    </div>
</div>
 </body>


<?php include_once("footer.php"); ?>