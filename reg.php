<?php
	require '/connection/config.php';
	//require '/func/db.php';
	//$link = mysqli_connect($host, $login, $pswrd, $db_name) or die("Ошибка " . mysqli_error($link)); 
	$data = $_POST;
	$email = $_SESSION['email'];
	$mobile = $_SESSION['mobile'];
	$loginuser = $_SESSION['login'];
	$name = $_SESSION['name'];
	$surname = $_SESSION['surname'];
	$th_name = $_SESSION['th_name'];
	$password = $_SESSION['password'];
	$result = Add_User ($link, $loginuser, $password, $surname, $name, $th_name, $email, $mobile, 3); // Добавление юзера через пользовательскую функцию
	unset($_SESSION['email']);
	unset($_SESSION['mobile']);
	unset($_SESSION['login']);
	unset($_SESSION['name']);
	unset($_SESSION['surname']);
	unset($_SESSION['th_name']);
	unset($_SESSION['password']);
	/*if($result)
	{
		mysqli_close($link);
		header('location: /sign-up-success.php');
		exit;
	}*/

?><?php require_once '/blocks/header.php';?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Регистрация прошла успешно</title>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">

</head>
<body>
<div class="registration">
<p>Регистрация прошла успешно</p>
			<p>Через 5 секунд вы перейдете обратно на
				<a href='/'>ГЛАВНУЮ СТРАНИЦУ</a>
			</p>
</div>
	<script>
		setTimeout(function() {
		
			window.location.href = '/';
		
		}, 5000);
	</script>
	
</body>
</html>