<?php
require '/connection/config.php';
ob_start();
require_once '/blocks/header.php'; 
require '/func/arrays.php';

$date = date('d-m-Y');
$customers = Show_Customer ($link);
$profitsumm = 0;
$profit_contr_summ = 0;
$supplier_cost_summ2 = 0; 
$id_customer_selected = "0";
$zarplata = 0;
$zarnalog = 0;
$nds = 0;
$profit_tax = 0;
$usn_tax = 0;
$other_tax = 0;
$banks = 0;
$network = 0;
$rent = 0;
$own_expenses = 0;

$err = FALSE;
$errors=array();
if(isset($_POST['id_customer']))
{
	$id_customer_selected = trim(filter_input(INPUT_POST, 'id_customer'));
	if($id_customer_selected != "1select")
	{
		$customer_sel = Edit_Customer($link, $id_customer_selected);
	}
}

if(isset($_POST['customer_report']))
{
	$ticket_status = trim(filter_input(INPUT_POST, 'ticket_status'));
	if ($ticket_status == 3) $ticket_status = "";
	$year = trim(filter_input(INPUT_POST, 'year'));
	$month_start = trim(filter_input(INPUT_POST, 'month_start'));
	$payment_status = trim(filter_input(INPUT_POST, 'payment_status'));
	//echo $month_start;
	$month_start_name = $months[$month_start-1];
	$month_end = trim(filter_input(INPUT_POST, 'month_end'));
	
	$zarplata = trim(filter_input(INPUT_POST, 'zarplata'));
	$zarnalog = trim(filter_input(INPUT_POST, 'zarnalog'));
	$nds = trim(filter_input(INPUT_POST, 'nds'));
	$profit_tax = trim(filter_input(INPUT_POST, 'profit_tax'));
	$usn_tax = trim(filter_input(INPUT_POST, 'usn_tax'));
	$other_tax = trim(filter_input(INPUT_POST, 'other_tax'));
	$banks = trim(filter_input(INPUT_POST, 'banks'));
	$network = trim(filter_input(INPUT_POST, 'network'));
	$rent = trim(filter_input(INPUT_POST, 'rent'));
	$own_expenses = trim(filter_input(INPUT_POST, 'own_expenses'));
	
	
	
	
	//echo $month_end;
	$month_end_name = $months[$month_end-1];
	$month_period = ($month_end - $month_start) + 1;
	switch($payment_status)
	{
		case '0':	
			$custompaystatus = "customer_payment_status = '0' AND ";
		break;	
		case '1':	
			$custompaystatus = "customer_payment_status = '1' AND ";
		break;	
		case '2':	
			$custompaystatus = "";
		break;	

	}
	if($month_start > $month_end)
	{
		$errors[] = 'Неправильно выбран отчетный период!';
	}
	
	if($month_end > date('n') AND $year == date('Y'))
	{
		$errors[] = 'Нельзя делать отчеты за будущие месяцы!';
	}
	if($year > date('Y'))
	{
		$errors[] = 'Нельзя делать отчеты за будущие месяцы!';
	}	
	
	
}	

?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Отчет рентабельности</title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
		<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
		<link rel="stylesheet" href="css/index.css">
	</head>
	<body>
