<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta charset="utf-8">
        <script type="text/javascript">
            
            if(checkserAgent()){
                document.location.href="mobile/client_mobile.php";
            }
            
            function checkserAgent(){
                var userAgentInfo=navigator.userAgent;
                var userAgentKeywords=new Array("Android", "iPhone" ,"SymbianOS", "Windows Phone", "iPad", "iPod", "MQQBrowser");
                var flag=false;
                if(userAgentInfo.indexOf("Windows NT")==-1){
                flag=true;
                }
                return flag;
            }
            
            function checkdata()
            {
                if(document.passwordform.logaccount.value.length==0)
                {
                    alert("帳號欄位不可以空白！"); 
                    return false;
                }
                else if(document.passwordform.logpassword.value.length==0)
                {
                    alert("密碼欄位不可以空白！");
                    return false;
                }
                else  
                {
                    passwordform.submit();    
                }
            }
        </script>
        
</head>
<body>
    
    <form method="post" align = "center" action="signcheck.php" name="passwordform">
        
        輸入帳號：<input type="text" name="logaccount" size="16"><br>
        輸入密碼：<input type="password" name="logpassword" size="16"><br>
        <input type="submit" value="登入" onclick="checkdata()">
        <a href="registered.php">註冊</a>
    </form>
            
</body>
</html>