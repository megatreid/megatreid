<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<3)
{
require_once '/blocks/header.php';
if(isset($_GET['id_customer']))
{
	$data = $_GET['id_customer'];
	$projects = Show_Projects($link, $data);
	$customers = Edit_Customer($link, $data);
}?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Проекты (<?=$customers['customer_name'];?>)</title>
	<script type="text/javascript" src='js/jquery.js'></script>
</head>
<body>
	<div class="showcustomer">
			<p class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > Проекты (<?=$customers['customer_name'];?>):</p>
				<div class="newcustomer">
					<a href='/newproject.php?id_customer=<?=$data?>'><button class="button-new">Добавить новый проект</button></a>
				</div>
				<br>
					<table border="1" cellspacing="0">
					<thead>
						<tr class="hdr">
							<th width="1" rowspan="2">№</th>
							<th rowspan="2">Заказчик</th>
							<th rowspan="2">Наименование проекта</th>
							<th  width="1" rowspan="2">Почасовой<br>тариф</th>
							<th width="1" colspan="4">Стоимость инцидентов</th>
							<!-- <th>Транспортные<br>расходы</th> -->
							<th width="1" rowspan="2">Действие</th>
							<th width="1" rowspan="2">Объекты</th>
						</tr>
							<tr class="hdr">
							<th width="1">Критический</th>
							<th width="1">Высокий</th>
							<th width="1">Средний</th>
							<th width="1">Низкий</th>

						</tr>
						<tr class='table-filters'>
							<td>
							</td>
							<td>
							</td>
							<td>
								<input class="reg_input_filter" type="text" placeholder="..."/><!--Наименование проекта-->
							</td>							
							<td colspan="7">

							</td>
						</tr>
					</thead>
					
				<?php
				if($projects){
				foreach($projects as $i => $project) { ?>
				<tbody>
					<tr class="reg_text_show_tickets">
						<td align="center" width="1"><?=$i+1?></td>
						<td align="center"><?=$customers['customer_name'];?></td>
						<td align="center"><?=$project['projectname']?></td>
						<td  width="1" align="center"><?=$project['cost_hour']?></td>
						<td  width="1" align="center"><?=$project['cost_incident_critical']?></td>
						<td  width="1" align="center"><?=$project['cost_incident_high']?></td>
						<td  width="1" align="center"><?=$project['cost_incident_medium']?></td>
						<td  width="1" align="center"><?=$project['cost_incident_low']?></td>
						<td width="1" align="center"><a href='/editproject.php?edit_project=<?= $project['id_project'] ?>' title = 'Редактировать'>
						<img src='/images/edit.png' width='20' height='20'></td>
						<td width="1" align="center"><a href='showobjects.php?id_project=<?= $project['id_project'] ?>' title = 'Объекты'>
						<img src='/images/house1.png' width='20' height='20'></td>
					</tr>
				</tbody>
<?php }} else { ?>
				<tbody>
					<tr>
						<td colspan="10" align="center" class="date">У данного заказчика не добавлено ни одного проекта</td>
 					</tr>
				</tbody>
<?php } ?>
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