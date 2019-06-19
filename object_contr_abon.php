<?php
require 'connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3){
require_once 'blocks/header.php';
require '/func/arrays.php';
$yearnow = date('Y');
$monthnow = date('n');

$err = FALSE;
$data_post = $_POST;
$id_record = trim(filter_input(INPUT_POST, 'recordcopy', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$year_select = trim(filter_input(INPUT_POST, 'year_select', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$month_select = trim(filter_input(INPUT_POST, 'month_select', FILTER_SANITIZE_FULL_SPECIAL_CHARS));


if(isset($data_post['recordcopy']))
{
	$errors=array();//массив сообшений ошибок
	$object_abon_contr = Edit_Object_with_abon($link, $id_record);
	$date_payment_result = "`paydate`= NULL,";
	$payment_status = "";
	$newmonth = $monthnow + 1;
	if($monthnow == 12){
		$newyear = $yearnow + 1;
		$newmonth = 1;
	}
	else {
		$newyear = $yearnow;
	}
	$object_exist = Object_Exist($link, $object_abon_contr['id_object'], $newyear, $newmonth);
	if(!$object_exist)
	{
		$err = false;
		$new_link = Add_Object_with_abon($link, $object_abon_contr['id_contractor'], $object_abon_contr['id_object'], $object_abon_contr['summ'], $newyear, $newmonth, $date_payment_result, $payment_status);
	}
	else 
	{
		$err = TRUE;
		$errors[] = 'Такой объект уже добавлен на '.$months[$newmonth - 1].'!';
	}
}
if(isset($data_post['year_select']))
{
	$yearselect = $year_select;
}
else 
{
	$yearselect = $yearnow;
}	
if(isset($data_post['month_select']))
{
	$monthselect = $month_select;
}
else 
{
	$monthselect = $monthnow;
}	

$objects_abons = Show_Objects_Contr_abon($link, $yearselect, $monthselect);

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
		<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
		<?php }?>		
		<?php if($_SESSION['userlevel']<=3){ ?>
			<div class="newticket">
				<a href='/newlinkcontrobject.php'><button class="button-new">Добавить новый объект</button></a>
			</div>
		<?php }?>
		<form action="" method="POST">
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
					<th rowspan="2">Абонентская<br>плата, руб.</th>
					<th colspan="2" rowspan="2">Действие</th>
					<!-- <th>Скопировать<br>на следующий месяц</th> -->
				</tr>
				<tr class='table-filters'>
					<td>
						<select class="reg_select_filter" name="year_select" id="year" onchange="this.form.submit()">
							<?php for($i = 2017; $i < 2041; $i++) { ?>
							<option  value="<?=$i;?>" <?= ($i == $yearselect) ? 'selected' : ''?>><?=$i;?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						<select class="reg_select_filter" name="month_select" onchange="this.form.submit()">
							<?php for($i = 1; $i < 13; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == $monthselect) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
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

				</tr>
			</thead>
			<tbody>
			
				<?php 
				if($objects_abons) {
				foreach($objects_abons as $i => $objects_abon) {
					$object_info = Edit_Object($link, $objects_abon['id_object']);
					$project_info = Edit_Project($link, $object_info['id_project']);
					$customer_info = Edit_Customer($link, $object_info['id_customer']);
					$contr_info = Edit_Contr($link, $objects_abon['id_contractor']);
					$city_info = get_geo($link, $object_info['city_id'], 'city', 'city_id');
					$city_info_contr = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
					$contractor_info = $contr_info['org_name']." ".$contr_info['ownership']." (".$city_info_contr['name'].")";					
				
				?>
					<tr class="reg_text_show_tickets">
						<td align="center"><?=$objects_abon['year'];?></td>
						<td align="center"><?=$months[$objects_abon['month'] - 1];?></td>
						<td align="center"><?=$customer_info['customer_name'];?></td>
						<td align="center"><?=$project_info['projectname'];?></td>
						<td align="center"><?=$city_info['name'];?></td>
						<td align="center"><?=$object_info['shop_number']."<br>".$object_info['address'];?></td>
						<td align="center"><?=$contractor_info;?></td>
						<td align="center"><?=$objects_abon['summ'];?></td>
						
						
						<td align="center"><a href='edit_object_contr_abon.php?id_record=<?= $objects_abon['id_record']; ?>' title = 'Изменить'>
						<img src='images/edit.png' width='20' height='20'></td>
						<td align="center"><input type="image" name = "recordcopy" value = "<?= $objects_abon['id_record']; ?>" src="images/copy.png" width='20' height='20' title = 'Копировать на следующий календарный месяц'></td>
					</tr>
				<?php }}?>
			</tbody>


		</table>
		</form>
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