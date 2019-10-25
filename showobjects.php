<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']))
{
	require_once '/blocks/header.php';
	require '/func/arrays.php';
	if(isset($_GET['id_project']))
	{
		$data = $_GET['id_project'];
		$projects = Edit_Project($link, $data);
		$id_customer = $projects['id_customer'];
		$customers = Edit_Customer($link, $id_customer);
		$objects = Show_Objects ($link, $data);
	}?>
	<!DOCTYPE html>
	<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>Объекты (<?=$projects['projectname'];?>)</title>
		<script type="text/javascript" src='js/jquery.js'></script>
	</head>
	<body>
		<div class="showcustomer">
			<div class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > <a href='showprojects.php?id_customer=<?= $customers['id_customer'] ?>'>Проекты (<?=$customers['customer_name'];?>)</a> > Объекты (<?=$projects['projectname'];?></a>):</div>
			<?php if($_SESSION['userlevel']<=3){ ?>	
				<div class="newticket">
					<a href='/newobject.php?id_project=<?=$data?>'><button class="button-new">Добавить новый объект</button></a>
				</div>
			<?php }?>	
				<table border="1" cellspacing="0">
					<thead>
						<tr class="hdr">
							<th width=1%>№</th>
							<th width=10%>Заказчик</th>
							<th width=10%>Проект</th>
							<th width=15%>Город</th>
							<th width=10%>Объект</th>
							<th>Адрес</th>
							<th width=1%>Статус</th>
							<th rowspan="2" width="1">Абонентская плата от заказчика</th>
							<th rowspan="2" width="1">Абонентская плата для подрядчика</th>
							<?php if($_SESSION['userlevel']<=3){ ?>	
							<th rowspan="2" width=1%>Действие</th>
							<?php }?>
						</tr>
						<tr class='table-filters'>
							<td>
							</td>
							<td>
							</td>
							<td>
							</td>							
							<td>
								<input class="reg_input_filter" type="text"/><!--Город-->
							</td>
							<td>
								<input class="reg_input_filter" type="text"/><!--Объект-->
							</td>							
							<td>
								<input class="reg_input_filter" type="text"/><!--Адрес-->
							</td>
							<td>
								<input class="reg_input_filter" type="text"/><!--Статус-->
							</td>							
							<td colspan="2">
							</td>
						</tr>
					</thead>
				<?php
				if($objects){
				foreach($objects as $i => $object) {
					
					$object_info = Object_customabont_info($link, $object['id_object']);
					if(!$object_info){
						$ab_summ = "-";
					}
					else
					{
						$ab_summ = $object_info['summ'];
					}
					$controbject_info = Info_contrab_by_object($link, $object['id_object']);
					if(!$controbject_info){
						$contrab_summ = "-";
					}
					else
					{
						$contrab_summ = $controbject_info['summ'];
					}
				?>
				<tbody>
					
					<tr title = 'Редактировать' class="reg_text_show_tickets" onclick="window.location.href='/editobject.php?edit_object=<?= $object['id_object'] ?>'; return false">
					
						<td align="center" width="1"><?=$i+1?></td>
						<td align="center" width="1"><?=$customers['customer_name'];?></td>
						<td align="center" width="1"><?=$projects['projectname'];?></td>
						<?php 
							$city = Get_Geo ($link, $object['city_id'], "city", "city_id" );
						?>
						<td align="center" width="1"><?=$city['name'];?></td>
						<td align="center" width="1"><?=$object['shop_number']?></td>
						<td align="center"><?=$object['address']?></td>
						<td align="center"><?=$statusedit[$object['status']];?></td>
						<td align="center"width="1"><?=$ab_summ;?></td>
						<td align="center"width="1"><?=$contrab_summ;?></td>
						<?php if($_SESSION['userlevel']<=3){ ?>
						<td align="center" width="1"><a href='/editobject.php?edit_object=<?= $object['id_object'] ?>' title = 'Редактировать'>
						<img src='/images/edit.png' width='20' height='20'></a></td>
						<?php }?>
						
					</tr>
					
				</tbody>
				<?php }} else { ?>
				<tbody>
					<tr>
						<td colspan="8" align="center" class="date">По данному проекту не добавлено ни одного объекта</td>
					</tr>
				</tbody>
			<?php
			}
			?>
			</table>
		</div>
		<script type="text/javascript" src='js/filter_showticket.js'></script>
<script type="text/javascript" src='js/sideup.js'></script>	

<a id="upbutton" href="#" onclick="smoothJumpUp(); return false;">
    <img src="/images/up.png" alt="Top" border="none" title="Наверх">
</a>
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