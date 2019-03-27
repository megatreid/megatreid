<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<3)
{
require_once '/blocks/header.php';
if(isset($_GET['id_customer']))
{
	$id_customer = $_GET['id_customer'];
	$data_post = $_POST;
	$customers = Edit_Customer($link, $id_customer);
	$projects = Show_Projects($link, $id_customer);
	$err=FALSE;	
	$projectname = trim(filter_input(INPUT_POST, 'projectname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$cost_hour = trim(filter_input(INPUT_POST, 'cost_hour', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$cost_incident_critical = trim(filter_input(INPUT_POST, 'cost_incident_critical', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$cost_incident_high = trim(filter_input(INPUT_POST, 'cost_incident_high', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$cost_incident_medium = trim(filter_input(INPUT_POST, 'cost_incident_medium', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$cost_incident_low = trim(filter_input(INPUT_POST, 'cost_incident_low', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	
	if( isset($data_post['new_project']))
		{
			$errors=array();//массив сообшений ошибок
		if(empty($projectname))
		{
			$errors[] = 'Введите название проекта!';
		}
		if( mb_strlen($projectname)>50 or mb_strlen($projectname)<2)
		{
			$errors[] = 'Название проекта должно содержать не менее 2 и не более 50 символов!';
		}	
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */	
		if(empty($cost_hour))
		{
			$cost_hour = 0;
		}
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */		
		if(empty($cost_incident_critical))
		{
			$cost_incident_critical = 0;
		}
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */		
		if(empty($cost_incident_high))
		{
			$cost_incident_high = 0;
		}
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */		
		if(empty($cost_incident_medium))
		{
			$cost_incident_medium = 0;
		}
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */		
		if(empty($cost_incident_low))
		{
			$cost_incident_low = 0;
		}

		if(empty($errors)){  
			
			$result = Add_Project ($link, $id_customer, $projectname, $status, $cost_hour, $cost_incident_critical, $cost_incident_high, $cost_incident_medium, $cost_incident_low)
			?>		
			<script>
				setTimeout(function() {window.location.href = 'showprojects.php?id_customer=<?=$id_customer;?>';}, 0);
			</script>	
			<?php		
		}
		else
			{
				$err=TRUE;
				//echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
			}
	}
	
	
}

	
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Новый проект</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
</head>
<body>
	<div class="showany">
		<p class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > <a href='showprojects.php?id_customer=<?= $customers['id_customer'] ?>'>Проекты (<?=$customers['customer_name'];?>)</a> > Новый проект:</p>
		<div class="reg_sel_object">
		<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
		<?php }?>
			<form action="newproject.php?id_customer=<?=$id_customer;?>" method="POST">
			<p style = "font-size: 8pt">Поля, отмеченные звездочкой, являются обязательными</p>
				<table>
				<tr>
					<td class="rowt"><label for="projectname">Наименование:*</label></td>
					<td><input id="projectname" class="StyleSelectBox" maxlength="50"  name="projectname" type="text" value="<?=@$projectname;?>"/></td>
				</tr>
				<tr class="status">
					<td class="rowt">Статус проекта:*</td>
					<td>
						<select name="status" class="StyleSelectBox" >

							<option value="0">Неактивный</option>
							<option value="1" selected>Активный</option>

						</select>
					</td>
				</tr>				
				<tr>
					<td class="rowt"><label for="cost_hour">Почасовой тариф:</label></td>
					<td><input class="StyleSelectBox"  id="cost_hour" name="cost_hour" type="number" min="0" value="<?=@$cost_hour;?>"/> руб.</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<b>Стоимость инцидента:</b>
					</td>
				</tr>
				<tr>
					<td class="rowt"><label for="cost_incident_critical">Критический:</label></td>
					<td><input class="StyleSelectBox"  id="cost_incident_critical" name="cost_incident_critical" type="number" min="0" value="<?=@$cost_incident_critical;?>"/> руб.</td>
				</tr>
				<tr>
					<td class="rowt"><label for="cost_incident_high">Высокий:</label></td>
					<td><input class="StyleSelectBox"  id="cost_incident_high" name="cost_incident_high" type="number" min="0" value="<?=@$cost_incident_high;?>"/> руб.</td>
				</tr>
				<tr>
					<td class="rowt"><label for="cost_incident_medium">Средний:</label></td>
					<td><input class="StyleSelectBox"  id="cost_incident_medium" name="cost_incident_medium" type="number" min="0" value="<?=@$cost_incident_medium;?>"/> руб.</td>
				</tr>
				<tr>
					<td class="rowt"><label for="cost_incident_low">Низкий:</label></td>
					<td><input class="StyleSelectBox"  id="cost_incident_low" name="cost_incident_low" type="number" min="0" value="<?=@$cost_incident_low;?>"/> руб.</td>
				</tr>
				</table>
				<div>
					<p>
						<button name="new_project" class="button">Сохранить</button>
					</p>
				</div>
			</form>
		</div>	
	</div>
	<br>
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