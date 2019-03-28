<?php
require '/connection/config.php';
$data = $_POST;

if( isset($data['delay']))
	{	
		$delay = trim(filter_input(INPUT_POST, 'delay'));
		if(isset($data['megatreid']) AND $data['megatreid'] == 1){
			$implementer = 1;
			$_SESSION['implementer'] = $implementer;
		}
		else $implementer = "";
		$_SESSION['delay'] = $delay;
	}
if(isset($_SESSION['implementer'])){
	$implementer = $_SESSION['implementer'];
}
else {
	$implementer = "";
}

if(isset($_SESSION['delay']) AND $_SESSION['delay']>=10){
header('Refresh: '.$_SESSION['delay'].'; url=' .$_SERVER['PHP_SELF']);	
}
else {
	$_SESSION['delay'] = 0;
}
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=4)
{
require_once '/blocks/header.php';
require '/func/arrays.php';
	//$ticket_status=0;
	//$status = 0;
	if(!isset($_SESSION['ticket_status'])){
		$_SESSION['ticket_status'] = 0;
		$status = 0;
	}
	if(!isset($_SESSION['pay_status_select'])){
		$pay_status = 2;

		
	}
	if(!isset($pay_status) AND !isset($_SESSION['pay_status'])){
		
		$pay_status = 2;
		
	}
	if(!isset($_SESSION['pay_status'])){
		$pay_status = 2;
	}
	if(!isset($pay_status))
	{
		$pay_status = $_SESSION['pay_status'];
	}
	$status = ($_SESSION['ticket_status']==="") ? 3 : $_SESSION['ticket_status'];
	//$ticket_count = 0; //количество заявок
	$current_month = "";
if( isset($data['pay_status']))
	{		
		$pay_status = trim(filter_input(INPUT_POST, 'pay_status'));
		$_SESSION['pay_status'] = $pay_status;
		if(!isset($pay_status))
		{
			$pay_status = $_SESSION['pay_status'];
			}
	}

if( isset($data['ticket_status']))
	{	
		$status = trim(filter_input(INPUT_POST, 'ticket_status'));
		
		switch($status)
		{
			case '0':	
				$ticket_status = "0";
			break;	
			case '1':	
				$ticket_status = "1";
			break;	
			case '2':	
				$ticket_status = "2";
			break;	
			case '3':	
				$ticket_status = "";
			break;	
		}
		$_SESSION['ticket_status'] = $ticket_status;
	}

	switch($pay_status)
	{
		case '0':	
			$pay_status_select = "AND (id_contractor !='0' AND contr_payment_status = '0' )";
		break;	
		case '1':	
			$pay_status_select = "AND (id_contractor !='0' AND contr_payment_status = '1' )";
		break;	
		case '2':	
			$pay_status_select = "";
		break;
		default:		
			$pay_status_select = "";	
	}
	$_SESSION['pay_status_select'] = $pay_status_select;

	$method_payment_select = "";
	$method_payment_table = 2;
if( isset($data['method_payment']))
	{		
		$method_payment_table = trim(filter_input(INPUT_POST, 'method_payment'));
		$_SESSION['method_payment'] = $method_payment_table;
		if(!isset($method_payment_table))
		{
			$method_payment_table = $_SESSION['method_payment'];
		}
	}	

switch($method_payment_table)
	{
		case '0':	
			$method_payment_select = "AND id_contractor IN(SELECT id_contractor FROM contractor WHERE method_payment=1)";
		break;	
		case '1':	
			$method_payment_select = "AND id_contractor IN(SELECT id_contractor FROM contractor WHERE method_payment=2)";
		break;	
		case '2':	
			$method_payment_select = "";
		break;
		default:		
			$method_payment_select = "";	
	}
	$_SESSION['method_payment_select'] = $method_payment_select;
	
	

	
	
	
	
	
	
	
	
	
	
	
	$tickets = Show_Tickets($link, $_SESSION['ticket_status'], $_SESSION['pay_status_select'], $_SESSION['method_payment_select'] , $current_month, $implementer);
	if($tickets){
	$count_tickets = count($tickets);
	}else {$count_tickets = 0;}
	$executor = "";

	$delay = isset($_POST["delay"])? $_POST["delay"]:30;


	//$customers = Edit_Customer($link, $data);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<!-- <meta http-equiv="Refresh" content="<?=$delay?>" /> -->
	<title>Заявки</title>
	<script type="text/javascript" src='js/jquery.js'></script>

</head>
<body>
	<div class="showcustomer">
			<div class="breadcrumbs">Заявки (количество <?=$count_tickets?>):</div>
				<div class="newticket">
					<a href='newticket.php'><button class="button-new">Создать новую заявку</button></a>
				</div>
				<br>
					<table border="1" cellspacing="0" id="filter-table">
					<form action="" method="POST">
					<thead>
						<tr class="hdr">
							<th rowspan="2" width=1%>Номер<br>заявки</th>
							<th rowspan="2" width=7%>Дата<br>заведения</th>
							<th width=1% rowspan="2">Отчетный<br>год</th>
							<th width="1" rowspan="2">Отчетный<br>месяц</th>
							<th rowspan="2">Заказчик<br></th>
							<th width="100" rowspan="2">Проект</th>
							<th width="170" rowspan="2">Город</th>
							<th rowspan="2">Объект</th>
							<th align="center" width=6% rowspan="2">Статус<br>заявки</th>
							<th rowspan="2">Исполнитель</th>
							<th rowspan="2">Статус<br>платежа</th>
							<th rowspan="2">Форма<br>оплаты</th>
							<th rowspan="2">Инженер</th>
							<th width="1" colspan="2">Изменение заявки</th>
							<!-- <th width="1" rowspan="2"></th> -->
						</tr>
						<tr class="hdr">
							<th>Сотрудник</th>
							<th width=7%>Дата</th>
						</tr>
						<tr class='table-filters'>
							<td>
								<input class="reg_input_filter" type="text"/><!--Номер заявки-->
							</td>
							<td>
								<input class="reg_input_filter" type="text"/><!--Дата заведения-->
							</td>
							<td>
								<input class="reg_input_filter" type="text"/><!--Год-->
							</td>							
							<td>
								<input class="reg_input_filter" type="text"/><!--Месяц-->
							</td>
							<td>
								<input class="reg_input_filter" type="text"/><!--Заказчик-->
							</td>							
							<td>
								<input class="reg_input_filter" type="text"/><!--Проект-->
							</td>							
							<td>
								<input class="reg_input_filter" type="text"/><!--Город-->
							</td>							
							<td>
								<input class="reg_input_filter" type="text"/><!--Объект-->
							</td>							
							<td>
								<select class="reg_select_filter" name="ticket_status" onchange="this.form.submit()">
									<?php for($i = 0; $i < 4; $i++) { ?>
									<option  value="<?= $i ?>" <?= ($i == $status) ? 'selected' : ''?>><?= $ticket_status_array[$i] ?></option>
									<?php }?>
								</select>
							</td>							
							<td>
								<input class="reg_input_filter" type="text"/><!--подрядчик-->
							</td>
							<td>
								<select class="reg_select_filter" name="pay_status" onchange="this.form.submit()">
									<?php for($i = 0; $i < 3; $i++) { ?>
									<option  value="<?= $i ?>" <?= ($i == $pay_status) ? 'selected' : ''?>><?= $paymentstatus[$i] ?></option>
									<?php }?>
								</select>
							</td>								
							<td>
								<select class="reg_select_filter" name="method_payment" onchange="this.form.submit()">
									<?php for($i = 0; $i < 3; $i++) { ?>
									<option  value="<?= $i ?>" <?= ($i == $method_payment_table) ? 'selected' : ''?>><?= $methodpaymentstatus[$i] ?></option>
									<?php }?>
								</select>
							</td>								
							<td>
								<input class="reg_input_filter" type="text"/><!--Инженер-->
							</td>								
							<td>
								<input class="reg_input_filter" type="text"/><!--Сотрудник-->
							</td>							
							<td>
								<input class="reg_input_filter" type="text"/><!--Дата-->
							</td>							
							<!-- <td colspan="2">
							<!--	<button class="reg_select_filter" name="do_filter">Применить</button> -->
						<!--	</td> -->
						</tr>
							</form>
					</thead>
				<?php
				if($tickets){
				foreach($tickets as $i => $ticket) {
				
				$users = Edit_User($link, $ticket['last_edit_user_id']);
				$implementer = $ticket['implementer'];
				
				//if($ticket['id_contractor'] == 0){ $ticket['id_contractor']="";}
				$objects = Edit_Object ($link, $ticket['id_object']);
				$city = Get_Geo ($link, $objects['city_id'], "city", "city_id");
				$projects = Edit_Project ($link, $objects['id_project']);
				$customers = Edit_Customer ($link, $objects['id_customer']);
				$contractor = Edit_Contr ($link, $ticket['id_contractor']);
				if($implementer == 1)
				{
					$executor = 'Мега Трейд ООО';
					$method_payment = "";
					$payment_status = "";
				}
				else{
					$executor =$contractor['org_name']." ".$contractor['ownership'];
					if($contractor) {
						$method_payment = $methodpaymentedit[$contractor['method_payment']];
						$payment_status = $paymentstatus[$ticket['contr_payment_status']];
					}
					else{
						$method_payment = "";
						$payment_status = "";
					}
				} 
				$convertticketdate = strtotime($ticket['ticket_date']);
				$currentdate = strtotime(date('d-m-Y H:i:s'));
				$ticketdate = date( 'd-m-Y H:i:s', $convertticketdate );
				$diffdatecreate = ($currentdate - $convertticketdate)/(60*60*24); //Разница между текущей датой и датой заведения заявки
				$convertlast_edit_datetime = strtotime($ticket['last_edit_datetime']);
				$last_edit_datetime = date( 'd-m-Y H:i:s', $convertlast_edit_datetime );
				$diffdatechange = ($currentdate - $convertlast_edit_datetime)/(60*60*24); //Разница между текущей датой и датой изменения заявки
				?>
				<tbody>
				<?php 
				if($diffdatecreate > 2 && $diffdatechange < 1 && $ticket['ticket_status']!=1)
				{
					$class = "reg_text_show_tickets_red";
				}
				elseif ($diffdatechange > 1 && $ticket['ticket_status']!=1){
					$class = "reg_text_show_tickets_red_bold";
				}
				elseif ($diffdatecreate < 2 && $diffdatechange < 1 && $ticket['ticket_status']!=1){
					$class = "";
				}
				elseif ($ticket['ticket_status']==1){
					$class = "";
				}
				if(!empty($ticket['id_engineers'])){
					$id_engineers_array = unserialize($ticket['id_engineers']);
				}
				else $id_engineers_array = "";
				
				?>
					<tr class="reg_text_show_tickets" id=<?=$ticket['id_ticket'];?>>
						<td class = "<?= $class?>"><a href='/editticket.php?id_ticket=<?= $ticket['id_ticket']; ?>' title = 'Редактировать'><?=$ticket['ticket_number'];?></a></td>
						<td class = "<?= $class?>"><?=$ticketdate;?></td>
						<td class = "<?= $class?>"><?=$ticket['year'];?></td>
						<td class = "<?= $class?>"><?=$months[$ticket['month']-1];?></td>
						<td class = "<?= $class?>"><?=$customers['customer_name'];?></td>
						<td class = "<?= $class?>"><?=$projects['projectname'];?></td>
						<td class = "<?= $class?>"><?=$city['name'];?></td>
						<td class = "<?= $class?>"><?=$objects['shop_number']."<br>".$objects['address']?></td>
						<td class = "<?= $class?>"><?=$ticket_status_array[$ticket['ticket_status']];?></td>
						<td class = "<?= $class?>"><?=$executor; ?></td>
						<td class = "<?= $class?>"><?=$payment_status;?></td>
						<td class = "<?= $class?>"><?=$method_payment;?></td>

						<td class = "<?= $class?>">
						
						<?php
						if(!empty($id_engineers_array)){
							foreach($id_engineers_array as $i => $id_engineers)
							{
								$user_info = edit_user($link,$id_engineers);
								echo $user_info['surname']." ".$user_info['name']."<br>";
							}
						}
						?>
						
						</td>
						<td class = "<?= $class?>"><?=$users['surname']." ".$users['name'];?></td>
						<td class = "<?= $class?>"><?=$last_edit_datetime;?></td>
						
						<!-- <td  class = "<?= $class?>"width="1"><a href='/editticket.php?id_ticket=<?= $ticket['id_ticket']; ?>' title = 'Редактировать'>
						<img src='/images/edit.png' width='20' height='20'></td> -->
					</tr>
				</tbody>
<?php }} else { ?>
				<tbody>
					<tr>
						<td colspan="15" align="center" class="date">Не добавлено ни одной заявки</td>
 					</tr>
				</tbody>
<?php } ?>
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