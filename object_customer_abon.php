<?php
require 'connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3){
require_once 'blocks/header.php';
require 'func/arrays.php';
$yearnow = date('Y');
$monthnow = date('n');
$newmonth = $monthnow + 1;
	if($monthnow == 12){
		$newyear = $yearnow + 1;
		$newmonth = 1;
	}
	else {
		$newyear = $yearnow;
	}
$err = FALSE;
$data_post = $_POST;
//$id_record = trim(filter_input(INPUT_POST, 'recordcopy', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$year_select = trim(filter_input(INPUT_POST, 'year_select', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$month_select = trim(filter_input(INPUT_POST, 'month_select', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$id_record = trim(filter_input(INPUT_GET, 'id_record', FILTER_SANITIZE_NUMBER_INT));

if(isset($_GET['id_record']) AND $_GET['statuscopy'] == "copy")
{
	$errors=array();//массив сообшений ошибок
	$object_abon_customer = Edit_Object_customabont($link, $id_record);
	$date_payment_result = "`paydate`= NULL,";
	$payment_status = "";
	$pay_account = "";
	
	$object_exist = Object_customer_Exist($link, $object_abon_customer['id_object'], $newyear, $newmonth);
	if(!$object_exist)
	{
		$err = false;
		$new_link = Add_Object_customabont($link, $object_abon_customer['id_object'], $object_abon_customer['summ'], $newyear, $newmonth, $date_payment_result, $payment_status, $pay_account);
		?>		
			<script>
				setTimeout(function() {window.location.href = 'object_customer_abon.php';}, 0);
			</script>	
		<?php		
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

$objects_abons = Show_Objects_customabont($link, $yearselect, $monthselect);

if(isset($data_post['copy']))
{
	$newmonth = $monthnow + 1;
	if($monthnow == 12){
		$newyear = $yearnow + 1;
		$newmonth = 1;
	}
	else {
		$newyear = $yearnow;
	}	
	if($objects_abons) {
		$new_link = "";
		$s  = 0;
		foreach($objects_abons as $k => $objects_abon)
		{
			
			
			$object_abon_customer = Edit_Object_customabont($link, $objects_abon['id_record']);
			$date_payment_result = "`paydate`= NULL,";
			$payment_status = "";
			$pay_account = "";
			$object_exist = Object_customer_Exist($link, $object_abon_customer['id_object'], $newyear, $newmonth);
			
			if(!$object_exist)
			{
				$s ++;
				$new_link = Add_Object_customabont($link, $object_abon_customer['id_object'], $object_abon_customer['summ'], $newyear, $newmonth, $date_payment_result, $payment_status, $pay_account);
			}			
		}
		if($new_link)
		{
			?>

				<div id="copy_objects" class="modalDialog">
				<div>
				<a href="#close"  title="Закрыть" class="close">X</a>
				<h2><?="Записи (".$s." из ". ($k + 1) .") скопированы на ".$months[$newmonth-1]." ".$newyear." года.";?></h2>
				</div>
				</div>	
			
			
			<?php
		}
		else 
		{
			?>
				<div id="copy_objects" class="modalDialog">
				<div>
				<a href="#close"  title="Закрыть" class="close">X</a>
				<h2><?="Все записи с текущего месяца уже скопированы на ".$months[$newmonth-1]." ".$newyear." года.";?></h2>
				</div>
				</div>	
			<?php
		}
	}
}







?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Объекты с абонентской платой от заказчиков</title>
	<script type="text/javascript" src='js/jquery.js'></script>
</head>
<body>
	<div class="showcustomer">
		<p class="breadcrumbs">Объекты с абонентской платой от заказчиков:</p>
		<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
		<?php }?>		
		<?php if($_SESSION['userlevel']<=3){ ?>
			<div class="newticket">
				<a href='newcustabobject.php'><button class="button-new">Добавить новый объект</button></a>
			</div>
		<form action="object_customer_abon.php#copy_objects" method="POST">	
			<div class="newcustomer">
				<button class="button-new" name = "copy">Копировать все объекты</button>
			</div>
		</form>
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
					<th rowspan="2">Абонентская<br>плата, руб.</th>
					<th rowspan="2">Статус<br>оплаты</th>
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
						

				</tr>
			</thead>
			<tbody>
			
				<?php 
				if($objects_abons) {
				foreach($objects_abons as $i => $objects_abon) {
					$object_info = Edit_Object($link, $objects_abon['id_object']);
					$project_info = Edit_Project($link, $object_info['id_project']);
					$customer_info = Edit_Customer($link, $object_info['id_customer']);
					$city_info = get_geo($link, $object_info['city_id'], 'city', 'city_id');
					//$city_info_contr = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
			
				
				
				if($objects_abon['paystatus'] == 1)
				{
					$class = "show_green";
				}
				else $class = "";
				?>
					<tr class="reg_text_show_tickets">
						<td align="center" class="<?=$class;?>"><?=$objects_abon['year'];?></td>
						<td align="center" class="<?=$class;?>"><?=$months[$objects_abon['month'] - 1];?></td>
						<td align="center" class="<?=$class;?>"><?=$customer_info['customer_name'];?></td>
						<td align="center" class="<?=$class;?>"><?=$project_info['projectname'];?></td>
						<td align="center" class="<?=$class;?>"><?=$city_info['name'];?></td>
						<td align="center" class="<?=$class;?>"><?=$object_info['shop_number']."<br>".$object_info['address'];?></td>
						<td align="center" class="<?=$class;?>"><?=$objects_abon['summ'];?></td>
						<td align="center" class="<?=$class;?>"><?=$paymentstatus_array[$objects_abon['paystatus']];?></td>
						<td align="center" class="<?=$class;?>"><a href='edit_object_customer_abon.php?id_record=<?= $objects_abon['id_record']; ?>' title = 'Изменить'>
						<img src='images/edit.png' width='20' height='20'></td>
						<td align="center" class="<?=$class;?>">
						<!-- <input type="image" name = "recordcopy" value = "<?= $objects_abon['id_record']; ?>" src="images/copy.png" width='20' height='20' title = 'Копировать на <?=$months[$newmonth-1];?> <?=$newyear;?> года!'> -->
						<a href='object_customer_abon.php?id_record=<?= $objects_abon['id_record']; ?>&statuscopy=copy' title = 'Скопировать на следующий месяц'>
						<img src='images/copy.png' width='20' height='20'>

						</td>
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