<?php
require '/connection/config.php';
ob_start();
require_once '/blocks/header.php'; 
require '/func/arrays.php';

$date = date('d-m-Y');
$customers = Show_Customer ($link);
$id_customer_selected = "0";
$err = FALSE;
if(isset($_POST['id_customer']))
{
	$id_customer_selected = trim(filter_input(INPUT_POST, 'id_customer'));
	if($id_customer_selected != "1select")
	{
		$customer_sel = Edit_Customer($link, $id_customer_selected);
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
		<div class="reg_sel_object">
			<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
			<?php }?>
			<br>
			<form action="" method="POST"  enctype="multipart/form-data">
			<table>
				<tr>
					<td class="rowt">Выберите заказчика:</td>
					<td colspan="2">
						<select name="id_customer" id="id_customer" class="StyleSelectBox"  onchange="this.form.submit()">
							<option disabled selected>Выберите заказчика:</option>
							<?php foreach($customers as $i => $customer)  { ?>
								<?php if($id_customer_selected){
									$customer_sel = Edit_Customer($link, $id_customer_selected);
									$customer_name = Edit_Customer($link, $customer['id_customer']);
									?>
									<option  value="<?= $customer['id_customer']; ?>"<?= ($customer['id_customer'] == $customer_sel['id_customer']) ? 'selected' : ''?>>
										<?= $customer_name['customer_name']; ?>
									</option>
								<?php }?>
								<?php if(!$id_customer_selected){?>
									<option  value="<?= $customer['id_customer']; ?>"><?= $customer['customer_name']; ?></option>
									
								<?php }?>									
							<?php } ?>
							
							<option  value="all" <?= ($id_customer_selected == "all") ? 'selected' : ''?>><?= "Все заказчики" ?></option>
						</select>
					</td>
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
				</tr>
				<tr>
					<td class="rowt">Статус платежа:</td>
					<td colspan="2">
					<select class="reg_select" name="payment_status" id="payment_status">
						<option selected value="0">Неоплачено</option>
						<option value="1">Оплачено</option>
						<option value="2">Любой</option>
					</select>
					</td>
				</tr>
				<tr>
					<td class="rowt">Отчетный год:</td>
					<td colspan="2">
						<select class="reg_select" name="year" id="year" >
						<?php for($i = 2015; $i < 2050; $i++) { ?>
						<option  value="<?=$i;?>" <?= ($i == date('Y')) ? 'selected' : ''?>><?=$i;?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="rowt">Отчетный период:</td> <!-- **********************ВЫБОР МЕСЯЦА***********************-->
					<td colspan='2'> с
					<select name="month_start" id="month" >
						<?php for($i = 1; $i < 13; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
						<?php } ?>
					</select>
					по
					<select name="month_end" id="month">
						<?php for($i = 1; $i < 13; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
						<?php } ?>
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
				<tr>
				<td><button name="customer_report" class="button-new">Создать отчет</button></td>
				
				</tr>
				<?php }?>
			</form>
			</table>
		</div>	
		</p>
<?php
if(isset($_POST['customer_report']))
{ ?>		

		<table border="1" cellspacing="0">
			<thead>
				<tr class="hdr">
					<th >Заказчик</th>
					<th >Проект</th>
					<th >Доход</th>
					<th >Расход</th>
					<th >Материалы<br></th>
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
					<tr class="reg_text_show_tickets">
						<td align="center"><?=$customer_table['customer_name'];?></td>
						<td align="center"><?=$projects_table['projectname'];?></td>
						<td align="center"><?= "test" ?></td>
						<td  width="1" align="center"><?= "test"?></td>
						<td  width="1" align="center"><?= "test"?></td>
					</tr>
				</tbody>
			<?php }}} else {
	
				foreach($customers as $i => $customer_t)  { 
				$projects_t = Show_Projects ($link, $customer_t['id_customer']);
				$customer_name_t = Edit_Customer ($link, $customer_t['id_customer']);
				foreach($projects_t as $i => $project_t)  { 
				$projects_info = Edit_Project ($link, $project_t['id_project']);
				?>
				<tbody>
					<tr class="reg_text_show_tickets">
						<td align="center"><?=$customer_name_t['customer_name'];?></td>
						<td align="center"><?=$projects_info['projectname'];?></td>
						<td align="center"><?= "test" ?></td>
						<td  width="1" align="center"><?= "test"?></td>
						<td  width="1" align="center"><?= "test"?></td>
					</tr>
				</tbody>				
			<?php }}} ?>				
				
				
			</table>
<?php } ?>		
		<div id="footer">&copy; ООО "МегаТрейд"</div>
		</div>
		
	</body>
</html>
