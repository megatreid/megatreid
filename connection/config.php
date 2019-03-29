<?php
session_start();
$host = "192.168.70.32"; // ЗДЕСТЬ сменить megatreid на ip-адрес сервера без номера порта!
$db_name = "megatreid";
$login = "baseuser";
$pswrd = "qazwsxedc";
$connect = @mysql_connect ("$host", "$login", "$pswrd");
if (!$connect) {
	$host = "megatreid";
    $connect = @mysql_connect ("$host", "$login", "$pswrd");
	@mysql_set_charset("utf8");
}
if(!$connect) {
	header('Location: /errors/404.htm');
}  
else 
{
    mysql_select_db ("$db_name", $connect);
	mysql_set_charset("utf8");
	global $connect; 
	mysql_query ("SET NAMES 'UTF-8'");
	$link = mysqli_connect($host, $login, $pswrd, $db_name) or die("Ошибка " . mysqli_error($link));
	//$user = "admin";
	//$userpassword = "qwerty";
	include 'func/db.php';

	
}
?>