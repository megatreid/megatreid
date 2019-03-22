<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<3)
{
	require_once '/blocks/header.php';
	require '/func/arrays.php';
	if(isset($_GET['edit_project']))
{
	$data = $_GET['edit_project'];
	$data_post = $_POST;
	$_SESSION['edit_project'] = $data;
	$projects = Edit_Project($link, $data);
	$id_customer = $projects['id_customer'];
	$customers = Edit_Customer($link, $id_customer);
	$projectname = $projects['projectname'];
	$status = $projects['status'];
	$cost_hour = $projects['cost_hour'];
	$cost_incident_critical = $projects['cost_incident_critical'];
	$cost_incident_high = $projects['cost_incident_high'];
	$cost_incident_medium = $projects['cost_incident_medium'];
	$cost_incident_low = $projects['cost_incident_low'];
	/*********************************/
	$projectname_edit = trim(filter_input(INPUT_POST, 'projectname'));
	$status_edit = trim(filter_input(INPUT_POST, 'status'));
	$cost_hour_edit = trim(filter_input(INPUT_POST, 'cost_hour'));
	$cost_incident_critical_edit = trim(filter_input(INPUT_POST, 'cost_incident_critical'));
	$cost_incident_high_edit = trim(filter_input(INPUT_POST, 'cost_incident_high'));
	$cost_incident_medium_edit = trim(filter_input(INPUT_POST, 'cost_incident_medium'));
	$cost_incident_low_edit = trim(filter_input(INPUT_POST, 'cost_incident_low'));




$err=FALSE;	

if( isset($data_post['edit_project']))
	{
		$errors=array();//массив сообшений ошибок
		if(empty($projectname))
		{
			$errors[] = 'Введите название проекта!';
		}
		if( mb_strlen($projectname)>20 or mb_strlen($projectname)<2)
		{
			$errors[] = 'Название проекта должно содержать не менее 2 и не более 20 символов!';
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
		
		$result = Update_Project ($link, $data, $id_customer, $projectname_edit, $status_edit, $cost_hour_edit, $cost_incident_critical_edit, $cost_incident_high_edit, $cost_incident_medium_edit, $cost_incident_low_edit);
		if($result){
		//unset($_SESSION['id_customer']);
		?>		
		<script>
			setTimeout(function() {window.location.href = '/showprojects.php?id_customer=<?=$id_customer;?>';}, 0);
		</script>	
		<?php
		}		
    }
	else
		{
			$err=TRUE;
			//echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
		}
	}
	if(isset($data_post['delete_project']))
	{
		$delete_project = Delete_Project($link, $data);
		if($delete_project)
		{
			unset($_SESSION['edit_project']);
			?>
				<script>
					setTimeout(function() {window.location.href = '/showprojects.php?id_customer=<?=$id_customer;?>';}, 0);
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
	<title>Редактирование проекта</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
</head>
<body>
	<div class="showany">
		<p class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > <a href='showprojects.php?id_customer=<?= $customers['id_customer'] ?>'>Проекты (<?=$customers['customer_name'];?>)</a> > Редактирование: <?=$projects['projectname'];?></a></p>
		<div class="reg_sel_object">
		<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
		<?php }?>
			<form action="editproject.php?edit_project=<?=$_SESSION['edit_project']?>" method="POST">
			<p style = "font-size: 8pt">Поля, отмеченные звездочкой, являются обязательными</p>
			<input name="id_project" type="hidden" value="<?=$_SESSION['edit_project']?>">
				<table>
				<tr>
					<td class="rowt"><label for="projectname">Наименование:*</label></td>
					<td><input id="projectname" name="projectname" type="text" value="<?=$projectname;?>"/></td>
				</tr>
				<tr class="status">
					<td class="rowt">Статус проекта: *</td>
					<td>
						<select name="status" class="StyleSelectBox">
						<?php for($i = 0; $i < 2; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == $status) ? 'selected' : ''?>><?= $statusedit[$i] ?></option>
						<?php } ?>
						</select>
					</td>
				</tr>				
				<tr>
					<td class="rowt"><label for="cost_hour">Почасовой тариф:</label></td>
					<td><input id="cost_hour" name="cost_hour" type="number" min="0" value="<?=$cost_hour;?>"/> руб.</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<b>Стоимость инцидента:</b>
					</td>
				</tr>
				<tr>
					<td class="rowt"><label for="cost_incident_critical">Критический:</label></td>
					<td><input id="cost_incident_critical" name="cost_incident_critical" type="number" min="0" value="<?=$cost_incident_critical;?>"/> руб.</td>
				</tr>
				<tr>
					<td class="rowt"><label for="cost_incident_high">Высокий:</label></td>
					<td><input id="cost_incident_high" name="cost_incident_high" type="number" min="0" value="<?=$cost_incident_high;?>"/> руб.</td>
				</tr>
				<tr>
					<td class="rowt"><label for="cost_incident_medium">Средний:</label></td>
					<td><input id="cost_incident_medium" name="cost_incident_medium" type="number" min="0" value="<?=$cost_incident_medium;?>"/> руб.</td>
				</tr>
				<tr>
					<td class="rowt"><label for="cost_incident_low">Низкий:</label></td>
					<td><input id="cost_incident_low" name="cost_incident_low" type="number" min="0" value="<?=$cost_incident_low;?>"/> руб.</td>
				</tr>
				</table>
				<div>
					<input class="button" value="Сохранить" type="submit" name="edit_project"/>
					<input class="button" value="К списку проектов" type="button" onclick="location.href='showprojects.php?id_customer=<?=$id_customer;?>'"/>
					<input class="button-delete" value="Удалить" type="submit" onclick='return confirm("Вы уверены, что хотите удалить эти данные?")' name="delete_project"/>				
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