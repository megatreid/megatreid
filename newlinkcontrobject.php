<?php
require 'connection/config.php';
require_once 'blocks/header.php';
require 'func/arrays.php';
$customers = Show_Customer_Active($link, '1');
$contractors = Show_Contr_for_select($link);
$show_city_names = Show_City_Name($link);
$data = $_POST;
$err=false;
$id_contractor = trim(filter_input(INPUT_POST, 'id_contractor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$id_object = trim(filter_input(INPUT_POST, 'id_object', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$abon_plata_contr = trim(filter_input(INPUT_POST, 'abon_plata_contr', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$year = trim(filter_input(INPUT_POST, 'year', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$month = trim(filter_input(INPUT_POST, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$pay_account = trim(filter_input(INPUT_POST, 'pay_account', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$date_payment = trim(filter_input(INPUT_POST, 'date_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$payment_status = trim(filter_input(INPUT_POST, 'payment_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

if(isset($data['save_to_object']))
	{
		$errors=array();//массив сообшений ошибок
		$object_exist = Object_Exist($link, $id_object, $year, $month);
		if(empty($id_object) OR $id_object == 0)
		{
			$errors[] = 'Выберите объект!';
		}
		if(empty($id_contractor) OR $id_contractor == 0)
		{
			$errors[] = 'Выберите подрядчика!';
		}	
		if(empty($year) OR $year == 0)
		{
			$errors[] = 'Укажите год!';
		}
		if(empty($month) OR $month == 0)
		{
			$errors[] = 'Укажите месяц!';
		}
		if(empty($abon_plata_contr) OR $abon_plata_contr == 0)
		{
			$errors[] = 'Укажите абонентскую плату!';
		}
		if(empty($date_payment) OR $date_payment == 0)
		{
			$date_payment_result = "`paydate`= NULL,";
		}
		else
		{
			$date_payment_result = "`paydate`= '".$date_payment."',";
		}
		if($object_exist)
		{
			$errors[] = 'Такой объект уже добавлен на '.$months[$month - 1].' '.$year.' года!';
		}
		
		if(empty($errors))
		{  
			$result = Add_Object_with_abon($link, $id_contractor, $id_object, $abon_plata_contr, $year, $month, $date_payment_result, $payment_status, $pay_account ); 
		?>		
			<script>
				setTimeout(function() {window.location.href = 'object_contr_abon.php';}, 0);
			</script>	
		<?php		
		}
		else
		{
			$err=TRUE;
		}
			
	}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Поиск объекта</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/object_select.js'></script>
	<script type="text/javascript" src='js/contractor_select.js'></script>
</head>
	<div class="showany">
	<p class="breadcrumbs"><a href='/object_contr_abon.php'>Объекты с абонентской платой</a> >  Добавление объекта с абонентской платой:</p>
	<div class="reg_sel_object">
	<p class="breadcrumbs"> Выбор объекта:</p>
	<?php if($err==TRUE){?>
		<div class="error-message"><?=array_shift($errors)?></div>
	<?php }?>	
		<form action="" method="POST">
					<table>
						<tr>
							<td class="rowt">Заказчик:</td>
							<td>
							<select name="id_customer" id="id_customer" class="StyleSelectBox">
								<option value="0">- выберите заказчика -</option>
								<?php foreach($customers as $i => $customer)  {?>
									<option  value="<?= $customer['id_customer']; ?>"><?= $customer['customer_name']; ?></option>
								<?php } ?>
							</select>
							</td>
						</tr>
						<tr>
							<td class="rowt">Проект:</td>
							<td>
								<select name="id_project" id="id_project" disabled="disabled" class="StyleSelectBox">
									<option value="0">- выберите проект -</option>
								</select>
								
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<span class="reg_link"  id=link_project></span>
							</td>
						</tr>
						<tr>
							<td class="rowt">Населенный пункт:</td>
							<td>
								<select name="city_id" id="city_id" disabled="disabled" class="StyleSelectBox">
									<option value="0">- выберите город -</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="rowt">Объект:</td>
							<td>
								<select name="id_object" id="id_object" disabled="disabled" class="StyleSelectBox">
									<option value="0">- выберите объект -</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<span class="reg_link"  id=link_object></span>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<p class="breadcrumbs"> Выбор подрядчика:</p>	
							</td>
						</tr>
			<tr id="contr_select">
				<td  class="rowt">Город:</td>
				<td>
				<!--
					<select name="city_id_contr" id="city_id_contr" class="StyleSelectBox">
						<option value="0">- Выберите город -</option>
						<?php foreach($contractors as $i => $contractor)  { 
						$citys= Get_Geo ($link, $contractor['city_id'], "city", "city_id" );
						
						?>
							<option  value="<?= $contractor['city_id']; ?>"><?= $citys['name']; ?></option>
						<?php } ?>
					</select>
				-->
					<select name="city_id_contr" id="city_id_contr" class="StyleSelectBox">
						<option value="0">- Выберите город -</option>
						<?php foreach($show_city_names as $i => $show_city_name)  { 
						//$citys= Get_Geo ($link, $contractor['city_id'], "city", "city_id" );
						
						?>
							<option  value="<?= $show_city_name['city_id']; ?>"><?= $show_city_name['name']; ?></option>
						<?php } ?>
					</select>
				</td>
				</tr>
				<tr id="contr_select">
					<td  class="rowt">Подрядчик:</td>
					<td>
						<select name="id_contractor" id="id_contractor" disabled="disabled" class="StyleSelectBox">
							<option value="0">- Выберите подрядчика -</option>
						</select>
					</td>
				</tr>
				<tr id="contr_select">
					<td colspan="2" align="center">
						<span class="reg_link" id=link_contractor></span>
					</td>
				</tr>

				<tr>
					<td colspan="2" align="center">
						<p class="breadcrumbs"> Выбор месяца:</p>	
					</td>
				</tr>
				<tr>
					<td class="rowt"><label for="year">Год:</label></td>
					<td>
						<select class="reg_select" name="year" id="year" >
							<?php for($i = 2015; $i < 2071; $i++) { ?>
								<option  value="<?=$i;?>" <?= ($i == date('Y')) ? 'selected' : ''?>><?=$i;?></option>
							<?php } ?>

						</select>
					</td>
				</tr>	
				<tr>
					<td class="rowt"><label for="month">Месяц:</label></td>
					<td>
						<select class="reg_select" name="month" id="month" >
							<?php for($i = 1; $i < 13; $i++) { ?>
								<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
							<?php } ?>

						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<p class="breadcrumbs"> Платеж:</p>	
					</td>
				</tr>
				<tr>
					<td class="rowt"><label for="abon_plata_contr">Абонентская плата, руб.:</label></td>
					<td><input class="StyleSelectBox" id="abon_plata_contr" name="abon_plata_contr" step="any" type="number" min="0" value="<?=$abon_plata_contr;?>"/></td>
				</tr>
				<!-- НОМЕР СЧЕТА --> 
				<tr>
					<td class="rowt"><label for="pay_account">Номер счета:</label></td>
					<td>
						<input id="pay_account" class="StyleSelectBox" name="pay_account"  type="text" value="<?= @$data['pay_account'];?>"/>
					</td>
				</tr>
				<!-- ДАТА ПЛАТЕЖА -->
				<tr>
					<td class="rowt"><label for="date_payment">Дата платежа:</label></td>
					<td>
						<input id="date_payment" class="StyleSelectBox" name="date_payment" type="date" value="<?= @$data['date_payment']; ?>"/>
					</td>
				</tr>
				<!-- СТАТУС ПЛАТЕЖА -->
				<tr>
					<td class="rowt"><label for="payment_status">Статус платежа:</label></td>
					<td>
						<select class="reg_select" name="payment_status" id="payment_status">
							<option selected value="0">Неоплачено</option>
							<option value="1">Оплачено</option>
						</select>
					</td>
				</tr>				
			</table>			
						<div>
							<p><button name="save_to_object">Сохранить</button></p>
						</div>
					</form>
					</div>
	<!-- <div id="footer">&copy; ООО "МегаТрейд"</div> -->
	</div>

</body>
</html>