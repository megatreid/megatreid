<?php require 'connection/config.php';

$err= FALSE;

if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3)
{
	require_once 'blocks/header.php';
	require 'func/arrays.php';
	
	if(isset($_GET['id_record']))
	{
		$id_record = trim(filter_input(INPUT_GET, 'id_record', FILTER_SANITIZE_NUMBER_INT));
		$object_info = Edit_Object_customabont($link, $id_record);
		//$id_contractor = $object_info['id_contractor'];
		$id_object = $object_info['id_object'];
		$abon_plata_customer = $object_info['summ'];
		$year = $object_info['year'];
		$month = $object_info['month'];
		$date_payment = $object_info['paydate'];
		$payment_status = $object_info['paystatus'];
		$pay_account = $object_info['pay_account'];
		
		$customers = Show_Customer_Active($link, '1');
		//$contractors = Show_Contr_for_select($link);
		$show_city_names = Show_City_Name($link);
		$object_info = Edit_Object($link, $id_object);
		$project_info = Edit_Project($link, $object_info['id_project']);
		$customer_info = Edit_Customer($link, $object_info['id_customer']);
		//$contr_info = Edit_Contr($link, $id_contractor);
		$city_info = get_geo($link, $object_info['city_id'], 'city', 'city_id');
		//$city_info_contr = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
		//$contractor_info = $contr_info['org_name']." ".$contr_info['ownership'];
		
		$data = $_POST;
		//$id_contractor_update = trim(filter_input(INPUT_POST, 'id_contractor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$id_object_update = trim(filter_input(INPUT_POST, 'id_object', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$abon_plata_customer_update = trim(filter_input(INPUT_POST, 'abon_plata_customer', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$year_update = trim(filter_input(INPUT_POST, 'year', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$month_update = trim(filter_input(INPUT_POST, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$date_payment_update = trim(filter_input(INPUT_POST, 'date_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$payment_status_update = trim(filter_input(INPUT_POST, 'payment_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$pay_account_update = trim(filter_input(INPUT_POST, 'pay_account', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		
		if(isset($data['save_to_object']))
		{
			$errors=array();//массив сообшений ошибок
			$object_exist = Object_customer_Exist($link, $id_object_update, $year_update, $month_update);
			if(empty($id_object_update) OR $id_object_update == 0)
			{
				$errors[] = 'Выберите объект!';
			}

			if(empty($year_update) OR $year_update == 0)
			{
				$errors[] = 'Укажите год!';
			}
			if(empty($month_update) OR $month_update == 0)
			{
				$errors[] = 'Укажите месяц!';
			}
			if(empty($abon_plata_customer_update) OR $abon_plata_customer_update == 0)
			{
				$errors[] = 'Укажите абонентскую плату!';
			}
			if(empty($date_payment_update) OR $date_payment_update == 0)
			{
				$date_payment_result = "`paydate`= NULL,";
			}
			else
			{
				$date_payment_result = "`paydate`= '".$date_payment_update."',";
			}
			if($object_exist AND $abon_plata_customer == $abon_plata_customer_update AND $date_payment == $date_payment_update AND $year == $year_update AND $month == $month_update AND 
			$date_payment == $date_payment_update AND $payment_status == $payment_status_update AND $pay_account == $pay_account_update)
			{
				$errors[] = 'Такой объект уже добавлен на '.$months[$month_update - 1].' '.$year_update.' года!';
			}
			
			if(empty($errors))
			{  
				$update = Update_Object_customabont($link, $id_record, $id_object_update, $abon_plata_customer_update, $year_update, $month_update, $date_payment_result, $payment_status_update, $pay_account_update);
				if($update) { ?>		
				<script>
					setTimeout(function() {window.location.href = 'object_customer_abon.php';}, 0);
				</script>	
			<?php		
			}}
			else
			{
				$err=TRUE;
			}
			
		}
		if(isset($data['delete_record']))
		{
			$delete_record = Delete_Object_customabont($link, $id_record);
			if($delete_record)
			{
				?>
				<script>
					setTimeout(function() {window.location.href = 'object_customer_abon.php';}, 0);
				</script>
				<?php		
			}
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
	<p class="breadcrumbs"><a href='/object_customer_abon.php'>Объекты с абонентской платой</a> > Редактирование объекта с абонентской платой:</p>
	<div class="reg_sel_object">
	<p class="breadcrumbs"> Выбор объекта:</p>
	<?php if($err==TRUE){?>
		<div class="error-message"><?=array_shift($errors)?></div>
	<?php }?>	
		<form action="edit_object_customer_abon.php?id_record=<?=$id_record;?>" method="POST">
					<table>
						<tr>
							<td class="rowt">Заказчик:</td>
							<td>
							<select name="id_customer" id="id_customer" class="StyleSelectBox">
								<option value="0">- выберите заказчика -</option>
								<?php foreach($customers as $i => $customer)  {?>
									<option  value="<?= $customer['id_customer']; ?>"<?= ($customer['id_customer'] == $object_info['id_customer']) ? 'selected' : ''?>><?= $customer['customer_name']; ?></option>
								<?php } ?>
							</select>
							</td>
						</tr>
						<tr>
							<td class="rowt">Проект:</td>
							<td>
								<select name="id_project" id="id_project"  class="StyleSelectBox">
									<option value="0">- выберите проект -</option>
									<option value="<?=$object_info['id_project'];?>" selected><?=$project_info['projectname'];?></option>
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
								<select name="city_id" id="city_id"  class="StyleSelectBox">
									<option value="0">- выберите город -</option>
									<option value="<?=$object_info['city_id'];?>" selected><?=$city_info['name'];?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="rowt">Объект:</td>
							<td>
								<select name="id_object" id="id_object"  class="StyleSelectBox">
									<option value="0">- выберите объект -</option>
									<option value="<?=$id_object;?>" selected><?=$object_info['shop_number'].'. '.$object_info['address'];?></option>
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
						<p class="breadcrumbs"> Выбор месяца:</p>	
					</td>
				</tr>
				<tr>
					<td class="rowt"><label for="year">Год:</label></td>
					<td>
						<select class="reg_select" name="year" id="year" >
							<?php for($i = 2015; $i < 2071; $i++) { ?>
								<option  value="<?=$i;?>" <?= ($i == $year) ? 'selected' : ''?>><?=$i;?></option>
							<?php } ?>

						</select>
					</td>
				</tr>	
				<tr>
					<td class="rowt"><label for="month">Месяц:</label></td>
					<td>
						<select class="reg_select" name="month" id="month" >
							<?php for($i = 1; $i < 13; $i++) { ?>
								<option  value="<?= $i ?>" <?= ($i == $month) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
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
					<td class="rowt"><label for="abon_plata_customer">Абонентская плата, руб.:</label></td>
					<td><input class="StyleSelectBox" id="abon_plata_customer" name="abon_plata_customer" type="number" step="any" min="0" value="<?=$abon_plata_customer;?>"/></td>
				</tr>
				<!-- НОМЕР СЧЕТА --> 
				<tr>
					<td class="rowt"><label for="pay_account">Номер счета:</label></td>
					<td>
						<input id="pay_account" class="StyleSelectBox" name="pay_account"  type="text" value="<?= $pay_account;?>"/>
					</td>
				</tr>
				<!-- ДАТА ПЛАТЕЖА -->
				<tr>
					<td class="rowt"><label for="date_payment">Дата платежа:</label></td>
					<td>
						<input id="date_payment" class="StyleSelectBox" name="date_payment" type="date" value="<?= @$date_payment; ?>"/>
					</td>
				</tr>
				<!-- СТАТУС ПЛАТЕЖА -->
				<tr>
					<td class="rowt"><label for="payment_status">Статус платежа:</label></td>
					<td>
						<select class="reg_select" name="payment_status" id="payment_status">
							<option value="0"<?=($payment_status == 0) ? 'selected':''?>>Неоплачено</option>
							<option value="1"<?=($payment_status == 1) ? 'selected':''?>>Оплачено</option>
						</select>
					</td>
				</tr>				
			</table>			
						
						<button class="button" name="save_to_object">Сохранить запись</button>
						<a href="#delete_record" class="button-delete">Удалить запись</a>
						<div id="delete_record" class="modalDialog">
							<div>
								<!-- <a href="#close"  title="Закрыть" class="close">X</a> -->
							<h2>Удаление записи </h2>
							<p>Вы уверены, что хотите удалить эту запись?</p>
							<p>Это может привести к потери данных в других разделах системы!</p>
							<input class="button-delete" value="Да" name="delete_record" type="submit"/>
							<a href="#close"  title="Отменить" class="button">Нет</a>
							</div>
						</div>	
					</form>
					</div>
			</div>
</body>
</html>	
	
<?php
}
else
{
	header('Location: /');
}
?>	