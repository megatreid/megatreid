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

<div class="showcustomer">
	<div class="breadcrumbs">Настройка обновления экрана</div>
	<form action="showtickets.php" method="post">
		<p>Автообновление через:
		<span><input type="radio" name="delay" value="0"<?=(!isset($_SESSION['delay']) OR isset($_SESSION['delay'])  AND $_SESSION['delay']==0)?'checked':''?> /> Отключить</span>
		<span><input type="radio" name="delay" value="20"<?=(isset($_SESSION['delay']) AND $_SESSION['delay']==20)?'checked':''?>/> 20 секунд</span>
		<span><input type="radio" name="delay" value="60"<?=(isset($_SESSION['delay']) AND $_SESSION['delay']==60)?'checked':''?>/> 60 секунд</span>
		</p>
		
		<p><input type="checkbox" name="megatreid" value="1">Исполнитель только ООО "Мега Трейд"</p>
		<p>
		<input type="submit" name="send" class="button" value="Сохранить" />
		</p>
	</form>
	<div class="help1">

		<li>После нажатия на кнопку "Сохранить" вы перейдете на страницу "Заявки", на которой будет включено обновление экрана с задержкой в N-секунд.</li>
		<li>Если выбрано поле "Исполнитель только ООО "Мега Трейд", будут отображаться только те заявки, в которых участвует этот исполнитель.</li>
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