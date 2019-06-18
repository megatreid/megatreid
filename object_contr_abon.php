<?php
require 'connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3){
require_once 'blocks/header.php';
require '/func/arrays.php';
$yearnow = date('Y');
$monthnow = date('n');
$objects_abon = Show_Objects_Contr_abon($link, $yearnow, $monthnow);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Объекты с абонентской платой</title>
	<script type="text/javascript" src='js/jquery.js'></script>
</head>
<body>
	<div class="showcustomer">
		<p class="breadcrumbs">Объекты с абонентской платой у подрядчиков:</p>
		<?php if($_SESSION['userlevel']<=3){ ?>
			<div class="newticket">
				<a href='/newlinkcontrobject.php'><button class="button-new">Добавить новый объект</button></a>
			</div>
		<?php }?>
		<table border="1">
			<thead>
				<tr class="hdr">
					<th>Год</th>
					<th>Месяц</th>
					<th>Заказчик</th>
					<th>Проект</th>
					<th>Город</th>
					<th>Объект</th>	
					<th>Подрядчик</th>
					<th>Абонентская плата</th>
					<th>Действие</th>
					<th>Скопировать<br>на следующий месяц</th>
				</tr>
				<tr class='table-filters'>
					<td>
						<select class="reg_select_filter" name="year_select" id="year" onchange="this.form.submit()">
							<?php for($i = 2017; $i < 2041; $i++) { ?>
							<option  value="<?=$i;?>" <?= ($i == $yearnow) ? 'selected' : ''?>><?=$i;?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						<select class="reg_select_filter" name="method_payment" onchange="this.form.submit()">
							<?php for($i = 1; $i < 13; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == $monthnow) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
							<?php }?>
						</select>					
					</td>
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Заказчик-->
					</td>
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Проект-->
					</td>							
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Объект-->
					</td>
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Город-->
					</td>					
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Абонентская плата от заказчика-->
					</td>							
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Подрядчик-->
					</td>
					<td></td>
				</tr>
			</thead>



		</table>
	</div>
	<script type="text/javascript" src='js/filter_showticket.js'></script>
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