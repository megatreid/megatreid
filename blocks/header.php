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
		<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
		<link rel="stylesheet" href="/css/index.css">
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
			document.getElementById("digital_watch").innerHTML ="	&#8986; "+day+"-"+month+"-"+ year +"   "+ hours + ":" + minutes + ":" + seconds;
			setTimeout("digitalWatch()", 1000);
		  }
		</script>
	</head>
<BODY onload="digitalWatch()">
	
		<div class="header">
			<a name="top"></a>
			<div class="logo">
				<a href="/"><img src="images/megatreid.png"  alt="ООО МЕГАТРЕЙД" width="55" height="56"></a>
			</div>
		</div>
			<div class="systemname">
				Управление проектами ООО "Мега Трейд"
			</div>
			<div id="clock"></div>
		<div class="auth-menu">
			<?php if(isset($_SESSION['auch'])){
			if($_SESSION['auch'] == 1) { ?>
				<?= "Добрый день,<br>".$_SESSION["io"] ."!"?>
				<!-- <a href='lk.php'><button class="button-new">Личный кабинет</button></a> -->
				<div class='auth_menu_button'> <a href='logout.php'><button class="button-new">Выйти</button></a> </div>
			<?php } 
			if($_SESSION['auch'] == 2) {?>
			<div class = 'auth_error'>Неправильный логин или пароль! </div>
			<div  class="authmenu_reg">
				
				<form action="/auth.php" method="POST">
					<label>Логин:</label>
					<input type="text" name="login"/>
					<label>Пароль:</label>
					<input type="password" name="userpassword"/>
					<button type="submit" name="do_login"  class="button-new">Войти</button>
				</form>
				
				<?php } ?>
			</div>
			<?php } ?>
			<?php if(!isset($_SESSION['auch'])){ ?>
			<div>Пожалуйста, авторизуйтесь! </div>
			<span class="authmenu_reg">
				
					<form action="/auth.php" method="POST">
						<label>Логин:</label>
						<input type="text" name="login"/>
						<label>Пароль:</label>
						<input type="password" name="userpassword"/>
						<button type="submit" name="do_login" class="button-new">Войти</button>
					</form>
				
			</span>
			<?php } ?>
		</div>
		<nav class="menu" role="navigation">
		<span id="digital_watch" class="watch"></span>
			<ul>
				<li><a href="/">Главная</a>
					<ul>
						<li><a href="screen_update.php">Настройка обновления экрана</a></li>
					</ul>
				</li>
				<?php if(isset($_SESSION['auch']) AND $_SESSION['auch'] == 1 AND $_SESSION['userlevel'] AND $_SESSION['userlevel'] <=4) {?>
				<li><a href="showtickets.php">Заявки</a>
				<ul>
				<?php if(isset($_SESSION['auch']) AND $_SESSION['auch'] == 1 AND $_SESSION['userlevel'] AND $_SESSION['userlevel'] <=3) {?>
						<li><a href="newticket.php">Создать заявку</a></li>
				<?php }?>
				</ul>
				</li>
				<?php }?>
				<?php if(isset($_SESSION['auch']) AND $_SESSION['auch'] == 1 AND $_SESSION['userlevel'] AND $_SESSION['userlevel'] <= 3) {?>
				<li><a href="showcustomer.php">Заказчики</a>
					<ul>
						<li><a href="object_customer_abon.php">Объекты с абонентской<br>платой от заказчиков</a></li>
					</ul>				
				</li>
				<li><a href="showcontractor.php">Подрядчики</a>
					<ul>
						<li><a href="object_contr_abon.php">Объекты с абонентской<br>платой у подрядчиков</a></li>
					</ul>
				</li>
				<li><a href="">Отчеты</a>
					<ul>
						<li><a href="report_by_customer.php">Отчет по заказчикам</a></li> 
						<li><a href="report_by_contractor.php">Отчет по подрядчикам</a>
						<ul>
							<li><a href="report_partner_from_base.php">Партнерская сеть</a></li>
						</ul>
						</li>
						<li><a href="objects_with_abon.php">Объекты с абонентской платой</a></li> 
					</ul>
				</li>
				<li><a href="">Администрирование</a>
					<ul>
					<?php if(isset($_SESSION['userlevel']) AND  $_SESSION['userlevel']==1) { ?>
						<li><a href="showusers.php">Пользователи</a></li>
					<?php }?>
						<li><a href="geo_update.php">Редактирование географических объектов</a></li>
					<?php if(isset($_SESSION['userlevel']) AND  $_SESSION['userlevel']==1) { ?>	
						<li><a href="backup_db.php">Резервирование БД</a></li>
					<?php }?>	
					</ul>
				</li>
					<?php }?>
				<li><a href="about.php">О компании</a></li>
			</ul>
		</nav>
	</body>
</html>