<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND ($_SESSION['userlevel']<=3))
{
require_once 'blocks/header.php'; 
require '/func/arrays.php';
$currnetdatetime = date("Y-m-d H:i:s");
$user_id = $_SESSION['user_id'];
//$get_data = $_GET['id_ticket'];
//$get_data = trim(filter_input(INPUT_GET, 'id_ticket', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$id_engineers_array = "";
$selected = "";
$data = $_POST;
$err=FALSE;

$ticket_number_edit = trim(filter_input(INPUT_POST, 'ticket_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$year_edit = trim(filter_input(INPUT_POST, 'year', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$month_edit = trim(filter_input(INPUT_POST, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
//$ticket_date = trim(filter_input(INPUT_POST, 'ticket_date'));
$ticket_task_edit = trim(filter_input(INPUT_POST, 'ticket_task', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ticket_solution_edit = trim(filter_input(INPUT_POST, 'ticket_solution', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ticket_material_edit = trim(filter_input(INPUT_POST, 'ticket_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ticket_status_edit = trim(filter_input(INPUT_POST, 'ticket_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ticket_sla_edit = trim(filter_input(INPUT_POST, 'ticket_sla', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$work_type_edit = trim(filter_input(INPUT_POST, 'work_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
//$cost_incident = trim(filter_input(INPUT_POST, 'cost_incident'));
$hours_edit = trim(filter_input(INPUT_POST, 'hours', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$cost_smeta_edit = trim(filter_input(INPUT_POST, 'cost_smeta', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$cost_material_edit = trim(filter_input(INPUT_POST, 'cost_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$cost_transport_edit = trim(filter_input(INPUT_POST, 'cost_transport', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$comment_edit = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
//$last_edit_datetime = trim(filter_input(INPUT_POST, 'last_edit_datetime'));
$last_edit_user_id_edit = trim(filter_input(INPUT_POST, 'last_edit_user_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$implementer_edit = trim(filter_input(INPUT_POST, 'implementer', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$id_contractor_edit = trim(filter_input(INPUT_POST, 'id_contractor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_cost_work_edit = trim(filter_input(INPUT_POST, 'contr_cost_work', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_cost_smeta_edit = trim(filter_input(INPUT_POST, 'contr_cost_smeta', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_cost_transport_edit = trim(filter_input(INPUT_POST, 'contr_cost_transport', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_material_edit = trim(filter_input(INPUT_POST, 'contr_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_cost_material_edit = trim(filter_input(INPUT_POST, 'contr_cost_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_account_number_edit = trim(filter_input(INPUT_POST, 'contr_account_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_date_payment_edit = trim(filter_input(INPUT_POST, 'contr_date_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
//echo $contr_date_payment_edit;
$contr_payment_status_edit = trim(filter_input(INPUT_POST, 'contr_payment_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_comment_edit = trim(filter_input(INPUT_POST, 'contr_comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_edit = trim(filter_input(INPUT_POST, 'supplier', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_cost_work_edit = trim(filter_input(INPUT_POST, 'supplier_cost_work', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_contr_material_edit = trim(filter_input(INPUT_POST, 'supplier_contr_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_cost_material_edit = trim(filter_input(INPUT_POST, 'supplier_cost_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_account_number_edit = trim(filter_input(INPUT_POST, 'supplier_account_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_date_payment_edit = trim(filter_input(INPUT_POST, 'supplier_date_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_payment_status_edit = trim(filter_input(INPUT_POST, 'supplier_payment_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_comment_edit = trim(filter_input(INPUT_POST, 'supplier_comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

if( isset($data['edit_ticket']))
	{
		$errors=array();//массив сообшений ошибок
		
/* ------------------------------------------------------------------------------------------------- */		
		if(empty($ticket_number_edit))
		{
			$errors[] = 'Укажите номер заявки!';
		}
				if(mb_strlen($ticket_number_edit)>20 or mb_strlen($ticket_number_edit)<3)
		{
			$errors[] = 'Номер заявки должен содержать не менее 3 и не более 20 символов!';
		}

/* ------------------------------------------------------------------------------------------------- */	
		if(empty($ticket_task_edit))
		{
			$errors[] = 'Укажите задачу по заявке!';
		}
			if(mb_strlen($ticket_task_edit)>200 or mb_strlen($ticket_task_edit)<3)
		{
			$errors[] = 'Текст задачи должен содержать не менее 3 и не более 200 символов!';
		}	


		if($work_type_edit == 0) //если выбран режим "Абонентская плата"
		{
			$cost_smeta_edit = 0;
			$hours_edit = 0;
		}
		elseif($work_type_edit == 1) //если выбран вид работы "Инцидентное обслуживание"
		{
			$cost_smeta_edit = 0;
		}
		elseif ($work_type_edit == 2) //если выбран вид работы "Почасовое обслуживание"
		{
			$cost_smeta_edit = 0;
		}
		
		else  //если выбран вид работы "Дополнительные работы"
		{ 
			$hours_edit = 0;
		}
		
		
		if($implementer_edit == 1){ //Если в качестве исполнителя выбрано МегаТрейд, по подрядчику всё обнуляется
			$id_contractor_edit = 0;
			$contr_cost_work_edit = 0;
			$contr_cost_smeta_edit = 0;
			$contr_cost_transport_edit = 0;
			$contr_material_edit = "";
			$contr_cost_material_edit = 0;
			$contr_account_number_edit = "";
			$contr_date_payment_edit = "";
			$contr_payment_status_edit = 0;
			$contr_comment_edit = "";
		}
		if(isset($_POST['id_engineers']) AND !empty($_POST['id_engineers']) AND $implementer_edit == 1){
			$id_engineers_array = serialize($_POST['id_engineers']);
		}
		else {$id_engineers_array = "";}
		if($ticket_status_edit==1 AND $implementer_edit == 0 AND $id_contractor_edit == 0)
		{
			$errors[] = 'Укажите исполнителя (подрядчика) работ!';
		}

		
		//echo $contr_date_payment_edit;
		
		if(empty($errors))
		{
			$editticket = Update_Ticket ($link, $get_data, $ticket_number_edit, $year_edit, $month_edit, $ticket_task_edit, $ticket_solution_edit, $ticket_material_edit, $ticket_status_edit, $ticket_sla_edit, $work_type_edit, $hours_edit, $cost_smeta_edit, $cost_material_edit, $cost_transport_edit, $comment_edit, $currnetdatetime, $user_id , $implementer_edit, $id_engineers_array, $id_contractor_edit, $contr_cost_work_edit, $contr_cost_smeta_edit, $contr_cost_transport_edit, $contr_material_edit, $contr_cost_material_edit, $contr_account_number_edit, $contr_date_payment_edit, $contr_payment_status_edit, $contr_comment_edit, $supplier_edit, $supplier_cost_work_edit, $supplier_contr_material_edit, $supplier_cost_material_edit, $supplier_account_number_edit, $supplier_date_payment_edit, $supplier_payment_status_edit, $supplier_comment_edit);
			
			if($editticket){
			?>
				<script>
					<!-- setTimeout(function() {window.location.href = '/showtickets.php#<?=$get_data?>';}, 0); -->
				</script>
			<?php	}
		}
		else
			{
				$err=TRUE;
			}
	}
	if(isset($_POST['delete_ticket'])){
		
		$deleteticket = Delete_Ticket($link, $get_data);
		if($deleteticket)
		{
			?>
				<script>
					setTimeout(function() {window.location.href = '/showtickets.php';}, 0);
				</script>
			<?php		
		}
	}
	if(isset($_GET['id_ticket']))
{
	//$get_data = $_GET['id_ticket'];
	$get_data = trim(filter_input(INPUT_GET, 'id_ticket', FILTER_SANITIZE_NUMBER_INT));
	$tickets = Edit_Ticket($link, $get_data);
	$objects = Edit_Object ($link, $tickets['id_object']);
	$city = Get_Geo ($link, $objects['city_id'], "city", "city_id");
	$projects = Edit_Project ($link, $objects['id_project']);
	$customers = Edit_Customer ($link, $objects['id_customer']);
	$contractor_select = Edit_Contr ($link, $tickets['id_contractor']);
	
	$id_object = $objects['id_object'];
	$id_project = $projects['id_project'];
	$object_full = "Объект: ".$objects['shop_number'].". Адрес: ".$objects['address'];
	$city_name = $objects['city_name'];
	$project_name = $projects['projectname'];
	$customer_name = $customers['customer_name'];
	$convertticketdate = strtotime($tickets['ticket_date']);
	$ticketdate = date( 'd-m-Y H:i:s', $convertticketdate );
	$convertlast_edit_datetime = strtotime($tickets['last_edit_datetime']);
	$last_edit_datetime = date( 'd-m-Y H:i:s', $convertlast_edit_datetime );
	$users = Edit_User($link, $tickets['last_edit_user_id']);
	$user_edit_ticket = $users['surname']." ".$users['name'];
	$work_type = $tickets['work_type'];
	$ticket_sla = $tickets['ticket_sla'];
	$hours = $tickets['hours'];
	$cost_smeta = $tickets['cost_smeta'];
	$cost_material = $tickets['cost_material'];
	$cost_transport = $tickets['cost_transport'];
	$contractors = Show_Contr_for_select ($link);
	$summ_contr = $tickets['contr_cost_work'] + $tickets['contr_cost_smeta'] + $tickets['contr_cost_transport'] + $tickets['contr_cost_material'];
	$summ_supplier = $tickets['supplier_cost_work'] + $tickets['supplier_cost_material'];
	if($work_type == 0) //если выбран режим "Абонентская плата"
		{
			$cost_incident = 0;
			$cost_hours = 0;
			$cost_smeta = 0;
		}
	elseif($work_type == 1) //если выбран вид работы "Инцидентное обслуживание"
	{

		switch ($ticket_sla)
		{
			case 0:
				$cost_incident = $projects['cost_incident_critical'];
				break;
			case 1:
				$cost_incident = $projects['cost_incident_high'];
				break;
			case 2:
				$cost_incident = $projects['cost_incident_medium'];
				break;
			case 3:
				$cost_incident = $projects['cost_incident_low'];
				break;
		}
		$cost_hours = $hours*$projects['cost_hour'];
		$cost_smeta = 0;
		
	}
	elseif ($work_type == 2) //если выбран вид работы "Почасовое обслуживание"
	{
		$projects = Edit_Project($link, $id_project);
		$cost_incident = 0;
		$cost_smeta = 0;
		$cost_hours = $hours * $projects['cost_hour'];
	}
		
	else  //если выбран вид работы "Дополнительные работы"
	{ 
		$cost_incident = 0;
		$cost_hours = 0;
	}
	$summcostwork = $cost_incident + $cost_hours + $cost_smeta + $cost_material + $cost_transport;

	
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Редактирование заявки</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/ticket_sel_option.js'></script>
	<script type="text/javascript" src='js/contractor_select.js'></script>
</head>
<body>
	<div class="showany">
	<p class="breadcrumbs"> <a href="showtickets.php">Заявки</a> / Редактирование заявки №<?=$tickets['ticket_number'];?>:</p>
		<?php if($err==TRUE){?>
		<h1><div class="error-message"><?=array_shift($errors)?></div></h1>
	<?php }?>
	<div class="reg_up_table">
	<p><b>Доходная часть</p>
		<table> <!-- Доходная часть  -->
						<!-- <tr>
							<a href="select_object.php"><button autofocus class="button-new">Выбрать объект</button></a>
						</tr> -->
			
						<form action="editticket.php?id_ticket=<?=$get_data;?>" method="POST">
						<input name="id_object" type="hidden" value=<?=$id_object?>> 
						<!--<input name="last_edit_datetime" type="hidden" value=<?=$currnetdatetime?>> 
						<input name="last_edit_user_id" type="hidden" value=<?=$user_id?>>  -->

						<tr>
							<td class="reg_dohod_td"><label for="customer_name">Заказчик:</label></td>
							<td>
								<div class="reg_text"><?= $customer_name;?></div>
								<input id="customer_name" class="StyleSelectBox" name="customer_name" readonly type="hidden" value="<?= @$customer_name;?>"/>
							</td>
						</tr>

						<tr>
							<td class="reg_dohod_td"><label for="project">Проект:</label></td>
							<td>
								<div class="reg_text"><?= @$project_name;?></div>
								<input id="project" class="StyleSelectBox" name="id_project" readonly type="hidden" value="<?= @$id_project;?>"/>
								<input id="project" class="StyleSelectBox" name="project_name" readonly type="hidden" value="<?= @$project_name;?>"/>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="city">Населенный пункт:</label></td>
							<td>
								<div class="reg_text"><?= @$city_name;?></div>
								
								<input id="project" class="StyleSelectBox" name="city_name" readonly type="hidden" value="<?= @$city_name;?>"/>
							</td>
						</tr>						
						<tr>
							<td class="reg_dohod_td"><label for="object">Объект:</label></td>
							<td>
								<div class="reg_text"><?= $object_full;?></div>
								<input id="object" class="StyleSelectBox" name="object" readonly type="hidden" value="<?= @$id_object;?>"/>
								<input id="object" class="StyleSelectBox" name="object_full" readonly type="hidden" value="<?= @$object_full;?>"/>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticketdate">Дата заведения заявки:</label></td>
							<td>
								<div class="reg_text"><?= $ticketdate;?></div>
						</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticketdate">Дата последнего редактирования:</label></td>
							<td>
								<div class="reg_text"><?= $last_edit_datetime;?></div>
						</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticketdate">Кто редактировал:</label></td>
							<td>
								<div class="reg_text"><?= $user_edit_ticket;?></div>
						</td>
						</tr>
						<tr>
						<td class="reg_dohod_td"><label for="ticket_number">Номер заявки:</label></td>
							<td>
								<input id="ticket_number" class="StyleSelectBox" name="ticket_number"  maxlength="20" type="text" value="<?= @$tickets['ticket_number'];?>"/>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="year">Отчетный год:</label></td>
							<td>
								<select class="reg_select" name="year" id="year" >
									<?php for($i = 2015; $i < 2071; $i++) { ?>
										<option  value="<?=$i;?>" <?= ($i == $tickets['year']) ? 'selected' : ''?>><?=$i;?></option>
									<?php } ?>

								</select>
							</td>
						</tr>							
						<tr>
							<td class="reg_dohod_td"><label for="month">Отчетный месяц:</label></td>
							<td>
								<select class="reg_select" name="month" id="month" >
									<?php for($i = 1; $i < 13; $i++) { ?>
										<option  value="<?= $i ?>" <?= ($i == $tickets['month']) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
									<?php } ?>

								</select>
							</td>
						</tr>						
					<!--	<tr>
							<td class="reg_dohod_td"><label for="ticket_date">Дата заведения заявки:</label></td>
							<td class="reg_text">
								<?= $currnetdatetime; ?>
								<input id="ticket_date" class="StyleSelectBox" name="ticket_date" type="date" value="<?= date('Y-m-d'); ?>"/>
							</td>
						</tr>  -->
						<tr>
							<td class="reg_dohod_td"><label for="ticket_task">Задача по заявке:</label></td>
							<td>
								<textarea class="reg_textarea" id="ticket_task" name="ticket_task" maxlength="150"><?= @$tickets['ticket_task'];?></textarea>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticket_solution">Решение по заявке:</label></td>
							<td>
								<textarea class="reg_textarea" id="ticket_solution" name="ticket_solution" maxlength="150"><?= @$tickets['ticket_solution'];?></textarea>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticket_material">Материалы и оборудование <br>(и их стоимость):</label></td>
							<td>
								<textarea class="reg_textarea" id="ticket_material" name="ticket_material" maxlength="200"><?= @$tickets['ticket_material'];?></textarea>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticket_status">Статус заявки:</label></td>
							<td>
								<select class="reg_select" name="ticket_status" id="ticket_status">
									<?php for($i = 0; $i < 3; $i++) { ?>
									<option  value="<?= $i ?>" <?= ($i == $tickets['ticket_status']) ? 'selected' : ''?>><?= $ticket_status_array[$i] ?></option>
									<?php }?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticket_sla">SLA:</label></td>
							<td>
								<select class="reg_select" name="ticket_sla" id="ticket_sla">
									<?php for($i = 0; $i < 4; $i++) { ?>
										<option  value="<?= $i ?>" <?= ($i == $tickets['ticket_sla']) ? 'selected' : ''?>><?= $ticket_sla_array[$i] ?></option>
									<?php }?>								

								</select>
							</td>
						</tr>						
						<tr>
							<td class="reg_dohod_td"><label for="work_type">Вид работы:</label></td>
							<td>
								<select class="reg_select" onchange="SelectOPT()" name="work_type" id="work_type">

									<?php for($i = 0; $i < 4; $i++) { ?>
										<option  value="<?= $i ?>" <?= ($i == $tickets['work_type']) ? 'selected' : ''?>><?= $work_type_array[$i] ?></option>
									<?php }?>		
								</select>
							</td>
						</tr>
						<!--<div>
							<tr id="work_type_select">
								<td class="reg_dohod_td"><label for="cost_incident">Стоимость инцидента:</label></td>
								<td><input id="cost_incident" class="StyleSelectBox" name="cost_incident" type="text" value="<?= @$cost_incident;?>"/></td>
							</tr>
						</div> -->
						<div>
							<tr id="work_type_select">
								<td class="reg_dohod_td"><label for="hours">Часы (количество):</label></td>
								<td><input id="hours" class="StyleSelectBox" name="hours" type="number" value="<?= @$tickets['hours'];?>"/></td>
							</tr>
						</div>
						<div>							
							<tr id="work_type_select">
								<td class="reg_dohod_td"><label for="cost_smeta">Сметная стоимость, руб.:</label></td>
								<td><input id="cost_smeta" class="StyleSelectBox" name="cost_smeta" type="number" value="<?= @$tickets['cost_smeta'];?>"/></td>
							</tr>
						</div>
						<div>							
							<tr>
								<td class="reg_dohod_td"><label for="cost_material">Стоимость материалов, руб.:</label></td>
								<td><input id="cost_material" class="StyleSelectBox" name="cost_material" type="number" value="<?= @$tickets['cost_material'];?>"/></td>
							</tr>							
						</div>
						<div>
							<tr>
								<td class="reg_dohod_td"><label for="cost_transport">Транспортные расходы, руб:</label></td>
								<td><input id="cost_transport" class="StyleSelectBox" name="cost_transport" type="number" value="<?= @$tickets['cost_transport'];?>"/></td>
							</tr>
						</div>
						<tr>
							<td class="reg_dohod_td"><label for="comment">Примечание:</label></td>
							<td><textarea class="reg_textarea" id="comment" name="comment" title="Поле должно содержать не более 100 символов!" ><?= @$tickets['comment'];?></textarea></td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="implementer">Исполнитель:</label></td>
							<td>
								<select class="reg_select" onchange="Select_ispolnitel()" name="implementer" id="implementer">
									<?php for($i = 0; $i < 2; $i++) { ?>
										<option  value="<?= $i; ?>" <?= ($i == $tickets['implementer']) ? 'selected' : ''?>><?= $implementer_array[$i];?></option>
									<?php }?>
								</select>
							</td>
						</tr>
						<tr id="contr_select"> <!-- ВЫБОР ИНЖЕНЕРА-->
							<td class="reg_dohod_td">
								<label for="work_type">Инженер:</label></td>
							<td>
						<?php
							$Users_Levels = Show_Users_Level($link, '4');
						
						?> 
						<?php if($Users_Levels) { 
							$Users_count = count($Users_Levels);?>
							<select class="reg_select" name="id_engineers[]" id="id_engineers"  multiple size="<?=$Users_count?>">
								<?php 
								if(!empty($tickets['id_engineers'])){
									$id_engineers_array = unserialize($tickets['id_engineers']);
								}
								foreach($Users_Levels as $i => $Users_Level)
								{ 
								$selected = "";
									if(in_array($Users_Level['id_users'], $id_engineers_array))
									
									{
										$selected = 'selected';
										
									}
									
									//$selected = (array_search($Users_Level['id_users'], $id_engineers_array) ? 'selected' : '');
									
									?>
									<option  value="<?=$Users_Level['id_users'];?>" <?=$selected;?> ><?= $Users_Level['surname'].' '.$Users_Level['name'];?></option>
								
									
							<?php } ?>
							</select>
						<?php } else { ?>
								<span class="rowt">У вас не добавлено ни одного инженера!</span>
							<?php }?>
							</td>
						</tr>						
					<tr>
					<td align="right">
						<div>
							<!-- <button name="edit_ticket" class="button">Сохранить</button> -->
							<input class="button" value="Сохранить" name="edit_ticket" type="submit" />
							<a href="showtickets.php#<?=$get_data?>" class="button"> Назад</a>
							<!-- <input class="button" value="Назад" type="button" onclick="location.href='showtickets.php#'.<?=$get_data?>.'" /> -->
						</div>
					</td>
					<?php if($_SESSION['userlevel']<3){ ?>
					<td align = "center">
						
					<a href="#delete_ticket" class="button-delete">Удалить заявку</a>
					<div id="delete_ticket" class="modalDialog">
						<div>
							<!-- <a href="#close"  title="Закрыть" class="close">X</a> -->
						<h2>Удаление заявки</h2>
						<p>Вы уверены, что хотите удалить заявку?</p>
						<input class="button-delete" value="Да" name="delete_ticket" type="submit"/>
						<a href="#close"  title="Отменить" class="button">Нет</a>
						<!-- <button class="button-delete" onclick='return confirm("Вы уверены, что хотите удалить эту заявку?")' name="delete_ticket">Удалить заявку</button> -->
						</div>
					</div>
					</td>
					<?php }?>
					</tr>
					<tr>
						<td class="reg_dohod_td_all">
							Инцидентная оплата:<br>
							Почасовая оплата:<br>
							Смета:<br>
							Материалы:<br>
							Транспорт:<br>
						</td>
						<td><div class="reg_text_all">
							<?=$cost_incident;?> руб.<br>
							<?=$cost_hours;?> руб.<br>
							<?=$cost_smeta;?> руб.<br>
							<?=$cost_material;?> руб.<br>
							<?=$cost_transport;?> руб.<br>
						</div></td>
					</tr>
					<tr>
						<td class="reg_dohod_td_itogo">

							ИТОГО:
						</td>
						<td><div class="reg_text_itogo">
							<?=$summcostwork;?> руб.
						</div></td>
					</tr>					
				</table>
<!-- "(Инцидент=".$cost_incident.") + (Почасовая оплата=".$cost_hours.") + (Смета=".$cost_smeta.") + (материалы=".$cost_material.") + (транспорт=".$cost_transport.") = ".$summcostwork." руб." -->
	</div>
<!--******************************* Подрядчик  *****************************************-->
	<div class="reg_up_table">
		<p><b>Расходная часть</p>
		<!-- <button class="button-new" formaction = "select_contractor.php">Выбрать подрядчика</button> -->
		<table id="reg_sel_exe">
			<tr id="contr_select">
				<td colspan="2" align="center">	Расходы на подрядчика: </td>
			</tr>
			<tr id="contr_select">
				<td  class="reg_contr_td">Город:</td>
				<td>
					<select name="city_id_contr" id="city_id_contr" class="StyleSelectBox">
						<option value="0">- выберите город -</option>
						<?php foreach($contractors as $i => $contractor)  { 
							$citys= Get_Geo ($link, $contractor['city_id'], "city", "city_id" );?>
							<option  value="<?= $contractor['city_id'];?>" <?= ($contractor['city_id'] == $contractor_select['city_id']) ? 'selected' : ''?>><?= $citys['name']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr id="contr_select">
				<td  class="reg_contr_td">Подрядчик:</td>
				<td>
					<select name="id_contractor" id="id_contractor" class="StyleSelectBox">
						<option value="0">- выберите контрагента -</option>
						<option value="<?=$tickets['id_contractor'];?>" selected><?=$contractor_select['org_name'];?></option>
					</select>
				</td>
			</tr>
			<tr id="contr_select">
				<td colspan="2" align="center">
					<span class="reg_link" id="link_contractor"></span>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_cost_work">Стоимость работ, руб:</label></td>
				<td>
					<input id="contr_cost_work" class="StyleSelectBox" name="contr_cost_work"type="number" value="<?= @$tickets['contr_cost_work'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_cost_smeta">Сметная стоимость, руб:</label></td>
				<td>
					<input id="contr_cost_smeta" class="StyleSelectBox" name="contr_cost_smeta"type="number" value="<?= @$tickets['contr_cost_smeta'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_cost_transport">Транспортные расходы, руб.:</label></td>
				<td>
					<input id="contr_cost_transport" class="StyleSelectBox" name="contr_cost_transport"type="number" value="<?= @$tickets['contr_cost_transport'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_material">Материалы и оборудование <br>(и их стоимость):</label></td>
				<td>
					<textarea class="reg_textarea" id="contr_material" name="contr_material" maxlength="100"><?= @$tickets['contr_material'];?></textarea>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_cost_material">Стоимость материалов, руб.:</label></td>
				<td>
					<input id="contr_cost_material" class="StyleSelectBox" name="contr_cost_material"type="number" value="<?= @$tickets['contr_cost_material'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_account_number">Номер счета:</label></td>
				<td>
					<input id="contr_account_number" class="StyleSelectBox" name="contr_account_number"  type="number" value="<?= @$tickets['contr_account_number'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_date_payment">Дата платежа:</label></td>
				<td>
					<input id="contr_date_payment" class="StyleSelectBox" name="contr_date_payment" type="date" value="<?= @$tickets['contr_date_payment']; ?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_payment_status">Статус платежа:</label></td>
				<td>
					<select class="reg_select" name="contr_payment_status" id="contr_payment_status">
					<?php for($i = 0; $i < 2; $i++) { ?>
						<option  value="<?= $i ?>" <?= ($i == $tickets['contr_payment_status']) ? 'selected' : ''?>><?= $paymentstatus_array[$i] ?></option>
					<?php }?>

					</select>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_comment">Примечание:</label></td>
				<td><textarea class="reg_textarea" id="contr_comment" name="contr_comment" title="Поле должно содержать не более 100 символов!" ><?= @$tickets['contr_comment'];?></textarea></td>
			</tr>
			<tr id="contr_select">
				<td class="reg_dohod_td_itogo">
					ИТОГО:
				</td>
				<td><div class="reg_text_itogo">
					<?=$summ_contr;?> руб.
				</div></td>
			</tr>
<!-- --------------------------ПОСТАВЩИК------------------------------------------------------------------>
			<tr>
				<td colspan="2" align="center"> Расходы на поставщика:</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier">Название организации:</label></td>
				<td>
					<input id="supplier" class="StyleSelectBox" name="supplier" maxlength="60" type="text" title="Название организации должно содержать не менее 3 и не более 60 символов!" value="<?= @$tickets['supplier'];?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_cost_work">Стоимость услуг, руб.:</label></td>
				<td>
					<input id="supplier_cost_work" class="StyleSelectBox" name="supplier_cost_work" type="number" value="<?= @$tickets['supplier_cost_work'];?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_contr_material">Материалы и оборудование <br>(и их стоимость):</label></td>
				<td>
					<textarea class="reg_textarea" id="supplier_contr_material" name="supplier_contr_material" maxlength="100"><?= @$tickets['supplier_contr_material'];?></textarea>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_cost_material">Стоимость материалов:</label></td>
				<td>
					<input id="supplier_cost_material" class="StyleSelectBox" name="supplier_cost_material"type="number" value="<?= @$tickets['supplier_cost_material'];?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_account_number">Номер счета:</label></td>
				<td>
					<input id="supplier_account_number" class="StyleSelectBox" name="supplier_account_number"  type="number" value="<?= @$tickets['supplier_account_number'];?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_date_payment">Дата платежа:</label></td>
				<td>
					<input id="supplier_date_payment" class="StyleSelectBox" name="supplier_date_payment" type="date" value="<?= @$tickets['supplier_date_payment']; ?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_payment_status">Статус платежа:</label></td>
				<td>
					<select class="reg_select" name="supplier_payment_status" id="supplier_payment_status">
					<?php for($i = 0; $i < 2; $i++) { ?>
						<option  value="<?= $i ?>" <?= ($i == $tickets['supplier_payment_status']) ? 'selected' : ''?>><?= $paymentstatus_array[$i] ?></option>
					<?php }?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_comment">Примечание:</label></td>
				<td><textarea class="reg_textarea" id="supplier_comment" name="supplier_comment" title="Поле должно содержать не более 100 символов!" ><?= @$tickets['supplier_comment']; ?></textarea></td>
			</tr>
			</form>
			<tr>
				<td class="reg_dohod_td_itogo">
					ИТОГО:
				</td>
				<td><div class="reg_text_itogo">
					<?=$summ_supplier;?> руб.
				</div>
				</td>
			</tr>
		</table>
	</div>
	</div>
</body>

<?php
	}
	else
	{
		header('Location: /');
}
?>