<div class="showcustomer">
		<p class="breadcrumbs">Отчет рентабельности</p>
		<div class="reg_sel_object1">
			<form action="" method="POST"  enctype="multipart/form-data">
			<table>
				<tr>
					<td class="rowt">Выберите заказчика:</td>
					<td colspan="2">
						<select name="id_customer" id="id_customer" class="StyleSelectBox"  onchange="this.form.submit()">
							<option disabled selected>Выберите заказчика:</option>
							<?php foreach($customers as $i => $customer) { ?>
								<?php if($id_customer_selected){
									$customer_sel = Edit_Customer($link, $id_customer_selected);
									$customer_name = Edit_Customer($link, $customer['id_customer']);
									?>
									<!-- <option  value="<?= $customer['id_customer']; ?>"<?= ($customer['id_customer'] == $customer_sel['id_customer']) ? 'selected' : ''?>>
										<?= $customer_name['customer_name']; ?>
									</option> -->
								<?php }?>
								<?php if(!$id_customer_selected){?>
									<!--<option  value="<?= $customer['id_customer']; ?>"><?= $customer['customer_name']; ?></option> -->
									
								<?php }?>									
							<?php } ?>
							<option value="all" <?= ($id_customer_selected == "all") ? 'selected' : ''?>><?= "Все заказчики" ?></option>
						</select>
					</td>
				<?php if(isset($id_customer_selected) AND $id_customer_selected!="0"){ ?>					
					<td class="rowt"><label for="zarplata">Зарплата:</label></td>
					<td><input id="zarplata" name="zarplata"   type="number" step="0.01" value="<?php echo @$zarplata;?>"/></td>
					<td class="rowt"><label for="zarnalog">Зарплатные налоги:</label></td>
					<td><input id="zarnalog" name="zarnalog"   type="number" step="0.01" value="<?php echo @$zarnalog;?>"/></td>
					<td class="rowt"><label for="nds">НДС:</label></td>
					<td><input id="nds" name="nds"  type="number" step="0.01" value="<?php echo @$nds;?>"/></td>
				<?php } ?>
				</tr>
				<?php if(isset($id_customer_selected) AND $id_customer_selected!="0"){
					$projects = Show_Active_Projects($link, $id_customer_selected);
				?>
				<tr>
					<td class="rowt">Статус заявки:</td>
					<td colspan="2">
					<select class="reg_select" name="ticket_status" id="ticket_status">
						<option  value="0">В работе</option>
						<option selected value="1">Закрыта</option> 
						<option value="2">На согласовании</option>
						<option value="3">Все заявки</option>
					</select>
					</td>
					<td class="rowt"><label for="profit_tax">Налог на прибыль:</label></td>
					<td><input id="profit_tax" name="profit_tax"  type="number" step="0.01" value="<?php echo @$profit_tax;?>"/></td>
					<td class="rowt"><label for="usn_tax">Налог УСН:</label></td>
					<td><input id="usn_tax" name="usn_tax"  type="number" step="0.01" value="<?php echo @$usn_tax;?>"/></td>
					<td class="rowt"><label for="other_tax">Прочие налоги:</label></td>
					<td><input id="other_tax" name="other_tax"  type="number" step="0.01" value="<?php echo @$other_tax;?>"/></td>				
				</tr>
				<tr>
					<td class="rowt">Статус платежа:</td>
					<td colspan="2">
					<select class="reg_select" name="payment_status" id="payment_status">
						<option value="0">Неоплачено</option>
						<option value="1">Оплачено</option>
						<option selected value="2">Любой</option>
					</select>
					</td>
					<td class="rowt"><label for="banks">Услуги банка:</label></td>
					<td><input id="banks" name="banks"  type="number" step="0.01" value="<?php echo @$banks;?>"/></td>
					<td class="rowt"><label for="network">Связь, интернет:</label></td>
					<td><input id="network" name="network"  type="number" step="0.01" value="<?php echo @$network;?>"/></td>
					<td class="rowt"><label for="rent">Аренда:</label></td>
					<td><input id="rent" name="rent"  type="number" step="0.01" value="<?php echo @$rent;?>"/></td>					
				</tr>
				<tr>
					<td class="rowt">Отчетный год:</td>
					<td colspan="2">
						<select class="reg_select" name="year" id="year" >
						<?php for($i = 2015; $i < 2050; $i++) { 
						if(isset($_POST['customer_report']))
						{
						?>
						<option  value="<?=$i;?>" <?= ($i == $year) ? 'selected' : ''?>><?=$i;?></option>
						<?php } else { ?>
						<option  value="<?=$i;?>" <?= ($i == date('Y')) ? 'selected' : ''?>><?=$i;?></option>
						<?php }} ?>
						</select>
					</td>

					<td class="rowt"><label for="own_expenses">Собственные расходы:</label></td>
					<td><input id="own_expenses" name="own_expenses"  type="number" step="0.01" value="<?php echo @$own_expenses;?>"/></td>					
				</tr>
				<tr>
					<td class="rowt">Отчетный период:</td> <!-- **********************ВЫБОР МЕСЯЦА***********************-->
					<td colspan='2'> с
					<select name="month_start" id="month" >
						<?php for($i = 1; $i < 13; $i++) {
						if(isset($_POST['customer_report']))
						{
						?>
							<option  value="<?= $i ?>" <?= ($i == $month_start) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
						<?php } else { ?>	
							<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
						<?php }
						} ?>
					</select>
					по
					<select name="month_end" id="month">
						<?php for($i = 1; $i < 13; $i++) { 
						if(isset($_POST['customer_report']))
						{ ?>
							<option  value="<?= $i ?>" <?= ($i == $month_end) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
						<?php } else { ?>	
							<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
						<?php }
						} ?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="rowt">Выберите проекты:</td>
					<td colspan="2">
					<?php if($projects) { 
						$project_count = count($projects);?>
						<select name="id_projects[]" id="id_projects" class="reg_select"  multiple size="<?=$project_count?>">
							<?php foreach($projects as $i => $project)  { ?>
								<option  value="<?= $project['id_project']; ?>"><?= $project['projectname']; ?></option>
							<?php  } ?>
						</select>
					<?php } elseif($id_customer_selected == "all") { ?>
					
					<span class="reg_text_all">Выбраны проекты у всех заказчиков!</span>
					
					<?php } else { ?>
						<span class="rowt">У данного заказчика нет проектов</span>
					<?php }?>
					</td>
				</tr>

				
				<?php }?>
			
			</table>
			<?php if(isset($id_customer_selected) AND $id_customer_selected!="0"){ ?>
			<button name="customer_report" class="button-new">Создать отчет</button>
			<?php }?>
			</form>
		</div>	
		</p>
