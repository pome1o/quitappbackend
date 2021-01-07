<?php
header("Content-Type:text/html; charset=utf-8");





$pwsv = '$2y$10$ovE7MmMrdcC8DUbkeqNL7OiicxyPgSvK//UGJkqV1sQj3q7Pa99hm';

echo strlen($pwsv);
echo "<br>";
if (password_verify('666666', $pwsv)){
echo "OK";
}else{
echo "f";
}
?>