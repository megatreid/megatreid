<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3)
{
require_once '/blocks/header.php';
$country_id = "3159";//Россия

if(isset($_GET['id_region']))
{
	$id_region = trim(filter_input(INPUT_GET, 'id_region', FILTER_SANITIZE_NUMBER_INT));
	$geo_info= Get_Geo ($link, $id_region, "region", "region_id");	
	$region_name = $geo_info['name'];
	$data_post = $_POST;
	$new_region_name = trim(filter_input(INPUT_POST, 'new_region_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	
	$err=FALSE;	
	
	if( isset($data_post['edit_region_submit']))
	{
		$errors=array();//массив сообшений ошибок
		if(empty($new_region_name))
		{
			$errors[] = 'Укажите название региона!';
		}
		if(empty($errors))
		{  
			$result = Update_Region($link, $country_id, $id_region, $new_region_name);
			if(isset($result)){ ?>		
				<script>
					setTimeout(function() {window.location.href = 'showregion.php';}, 0);
				</script>	
				<?php
			}	
		}
	}
	if(isset($data_post['delete_region']))
	{
		$delete_region = Delete_Region($link, $id_region, $country_id);
		if($delete_region)
		{
			?>
				<script>
					setTimeout(function() {window.location.href = 'showregion.php';}, 0);
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
	<title>Редактирование региона</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
</head>
<body>
	<div class="showany">

		<span class="breadcrumbs"><a href="geo_update.php">Редактирование географических объектов</a> > <a href="showregion.php">Регионы</a> > Редактирование: </span>
		<div class="reg_sel_object">
		<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
		<?php }?>
			<form action="editregion.php?id_region=<?=$id_region;?>" method="POST">
			<table>
			<tr>
				<td  class="rowt">Регион:</td>
				<td>
					<input class="StyleSelectBox" name="new_region_name" type="text" value="<?=$region_name;?>"/>
				</td>				
			
			</table>
			<p class="showusers">Вы можете изменить название региона
<?php if(isset($_SESSION['userlevel']) AND  $_SESSION['userlevel'] == 1) { ?>
			или удалить его из базы данных.</p>
			<?php }?>		
				<div>
					<input class="button" value="Сохранить" type="submit" name="edit_region_submit"/>
					<input class="button" value="Назад" type="button" onclick="location.href='showregion.php'"/>
					<?php if(isset($_SESSION['userlevel']) AND  $_SESSION['userlevel'] == 1) { ?>
					<a href="#delete_object" class="button-delete">Удалить</a>
					<div id="delete_object" class="modalDialog">
						<div>
							<!-- <a href="#close"  title="Закрыть" class="close">X</a> -->
						<h2>Удаление объекта</h2>
						<p>Вы уверены, что хотите удалить этот объект?</p>
						<p>Это может привести к потери данных в других разделах системы!</p>
						<input class="button-delete" value="Да" name="delete_region" type="submit"/>
						<a href="#close"  title="Отменить" class="button">Нет</a>
						<!-- <button class="button-delete" onclick='return confirm("Вы уверены, что хотите удалить эту заявку?")' name="delete_ticket">Удалить заявку</button> -->
						</div>
					</div>
					<?php }?>
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