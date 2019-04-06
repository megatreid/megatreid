<?php

$host = "megatreid"; // ЗДЕСТЬ сменить megatreid на ip-адрес сервера без номера порта!
$db_name = "megatreid";
$login = "baseuser";
$pswrd = "qazwsxedc";
//$connect = @mysql_connect ("$host", "$login", "$pswrd");
$link = mysqli_connect($host, $login, $pswrd, $db_name) or die("Ошибка " . mysqli_error($link));
if(!$link) {
	header('Location: /errors/404.htm');
}  
else 
{
   /* mysql_select_db ("$db_name", $connect);
	mysql_set_charset("utf8");
	global $connect; 
	mysql_query ("SET NAMES 'UTF-8'");
	*/
	
	//$user = "admin";
	//$userpassword = "qwerty";
	include 'd:/openserver/domains/megatreid/func/db.php';

	
}
?>