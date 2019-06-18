<?php require 'connection/config.php';

if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3)
{
	require_once 'blocks/header.php';
	require 'func/arrays.php';
?>	
	
	




	
<?php
}
else
{
	header('Location: /');
}
?>	