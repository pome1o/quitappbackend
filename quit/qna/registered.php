<?php
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>registered</title>
    <script src='https://code.jquery.com/jquery-3.2.1.min.js'></script>
    <script>
    function req(obj){
        $("d").empty()
        if ($('#pas').val() == obj.val()){
            $("d").append("<b>密碼正確</b>")
            console.log('yes')
        }else{ 
            $("d").append("<b>密碼錯誤</b>")
            console.log('no')
        }
    }
    </script>
</head>
<body>
    <form action="regadoptive.php" method="post" name="regform">
          
       姓名：<input type="text" name="name" size="16"><br>
       帳號：<input type="text" name="logAcount" size="16"><br>
       密碼：<input type="password" name="password" id="pas" size="16"><br>
       確認密碼：<input type="password" name="pwcheck" size="16" onblur='req($(this))'><d></d><br>
       電話：<input type="text" name="phonenum" size="16"><br>
       <input type="submit" value="註冊">
    </form>
    
</body>
</html>