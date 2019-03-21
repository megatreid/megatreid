<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3){
require_once '/blocks/header.php';
$objects_abon = Show_Objects_abon($link);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Заказчики</title>
	<script type="text/javascript" src='js/jquery.js'></script>
</head>
<body>
	<div class="showcustomer">
		<p class="breadcrumbs">Объекты с абонентской платой:</p>
		<table border="1">
			<thead>
				<tr class="hdr">
					<th width=1% >Заказчик</th>
					<th>Проект</th>
					<th>Город</th>
					<th>Объект</th>	
					<th width=1% >Абонентская плата<br>от заказчика</th>
					<th>Подрядчик</th>							
					<th width=1% >Абонентская плата<br>для подрядчика</th>
				</tr>
				<tr class='table-filters'>
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
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Абонентская плата для подрядчика-->
					</td>
				</tr>
			</thead>	
<?php if($objects_abon){
		foreach($objects_abon as $i => $object_abon) {
			$customer_info = Edit_Customer($link, $object_abon['id_customer']);
			$project_info = Edit_Project($link, $object_abon['id_project']);
			$contr_info = Edit_Contr($link, $object_abon['id_contractor']);
			if($contr_info){
				$city_name = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
				$contractor_info = $contr_info['org_name']." ".$contr_info['status']." (".$city_name['name'].")";
			}
			else
			{
				$contractor_info = "Подрядчик<br>не выбран";
			}
		?>
			<tr class="text_show">
			
				<td align="center"><a href="editcustomer.php?edit=<?=$object_abon['id_customer'];?>"><?=$customer_info['customer_name'];?></a></td>
				<td align="center"><a href="editproject.php?edit_project=<?=$object_abon['id_project'];?>"><?=$project_info['projectname']?></a></td>
				<td align="center"><?=$object_abon['city_name'];?></td>
				<td align="center"><a href="editobject.php?edit_object=<?=$object_abon['id_object'];?>"><?=$object_abon['shop_number']."<br>".$object_abon['address'];?></a></td>
				<td align="center"><?=$object_abon['abon_plata'];?></td>
				<td align="center"><?=$contractor_info;?></td>
				<td align="center"><?=$object_abon['abon_plata_contr'];?></td>
				
	
			</tr>
<?php }} else { ?>
				<tr>
					<td colspan="8" align="center" class="date">Не добавлено ни одного заказчика</td>
 				</tr>
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