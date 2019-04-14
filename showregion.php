<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3)
{
	require_once '/blocks/header.php';
	require '/func/arrays.php';
	$country_id = "3159";//Российская Федерация
	$data = $_POST;
	$err=FALSE;
	$msg = false;
	$region_name = trim(filter_input(INPUT_POST, 'region_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	if(isset($data['new_region']))
	{
		$errors=array();//массив сообшений ошибок
		
		if(empty($region_name))
		{
			$errors[] ='not_empty';
		}

		if(empty($errors))
		{
			$new_region = New_Region($link, $country_id, $region_name);

			if($new_region)
			{
				$msg = 'Вы добавили регион: '.$region_name;
			}
		}
		else
		{
			$err=TRUE;
		}
			

	}

	$regions = Show_Region ($link, $country_id);

	?>
	<!DOCTYPE html>
	<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>Регионы</title>
		<script type="text/javascript" src='js/jquery.js'></script>
	</head>
	<body>
		<div class="showregion">
			<div class="breadcrumbs"><a href="geo_update.php">Редактирование географических объектов</a> > Регионы:</div>
			
			<?php if($_SESSION['userlevel']<=3){ ?>
			<form action="" method="POST">
				<input align="center" type="text" name="region_name" placeholder="Название региона"> 
				<input name="new_region" class="button-new" type="submit" value="Добавить новый регион">
			</form>
			
			<?php }
			if($msg) { ?>
				<p><?=$msg;?></p>
				
			<?php } ?>
			<table border="1">
				<thead>
					<tr class="hdr">
						<th width="1">№</th>
						<!--<th>Страна</th>-->
						<th>Название</th>
						<th width="1">Действие</th>
						<!--
						<th width="1">Населенные<br>пункты</th>
						-->
					</tr>
					<tr class='table-filters'>
						<td>
						</td>
						<td>
							<input class="reg_input_filter" type="text" placeholder="фильтр"/><!--Название-->
						</td>
						<td colspan="3">

						</td>
					</tr>
				</thead>	
		<?php if($regions){
			foreach($regions as $i => $region) { 
			?>
				<tr class="reg_text_show_tickets">
					<td align="center" width="1"><?=$i + 1?></td>
					<td align="center"><?=$region['name']?></td>

					<td align="center" width="1">
						<a href='editregion.php?id_region=<?= $region['region_id'] ?>' title = 'Изменить'><img src='/images/edit.png' width='20' height='20'></a>
					</td>
					<!--
					<td align="center" width="1">
						<a href='showcity.php?id_region=<?= $region['region_id'] ?>' title = 'Проекты'><img src='/images/new-york.png' width='20' height='20'></a>
					</td>
					-->
				</tr>
	<?php }} else { ?>
					<tr>
						<td colspan="9" align="center" class="date">Не добавлен ни один регион</td>
					</tr>
	<?php } ?>
			</table>
		</div>
		<script type="text/javascript" src='js/filter_showticket.js'></script>
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