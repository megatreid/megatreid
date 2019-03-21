<!DOCTYPE HTML public "-//W3C//DTD HTML 3.2//EN">
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<META HTTP-EQUIV=CONTENT-TYPE CONTENT="text/html; charset=windows-1251">
		<meta name="viewport" content="width=1024, initial-scale=1">
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
		<!--<script src="//libs.raltek.ru/libs/jquery/1.8.3/js/jquery-1.8.3.js"></script> -->
		<script type="text/javascript" src='/js/jquery.min.js'></script>
		<script type="text/javascript" src='/index.js'></script>
		<script type="text/javascript" src='jquery.js'></script>
		<script type="text/javascript" src='selects.js'></script>
		<script type="text/javascript" src="js/jquery.maskedinput.min.js"></script>
		<link rel="stylesheet" href="../css/index.css">
		<link rel='shortcut icon' href='/images/favicon.ico' type='image/x-icon'>
		<script type="text/javascript">
		  function digitalWatch() {
			var date = new Date();
			var day = date.getDate();
			if (day < 10) day = "0" + day;
			var month = date.getMonth()+1;
			if (month < 10) month = "0" + month;
			var year = date.getFullYear();
			var hours = date.getHours();
			var minutes = date.getMinutes();
			var seconds = date.getSeconds();
			if (hours < 10) hours = "0" + hours;
			if (minutes < 10) minutes = "0" + minutes;
			if (seconds < 10) seconds = "0" + seconds;
			document.getElementById("digital_watch").innerHTML =day+"-"+month+"-"+ year +"   "+ hours + ":" + minutes + ":" + seconds;
			setTimeout("digitalWatch()", 1000);
		  }
		</script>
	</head>
<BODY onload="digitalWatch()">
	<p id="digital_watch" class="watch"></p>
		<div class="header">
			<a name="top"></a>
			<div class="logo">
				<a href="/"><img src="images/megatreid.png" align="middle" alt="ООО МЕГАТРЕЙД" width="100" height="101"></a>
			</div>
		</div>
			<div class="systemname">
				<h2>Управление проектами ООО "Мега Трейд"</h2>
			</div>
			<div id="clock"></div>
		<div class="auth-menu">
			<?php if(isset($_SESSION['auch'])){
			if($_SESSION['auch'] == 1) { ?>
				<p><?= "Добрый день,<br>".$_SESSION["io"] ."!"?></p>
				<a href='lk.php'><button class="button-new">Личный кабинет</button></a>
				<a href='logout.php'><button class="button-new">Выйти</button></a>
			<?php } 
			if($_SESSION['auch'] == 2) {?>
			<div><br><br> Неправильный логин или пароль! </div>
			<div  class="authmenu_reg">
				<p>
				<form action="/auth.php" method="POST">
					<label>Логин:</label>
					<input type="text" name="login"/>
					<label>Пароль:</label>
					<input type="password" name="userpassword"/>
					<button type="submit" name="do_login"  class="button-new">Войти</button>
				</form>
				</p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if(!isset($_SESSION['auch'])){ ?>
			<div><br><br> Пожалуйста, авторизуйтесь! </div>
			<div class="authmenu_reg">
				<p>
					<form action="/auth.php" method="POST">
						<label>Логин:</label>
						<input type="text" name="login"/>
						<label>Пароль:</label>
						<input type="password" name="userpassword"/>
						<button type="submit" name="do_login" class="button-new">Войти</button>
					</form>
				</p>
			</div>
			<?php } ?>
		</div>
		<nav class="menu" role="navigation">
			<ul>
				<li><a href="/">Главная</a></li>
				<?php if(isset($_SESSION['auch']) AND $_SESSION['auch'] == 1 AND $_SESSION['userlevel'] AND $_SESSION['userlevel'] <=3) {?>
				<li><a href="showtickets.php">Заявки</a>
					<ul>
						<li><a href="newticket.php">Создать заявку</a></li>
					</ul>
				</li>
				<li><a href="showcustomer.php">Заказчики</a></li>
				<li><a href="showcontractor.php">Подрядчики</a></li>
				<li><a href="">Отчеты</a>
					<ul>
						<li><a href="report_by_customer.php">Отчет по заказчикам</a></li> 
						<li><a href="report_by_contractor.php">Отчет по подрядчикам</a></li>
						<li><a href="objects_with_abon.php">Объекты с абонентской платой</a></li>
					</ul>
				</li>
					<?php }?>
					<?php if(isset($_SESSION['auch']) AND $_SESSION['auch'] == 1 AND $_SESSION['userlevel'] AND $_SESSION['userlevel'] == 1) {?>
				<li><a href="">Администрирование</a>
					<ul>
						<li><a href="showusers.php">Пользователи</a></li>
						<li><a href="geo_update.php">Редактирование географических объектов</a></li>
						<li><a href="screen_update.php">Настройка обновления экрана</a></li>
					</ul>
				</li>
					<?php }?>
				<li><a href="about.php">О компании</a></li>
			</ul>
		</nav>
	</body>
</html>