<?php

if(isset($_POST['customer_report']) AND empty($errors))
	{	

	?>		
	
		<table border="1" cellspacing="0">
			<thead>
				<tr class="hdr_size">
					<th>Заказчик</th>
					<th>Проект</th>
					<th width="10%">Доход</th>
					<th width="10%">Расход</th>
					<th width="10%">Материалы</th>
					<th width="10%">Прочие расходы<br></th>
					<th width="10%">Рентабельность<br></th>
					
				</tr>
			</thead>
			<?php
			if($id_customer_selected !="all"){
				if(isset($_POST['id_projects'])){
				foreach($_POST['id_projects'] as $id_project) { 
				$projects_table = Edit_Project ($link, $id_project);
				$customer_table = Edit_Customer ($link, $projects_table['id_customer']);
				?>
				<tbody>
					<tr class="reports_table">
						<td align="center"><?=$customer_table['customer_name'];?></td>
						<td align="center"><?=$projects_table['projectname'];?></td>
						<td align="center"><?= "test" ?></td>
						<td  width="1" align="center"><?= "test"?></td>
						<td  width="1" align="center"><?= "test"?></td>
						<td  width="1" align="center"><?= "test"?></td>
					</tr>
				</tbody>
			<?php }}} else {
			$cash_abplata_month_summ = 0;
			$cash_abplata_month_contr_summ = 0;
			$all_cost_in_project_summ = 0;
			$cash_summ = 0;
			$all_cost_in_project = 0;
				foreach($customers as $i => $customer_t)  { 
				$projects_t = Show_Projects ($link, $customer_t['id_customer']);
				$customer_name_t = Edit_Customer ($link, $customer_t['id_customer']);
					foreach($projects_t as $i => $project_t)  
					{						
					$projects = Edit_Project ($link, $project_t['id_project']);
					$objects = Show_Objects_report ($link, $projects['id_project']);
						if($objects)
						{
							$cash_abplata_month = 0; //Месячная абонплата
							$all_cost_in_project = 0;
							$all_contr_cost = 0;
							$supplier_cost_summ = 0;
							foreach($objects as $object)
							{
								$id_object_for = $object['id_object'];
								//$objaboninfo = Edit_Object_with_abon($link, $id_object_for);
								$paystatusabon = "AND paystatus = 0";
								$customer_abonents = Show_objects_customer($link, $id_object_for, $year, $month_start, $month_end, $paystatusabon);
								if($customer_abonents)
								{
									foreach($customer_abonents as $customer_abonent)
									{
										$abon_plata = $customer_abonent['summ'];
										$cash_abplata_month_summ += floatval($abon_plata);
									}
								//$abon_plata = (int)$abon_plata;
								//$cash_abplata_month = $abon_plata * $month_period;
								//$cash_abplata_month_summ += floatval($cash_abplata_month); //Сумма месячных абонплат со всех объектов одного проекта
								}

								$contr_objects_info = Show_objects_contr_object($link, $id_object_for, $year, $month_start, $month_end, $paystatusabon);
								
								if($contr_objects_info)
								{
									foreach($contr_objects_info as $contr_object_info)
									{
										$abon_plata_contr = $contr_object_info['summ'];
										$cash_abplata_month_contr_summ += floatval($abon_plata_contr);
									}
								}								
							}
							$all_cost_in_project_summ += $all_cost_in_project;
							$cash_summ += $cash_abplata_month_summ;
							$cash_abplata_month = 0; //Месячная абонплата
							$all_cost_in_project = 0;
							foreach($objects as $object)
							{
								$odject_arr = $object['id_object'];
								//$abon_plata = (int)$abon_plata;
								$rep_tickets = Show_Rep_Tickets ($link, $odject_arr, $year, $ticket_status, $custompaystatus, $month_start, $month_end);
								//$k=0;
								if($rep_tickets)
								{
									foreach($rep_tickets as $rep_ticket)
									{
										if($rep_ticket['work_type'] == 1) //если выбран вид работы "Инцидентное обслуживание"
										{	
											switch ($rep_ticket['ticket_sla'])
											{
												case 0:
													$cost_incident = floatval($projects['cost_incident_critical']);
													break;
												case 1:
													$cost_incident = floatval($projects['cost_incident_high']);
													break;
												case 2:
													$cost_incident = floatval($projects['cost_incident_medium']);
													break;
												case 3:
													$cost_incident = floatval($projects['cost_incident_low']);
													break;
											}
										}
										else {
											$cost_incident = 0;
										}
										
										$contr_cost_work = floatval($rep_ticket['contr_cost_work']);
										$contr_cost_smeta = floatval($rep_ticket['contr_cost_smeta']);
										$contr_cost_transport = floatval($rep_ticket['contr_cost_transport']);
										$all_contr_cost += ($contr_cost_work + $contr_cost_smeta + $contr_cost_transport);
										
										$supplier_cost_work = floatval($rep_ticket['supplier_cost_work']);
										$supplier_cost_material = floatval($rep_ticket['supplier_cost_material']);
										
										$hours = floatval($rep_ticket['hours']);
										$sla = intval($rep_ticket['ticket_sla']);
										$cost_hour = $hours * floatval($projects['cost_hour']);
										$cost_smeta = floatval($rep_ticket['cost_smeta']);
										$cost_material = floatval($rep_ticket['cost_material']);
										$cost_transport = floatval($rep_ticket['cost_transport']);
										$all_cost_in_project += ($cost_incident + $cost_hour + $cost_smeta + $cost_material + $cost_transport);
										$supplier_cost_summ += $supplier_cost_work + $supplier_cost_material;
										
										
										
									}
								}
							}							
						}
						$profit = $cash_abplata_month_summ  + $all_cost_in_project;
						$profitprint = number_format($profit, 2, ',', ' ');
						$cash_abplata_month_summ_print = number_format($cash_abplata_month_summ, 2, ',', ' ');
						$all_cost_in_project_print = number_format($all_cost_in_project, 2, ',', ' ');
						$cash_abplata_month_contr_summ_print = number_format($cash_abplata_month_contr_summ, 2, ',', ' ');
						$all_contr_cost_print = number_format($all_contr_cost, 2, ',', ' ');
						$profit_contr = $cash_abplata_month_contr_summ + $all_contr_cost;
						$profit_contr_print = number_format($profit_contr, 2, ',', ' ');
						$supplier_cost_summ_print = number_format($supplier_cost_summ, 2, ',', ' ');
						$rashod = $profit_contr + $supplier_cost_summ;

						if($rashod>0)
						{
							$profitability = ($profit/$rashod)*100;
						}
						else
						{
							$profitability = 0;
						}
						$profitabilityprint = number_format($profitability, 2, ',', ' ');
						?>
						<tbody>
							<tr class="reports_table">
								<td align="center"><?=$customer_name_t['customer_name'];?></td>
								<td align="center"><?=$projects['projectname'];?></td>
								<td align="center" title = "<?= "Абонплата: ".$cash_abplata_month_summ_print."р. + Заявки: ".$all_cost_in_project_print."р." ?>"><?= $profitprint." р."?></td>
								<td  width="1" align="center" title = "<?= "Абонплата: ".$cash_abplata_month_contr_summ_print."р. + Заявки: ".$all_contr_cost_print."р." ?>"><?= $profit_contr_print." р."?></td>
								<td  width="1" align="center"><?= $supplier_cost_summ_print." р."; ?></td>
								<td></td>
								<td  width="1" align="center"><?= $profitabilityprint."%";?></td>
							</tr>
						</tbody>				
					<?php 
					
					$cash_abplata_month_summ = 0;
					$cash_abplata_month_contr_summ = 0;
					$all_contr_cost = 0;
					
					$profitsumm += $profit;
					$profitsummprint = number_format($profitsumm, 2, ',', ' ');
					$profit_contr_summ += $profit_contr;
					$profit_contr_summ_print = number_format($profit_contr_summ, 2, ',', ' ');
					$supplier_cost_summ2 += $supplier_cost_summ; 
					$supplier_cost_summ2print = number_format($supplier_cost_summ2, 2, ',', ' ');
						
	
	
	
	
	
	
	
					$other_exp = $zarplata + $zarnalog + $nds + $profit_tax + $usn_tax + $other_tax + $banks + $network + $rent + $own_expenses;
					$rashod_all = $profit_contr_summ + $supplier_cost_summ2 + $other_exp;
					
					if($rashod_all>0)
					{
						$profitability_all = ($profitsumm/$rashod_all)*100;
					}
					else
					{
						$profitability_all = 0;
					}
					$profitability_allprint = number_format($profitability_all, 2, ',', ' ');
					$other_expprint = number_format($other_exp, 2, ',', ' ');
					$supplier_cost_summ = 0;

					
					}}}?>
							<tr class="reports_table">
								<td align="center"></td>
								<td align="center"><b><?="ИТОГО:"?></b></td>
								<td align="center"><b><?= $profitsummprint." р."; ?></b></td>
								<td align="center"><b><?= $profit_contr_summ_print." р."; ?></b></td>
								<td align="center"><b><?= $supplier_cost_summ2print." р."; ?></b></td>
								<td align="center"><b><?= $other_expprint." р."; ?></b></td>
								<td align="center"><b><?= $profitability_allprint."%"; ?></b></td>								
							</tr>
			</table>
<?php } 	else	{
		$err=TRUE; ?>
		<div class="error-message"><?=array_shift($errors)?></div>
		<br>
	<?php }	?>	



	
		<div id="footer">&copy; ООО "МегаТрейд"</div>
		</div>
		
	</body>
</html>
