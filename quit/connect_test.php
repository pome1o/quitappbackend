<?php
if(isset($_POST['connect'])){
    $json['ck'] = "connect";
}else{
    $json['ck'] = "error";
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>