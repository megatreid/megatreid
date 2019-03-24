<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']==1)
{
require_once '/blocks/header.php';
unset($_SESSION['implementer']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Настройка обновления экрана</title>
	<script type="text/javascript" src='js/jquery.js'></script>
</head>
<body>
<!--
<div class="showcustomer">
	<div class="breadcrumbs">Настройка обновления экрана</div>
	<form action="showtickets.php" method="post">
		<p>Автообновление через: <input type="number" value="0" name="delay" /> секунд</p>
		<p><input type="checkbox" name="megatreid" value="1">Исполнитель только ООО "Мега Трейд"</p>
		<p>
		<input type="submit" name="send" value="Сохранить" />
		</p>
	</form>
</div>
<a href="#openModal">Открыть модальное окно</a>
-->
<div id="openModal" class="modalDialog">
	<div>
		<a href="#close" title="Закрыть" class="close">X</a>
<div class="showcustomer">
	<div class="breadcrumbs">Настройка обновления экрана</div>
	<form action="showtickets.php" method="post">
		<p>Автообновление через: <input type="number" value="0" name="delay" /> секунд</p>
		<p><input type="checkbox" name="megatreid" value="1">Исполнитель только ООО "Мега Трейд"</p>
		<p>
		<input type="submit" name="send" value="Сохранить" />
		</p>
	</form>
</div>
	</div>
</div>















<div id="footer">&copy; ООО "МегаТрейд"</div>
</body>
</html>











<?php
	}
	else
	{
		header('Location: /');
	}
?>