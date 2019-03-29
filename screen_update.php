<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=4)
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
<br>
<div class="showcustomer">
<div class="contr_registr">	
	<div class="breadcrumbs">Настройка обновления экрана</div>
	
	<form action="showtickets.php" method="post">
	<table>
		<tr>
			<td  width=1% align="right" rowspan="4">Автообновление через:</td>
			<td   width=1%><input type="radio" name="delay" value="0"<?=(!isset($_SESSION['delay']) OR isset($_SESSION['delay'])  AND $_SESSION['delay']==0)?'checked':''?> /> Отключить</td>
		</tr>
		<tr>
			<td><input type="radio" name="delay" value="20"<?=(isset($_SESSION['delay']) AND $_SESSION['delay']==20)?'checked':''?>/> 20 секунд</td>
		</tr>
		<tr>
			<td><input type="radio" name="delay" value="60"<?=(isset($_SESSION['delay']) AND $_SESSION['delay']==60)?'checked':''?>/> 60 секунд</td>
		</tr>
		<tr>
			<td><input type="radio" name="delay" value="300"<?=(isset($_SESSION['delay']) AND $_SESSION['delay']==300)?'checked':''?>/> 5 минут</td>
		</tr>

		<tr>
			<td colspan="2" align="center"><p><input type="checkbox" name="megatreid" value="1">Исполнитель только ООО "Мега Трейд"</td>
		</tr>

		<tr><td align="center" colspan="2"><input type="submit" name="send" class="button" value="Сохранить" /></td></tr>

	</table>	
	</form>
	<div class="help1">

		<li>После нажатия на кнопку "Сохранить" вы перейдете на страницу "Заявки", на которой будет включено обновление экрана с задержкой в N-секунд.</li>
		<li>Если выбрано поле "Исполнитель только ООО "Мега Трейд", будут отображаться только те заявки, в которых участвует этот исполнитель.</li>
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