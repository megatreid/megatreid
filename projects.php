<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<3)
{
	require_once '/blocks/header.php';
	?>
	<!DOCTYPE html>
	<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>Проекты и тарифы</title>
	</head>
	<body>

	</body>
	</html>
	<?php
}
else
{
	header('Location: /');
}
?>