<?php
require '/connection/config.php';
require_once '/blocks/header.php';
$country_id = "3159";//Россия
$regions = Show_Region ($link, $country_id);
if(isset($_GET['id_city']))
{
	$id_city = trim(filter_input(INPUT_GET, 'id_city', FILTER_SANITIZE_NUMBER_INT));
	
	$geo_info= Get_Geo ($link, $id_city, "city", "city_id");	
	
	$data_post = $_POST;
	$city_name_edit = trim(filter_input(INPUT_POST, 'city_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$region_id = trim(filter_input(INPUT_POST, 'region_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	
	$err=FALSE;	
	
	if( isset($data_post['edit_city']))
	{
		$errors=array();//массив сообшений ошибок
		if(empty($city_name_edit))
		{
			$errors[] = 'Укажите название населенного пункта!';
		}
		if(empty($errors))
		{  
			$result = Update_City($link, $id_city, $region_id, $city_name_edit);
			if(isset($result)){ ?>		
				<script>
					setTimeout(function() {window.location.href = 'geo_update.php';}, 0);
				</script>	
				<?php
			}	
		}
	}
	if(isset($data_post['delete_city']))
	{
		$delete_city = Delete_City($link, $id_city, $region_id, $country_id);
		if($delete_city)
		{
			
			?>
				<script>
					setTimeout(function() {window.location.href = 'geo_update.php';}, 0);
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
	<title>Редактирование населенного пункта</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/selects.js'></script>
	<script type="text/javascript" src='js/contractor_select.js'></script>	
</head>
<body>
	<div class="showany">
		<br>
		<div class="reg_sel_object">
		<p class="breadcrumbs">Редактирование:</p>
		<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
		<?php }?>
			<form action="editcity.php?id_city=<?=$id_city;?>" method="POST">
			<table>
			<tr>
				<td  class="rowt">Регион:</td>
				<td>
					<select name="region_id" class="StyleSelectBox">
						<option value="0">- выберите регион -</option>
						<?php foreach($regions as $i => $region)  { ?>
							<option  value="<?= $region['region_id']; ?>" <?=$region['region_id'] == $geo_info['region_id'] ? 'selected' : '';?>><?= $region['name']; ?></option>
						<?php } ?>
					</select>
				</td>				
				<tr>
					<td class="rowt">
						<label for="city_name">Название:</label>
					</td>
					<td>
						<input class="StyleSelectBox" id="city_name" name="city_name" type="text" value="<?=$geo_info['name'];?>"/>
					</td>
				</tr>				
			</table>
			<p>Вы можете изменить регион, к которому относится данный населенный пункт, а также название населенного пункта.</p>
				<div>
					<input class="button" value="Сохранить" type="submit" name="edit_city"/>
					<input class="button" value="Назад" type="button" onclick="location.href='geo_update.php'"/>
					<?php if(isset($_SESSION['userlevel']) AND  $_SESSION['userlevel']<3) { ?>
				<a href="#delete_object" class="button-delete">Удалить</a>
					<div id="delete_object" class="modalDialog">
						<div>
							<!-- <a href="#close"  title="Закрыть" class="close">X</a> -->
						<h2>Удаление объекта</h2>
						<p>Вы уверены, что хотите удалить этот объект?</p>
						<p>Это может привести к потери данных в других разделах системы!</p>
						<input class="button-delete" value="Да" name="delete_city" type="submit"/>
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