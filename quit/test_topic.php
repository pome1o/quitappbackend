<html>
    <?php  header("Content-Type:text/html; charset=utf-8"); ?>
    <body>

        <form method="post" action =<?php $_SERVER['PHP_SELF'] ?> >
        <input type="text" name = 'topic'>
        <button>送</button>
        
        </form>
        
<?php
    
require_once("dbtools.inc.php");
if(isset($_POST['topic'])){
    send_push(send_topic($_POST['topic']));
   
}

?>
</body>
</html>