<?php
	$wwwlink=$_SERVER['HTTP_REFERER'];
	require '/connection/config.php';
	unset($_SESSION['auch']);
	unset($_SESSION['count']);
	unset($_SESSION['io']);
	unset($_SESSION['userlevel']);
	unset($_SESSION['login']);
	unset($_SESSION['email']);
	unset($_SESSION['id_edit']);
	unset($_SESSION['user_id']);
	unset($_SESSION['ticket_status']);
	//echo $wwwlink;
	//header('location: '.$wwwlink.'');
	header('location: /');
	?>