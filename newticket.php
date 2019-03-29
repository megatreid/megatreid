<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3)
{
require_once '/blocks/header.php';
require '/func/arrays.php';
$currentdate = date('Y-m-d');
$currnetdatetime = date("Y-m-d H:i:s");
$user_id = $_SESSION['user_id'];
$id_customer="";
$id_project="";
$id_object="";
$customer_name="";
$project_name="";
$city_name="";
$object_full="";
$id_engineers_array = "";
$summcostwork = 0;
$cost_incident = 0;
$cost_hours = 0;
if(isset($_POST['select_object'])){
	$select_object_post = $_POST['select_object'];
	$id_customer = trim(filter_input(INPUT_POST, 'id_customer'));
	$customers = Edit_Customer($link, $id_customer);
	$customer_name = $customers['customer_name'];
	$id_project = trim(filter_input(INPUT_POST, 'id_project'));
	$projects = Edit_Project($link, $id_project);
	$project_name = $projects['projectname'];
	$id_object = trim(filter_input(INPUT_POST, 'id_object'));
	$objects = Edit_Object($link, $id_object);
	$city_name = $objects['city_name'];
	$object_full = $objects['shop_number'].". Адрес: ".$objects['address'];
}

$contractors = Show_Contr_for_select ($link);
$data = $_POST;
$err=FALSE;
$ticket_number = trim(filter_input(INPUT_POST, 'ticket_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ticket_exist = Ticket_Exist($link, $ticket_number);
$year = trim(filter_input(INPUT_POST, 'year', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$month = trim(filter_input(INPUT_POST, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
if(empty($id_object))
{
	$id_object = trim(filter_input(INPUT_POST, 'id_object', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$customer_name = trim(filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$id_project = trim(filter_input(INPUT_POST, 'id_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$project_name = trim(filter_input(INPUT_POST, 'project_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$city_name = trim(filter_input(INPUT_POST, 'city_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$object_full = trim(filter_input(INPUT_POST, 'object_full', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	
}
//$ticket_date = trim(filter_input(INPUT_POST, 'ticket_date'));
$ticket_task = trim(filter_input(INPUT_POST, 'ticket_task', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ticket_solution = trim(filter_input(INPUT_POST, 'ticket_solution', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ticket_material = trim(filter_input(INPUT_POST, 'ticket_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ticket_status = trim(filter_input(INPUT_POST, 'ticket_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ticket_sla = trim(filter_input(INPUT_POST, 'ticket_sla', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$work_type = trim(filter_input(INPUT_POST, 'work_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
//$cost_incident = trim(filter_input(INPUT_POST, 'cost_incident'));
$hours = trim(filter_input(INPUT_POST, 'hours', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$cost_smeta = trim(filter_input(INPUT_POST, 'cost_smeta', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$cost_material = trim(filter_input(INPUT_POST, 'cost_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$cost_transport = trim(filter_input(INPUT_POST, 'cost_transport', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$comment = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
//$last_edit_datetime = trim(filter_input(INPUT_POST, 'last_edit_datetime'));
$last_edit_user_id = trim(filter_input(INPUT_POST, 'last_edit_user_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$implementer = trim(filter_input(INPUT_POST, 'implementer', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
//$id_engineers = trim(filter_input(INPUT_POST, 'id_engineers'));
/*************************************************************************/
$id_contractor = trim(filter_input(INPUT_POST, 'id_contractor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_cost_work = trim(filter_input(INPUT_POST, 'contr_cost_work', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_cost_smeta = trim(filter_input(INPUT_POST, 'contr_cost_smeta', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_cost_transport = trim(filter_input(INPUT_POST, 'contr_cost_transport', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_material = trim(filter_input(INPUT_POST, 'contr_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_cost_material = trim(filter_input(INPUT_POST, 'contr_cost_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_account_number = trim(filter_input(INPUT_POST, 'contr_account_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_date_payment = trim(filter_input(INPUT_POST, 'contr_date_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_payment_status = trim(filter_input(INPUT_POST, 'contr_payment_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contr_comment = trim(filter_input(INPUT_POST, 'contr_comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
/***************************************************************************/
$supplier = trim(filter_input(INPUT_POST, 'supplier', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_cost_work = trim(filter_input(INPUT_POST, 'supplier_cost_work', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_contr_material = trim(filter_input(INPUT_POST, 'supplier_contr_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_cost_material = trim(filter_input(INPUT_POST, 'supplier_cost_material', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_account_number = trim(filter_input(INPUT_POST, 'supplier_account_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_date_payment = trim(filter_input(INPUT_POST, 'supplier_date_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_payment_status = trim(filter_input(INPUT_POST, 'supplier_payment_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$supplier_comment = trim(filter_input(INPUT_POST, 'supplier_comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
if( isset($data['new_ticket']))
	{
		$errors=array();//массив сообшений ошибок
		
		if(empty($id_object))
		{
			$errors[] = 'Не выбран объект по заявке!';
		}
/* ------------------------------------------------------------------------------------------------- */		
		if(empty($ticket_number))
		{
			$errors[] = 'Укажите номер заявки!';
		}
		if(mb_strlen($ticket_number,'UTF-8')>20 or mb_strlen($ticket_number,'UTF-8')<3)
		{
			$errors[] = 'Номер заявки должен содержать не менее 3 и не более 20 символов!';
		}
		if(!empty($ticket_exist))
		{
			$errors[] = 'Заявка с таким номером уже существует!';
		}
		
		
/* ------------------------------------------------------------------------------------------------- */	
		if(empty($ticket_task))
		{
			$errors[] = 'Укажите задачу по заявке!';
		}
			if(mb_strlen($ticket_task,'UTF-8')>200 or mb_strlen($ticket_task,'UTF-8')<3)
		{
			$errors[] = 'Текст задачи должен содержать не менее 3 и не более 200 символов!';
		}	

		if(isset($work_type) && $work_type=="")
		{
			$errors[] = 'Выберите тип работы!';
		}
		if($work_type == 0) //если выбран режим "Абонентская плата"
		{
			$cost_smeta = 0;
			$hours = 0;
		}
		elseif($work_type == 1) //если выбран вид работы "Инцидентное обслуживание"
		{
			$cost_smeta = 0;
		}
		elseif ($work_type == 2) //если выбран вид работы "Почасовое обслуживание"
		{

			$cost_smeta = 0;

		}
		
		else  //если выбран вид работы "Дополнительные работы"
		{ 
			$hours = 0;
		}
		
		
		if($implementer == 1){
			$id_contractor = 0;
			$contr_cost_work = 0;
			$contr_cost_smeta = 0;
			$contr_cost_transport = 0;
			$contr_material = "";
			$contr_cost_material = 0;
			$contr_account_number = "";
			$contr_date_payment = "";
			$contr_payment_status = 0;
			$contr_comment = "";
		}
		if(isset($_POST['id_engineers']) AND !empty($_POST['id_engineers']) AND $implementer == 1){
			$id_engineers_array = serialize($_POST['id_engineers']);
		}
		else {$id_engineers_array = "";}
		if($ticket_status==1 AND $implementer == 0 AND $id_contractor == 0)
		{
			$errors[] = 'Выберите исполнителя работ!';
		}

		if(empty($errors))
		{
			
			$newticket = Add_Ticket ($link, $ticket_number, $year, $month, $id_object, $currnetdatetime, $ticket_task, $ticket_solution, $ticket_material, $ticket_status, $ticket_sla, $work_type,  $hours,  $cost_smeta, $cost_material, $cost_transport, $comment, $currnetdatetime, $user_id, $implementer, $id_engineers_array, $id_contractor, $contr_cost_work, $contr_cost_smeta, $contr_cost_transport, $contr_material, $contr_cost_material, $contr_account_number, $contr_date_payment, $contr_payment_status, $contr_comment, $supplier, $supplier_cost_work, $supplier_contr_material, $supplier_cost_material, $supplier_account_number, $supplier_date_payment, $supplier_payment_status, $supplier_comment);
			
			if($newticket){
			?>
				<script>
					setTimeout(function() {window.location.href = '/showtickets.php';}, 0);
				</script>
			<?php	}
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
	<title>Новая заявка</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/reg_sel_option.js'></script>
	<script type="text/javascript" src='js/contractor_select.js'></script>
</head>
<body>

	<div class="showany">
	
	<p class="breadcrumbs"> <a href="showtickets.php">Заявки</a> / Новая заявка:</p>
		<?php if($err==TRUE){?>
		<h1><div class="error-message"><?=array_shift($errors)?></div></h1>
	<?php }?>
	<div class="reg_up_table">
	<p><b>Доходная часть</p>


		<table> <!-- Доходная часть  -->
						<tr>
							<a href="select_object.php"><button autofocus class="button-new">Выбрать объект</button></a>
						</tr>
						<tr>
						<form action="" method="POST">
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
						<td class="reg_dohod_td"><label for="ticket_number">Номер заявки:</label></td>
							<td>
								<input id="ticket_number" class="StyleSelectBox" name="ticket_number"  maxlength="20" type="text" value="<?= @$data['ticket_number'];?>"/>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="year">Отчетный год:</label></td>
							<td>
								<select class="reg_select" name="year" id="year" >
									<?php for($i = 2015; $i < 2071; $i++) { ?>
										<option  value="<?=$i;?>" <?= ($i == date('Y')) ? 'selected' : ''?>><?=$i;?></option>
									<?php } ?>

								</select>
							</td>
						</tr>	
						<tr>
							<td class="reg_dohod_td"><label for="month">Отчетный месяц:</label></td>
							<td>
								<select class="reg_select" name="month" id="month" >
									<?php for($i = 1; $i < 13; $i++) { ?>
										<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
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
								<textarea class="reg_textarea" id="ticket_task" name="ticket_task" maxlength="150"><?= @$data['ticket_task'];?></textarea>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticket_solution">Решение по заявке:</label></td>
							<td>
								<textarea class="reg_textarea" id="ticket_solution" name="ticket_solution" maxlength="150"><?= @$data['ticket_solution'];?></textarea>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticket_material">Материалы и оборудование <br>(и их стоимость):</label></td>
							<td>
								<textarea class="reg_textarea" id="ticket_material" name="ticket_material" maxlength="200"><?= @$data['ticket_material'];?></textarea>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticket_status">Статус заявки:</label></td>
							<td>
								<select class="reg_select" name="ticket_status" id="ticket_status">
									<option selected value="0">В работе</option>
									<option value="1">Закрыта</option>
									<option value="2">На согласовании</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="ticket_sla">SLA:</label></td>
							<td>
								<select class="reg_select" name="ticket_sla" id="ticket_sla">
									<option value="0">Критический</option>
									<option value="1">Высокий</option>
									<option selected value="2">Средний</option>
									<option value="3">Низкий</option>
								</select>
							</td>
						</tr>						
						<tr>
							<td class="reg_dohod_td"><label for="work_type">Вид работы:</label></td>
							<td>
								<select class="reg_select" onchange="SelectOPT()" name="work_type" id="work_type">
									<option disabled selected>Выберите значение:</option>
									<option value="0">Абонентское обслуживание</option>
									<option value="1">Инцидентное обслуживание</option>
									<option value="2">Почасовое обслуживание</option>
									<option value="3">Дополнительные работы</option>
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
								<td><input id="hours" class="StyleSelectBox" name="hours" type="number" value="<?= @$data['hours'];?>"/></td>
							</tr>
						</div>
						<div>							
							<tr id="work_type_select">
								<td class="reg_dohod_td"><label for="cost_smeta">Сметная стоимость, руб.:</label></td>
								<td><input id="cost_smeta" class="StyleSelectBox" name="cost_smeta" type="number" step="any" value="<?= @$data['cost_smeta'];?>"/></td>
							</tr>
						</div>
						<div>							
							<tr>
								<td class="reg_dohod_td"><label for="cost_material">Стоимость материалов, руб.:</label></td>
								<td><input id="cost_material" class="StyleSelectBox" name="cost_material" type="number" step="any" value="<?= @$data['cost_material'];?>"/></td>
							</tr>							
						</div>
						<div>
							<tr>
								<td class="reg_dohod_td"><label for="cost_transport">Транспортные расходы, руб:</label></td>
								<td><input id="cost_transport" class="StyleSelectBox" name="cost_transport" type="number" step="any" value="<?= @$data['cost_transport'];?>"/></td>
							</tr>
						</div>
						<tr>
							<td class="reg_dohod_td"><label for="comment">Примечание:</label></td>
							<td><textarea class="reg_textarea" id="comment" name="comment" title="Поле должно содержать не более 100 символов!" ><?= @$data['comment'];?></textarea></td>
						</tr>
						<tr>
							<td class="reg_dohod_td"><label for="implementer">Исполнитель:</label></td>
							<td>
								<select class="reg_select" onchange="Select_ispolnitel()" name="implementer" id="implementer">
									<option selected value="0">Подрядная организация</option>
									<option value="1">ООО "Мега Трейд"</option>
								</select>
							</td>
						</tr>
						<tr id="contr_select">
							<td class="reg_dohod_td"><label for="work_type">Инженер:</label></td>
							<td>
						<?php
							$Users_Levels = Show_Users_Level($link, '4');
						
						?> 
						<?php if($Users_Levels) { 
							$Users_count = count($Users_Levels);?>
							<select class="reg_select" name="id_engineers[]" id="id_engineers"  multiple size="<?=$Users_count?>">
								<?php foreach($Users_Levels as $i => $Users_Level)  { 
								?>
									<option  value="<?= $Users_Level['id_users']; ?>"><?= $Users_Level['surname'].' '.$Users_Level['name'];?></option>
								<?php  } ?>
							</select>
							<?php } else { ?>
								<span class="rowt">У вас не добавлено ни одного инженера!</span>
							<?php }?>
							</td>
						</tr>
					<tr>
					<td colspan="2" align="center">
						<div>

							<!-- <button name="edit_ticket" class="button">Сохранить</button> -->
							<input class="button" value="Сохранить" name="new_ticket" type="submit" />
							<input class="button" value="Назад" type="button" onclick="location.href='showtickets.php'" />
							
						</div>

					</td>
						
					</tr>
					<tr>

					</tr>
				</table>

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
						<option value="0">- Выберите город -</option>
						<?php foreach($contractors as $i => $contractor)  { 
						$citys= Get_Geo ($link, $contractor['city_id'], "city", "city_id" );
						
						?>
							<option  value="<?= $contractor['city_id']; ?>"><?= $citys['name']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr id="contr_select">
				<td  class="reg_contr_td">Подрядчик:</td>
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
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_cost_work">Стоимость работ, руб:</label></td>
				<td>
					<input id="contr_cost_work" class="StyleSelectBox" name="contr_cost_work"type="number" step="any" value="<?= @$data['contr_cost_work'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_cost_smeta">Сметная стоимость, руб:</label></td>
				<td>
					<input id="contr_cost_smeta" class="StyleSelectBox" name="contr_cost_smeta"type="number" step="any" value="<?= @$data['contr_cost_smeta'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_cost_transport">Транспортные расходы, руб.:</label></td>
				<td>
					<input id="contr_cost_transport" class="StyleSelectBox" name="contr_cost_transport"type="number" step="any" value="<?= @$data['contr_cost_transport'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_material">Материалы и оборудование <br>(и их стоимость):</label></td>
				<td>
					<textarea class="reg_textarea" id="contr_material" name="contr_material" maxlength="100"><?= @$data['contr_material'];?></textarea>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_cost_material">Стоимость материалов, руб.:</label></td>
				<td>
					<input id="contr_cost_material" class="StyleSelectBox" name="contr_cost_material"type="number" step="any" value="<?= @$data['contr_cost_material'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_account_number">Номер счета:</label></td>
				<td>
					<input id="contr_account_number" class="StyleSelectBox" name="contr_account_number"  type="text" value="<?= @$data['contr_account_number'];?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_date_payment">Дата платежа:</label></td>
				<td>
					<input id="contr_date_payment" class="StyleSelectBox" name="contr_date_payment" type="date" value="<?= @$data['contr_date_payment']; ?>"/>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_payment_status">Статус платежа:</label></td>
				<td>
					<select class="reg_select" name="contr_payment_status" id="contr_payment_status">
						<option selected value="0">Неоплачено</option>
						<option value="1">Оплачено</option>
					</select>
				</td>
			</tr>
			<tr id="contr_select">
				<td class="reg_contr_td"><label for="contr_comment">Примечание:</label></td>
				<td><textarea class="reg_textarea" id="contr_comment" name="contr_comment" title="Поле должно содержать не более 100 символов!" ></textarea></td>
			</tr>
		
<!-- --------------------------ПОСТАВЩИК------------------------------------------------------------------>
			<tr>
				<td colspan="2" align="center"> Расходы на поставщика:</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier">Название организации:</label></td>
				<td>
					<input id="supplier" class="StyleSelectBox" name="supplier" maxlength="60" type="text" title="Название организации должно содержать не менее 3 и не более 60 символов!" value="<?= @$data['supplier'];?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_cost_work">Стоимость услуг, руб.:</label></td>
				<td>
					<input id="supplier_cost_work" class="StyleSelectBox" name="supplier_cost_work" type="number" step="any" value="<?= @$data['supplier_cost_work'];?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_contr_material">Материалы и оборудование <br>(и их стоимость):</label></td>
				<td>
					<textarea class="reg_textarea" id="supplier_contr_material" name="supplier_contr_material" maxlength="100"><?= @$data['supplier_contr_material'];?></textarea>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_cost_material">Стоимость материалов:</label></td>
				<td>
					<input id="supplier_cost_material" class="StyleSelectBox" name="supplier_cost_material"type="number" step="any" value="<?= @$data['supplier_cost_material'];?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_account_number">Номер счета:</label></td>
				<td>
					<input id="supplier_account_number" class="StyleSelectBox" name="supplier_account_number"  type="text" value="<?= @$data['supplier_account_number'];?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_date_payment">Дата платежа:</label></td>
				<td>
					<input id="supplier_date_payment" class="StyleSelectBox" name="supplier_date_payment" type="date" value="<?= @$data['supplier_date_payment']; ?>"/>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_payment_status">Статус платежа:</label></td>
				<td>
					<select class="reg_select" name="supplier_payment_status" id="supplier_payment_status">
						<option selected value="0">Неоплачено</option>
						<option value="1">Оплачено</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="reg_post_td"><label for="supplier_comment">Примечание:</label></td>
				<td><textarea class="reg_textarea" id="supplier_comment" name="supplier_comment" title="Поле должно содержать не более 100 символов!" ></textarea></td>
			</tr>
			</form>	
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