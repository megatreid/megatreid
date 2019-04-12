<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3)
{
require_once '/blocks/header.php';
	
//require_once 'Classes/PHPExcel.php';

$country_id = "3159";
$citys=array();
$data = $_POST;
$err=FALSE;
$insert = false;
$searching = false;
$regions = Show_Region ($link, $country_id);
$city_name = trim(filter_input(INPUT_POST, 'city_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$region_name = trim(filter_input(INPUT_POST, 'region_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

if( isset($data['do_search']))
	{
		$errors=array();//массив сообшений ошибок
		
		if(empty($city_name) AND empty($region_name)){
			$errors[] ='not_empty';
		}

		if(empty($errors))
		{
			$searching = TRUE;
			$citys = geo_search($link, $city_name, $region_name, $country_id);
		}
		/*
			$city_add = Add_City ($link, $country_id, $region_id, $city_name);
			if($city_add)
			{
				$region = Get_Geo ($link, $region_id, "region", "region_id");
				$msg = 'Вы добавили в "'.$region['name'].'" населенный пункт:<br> "'.$city_name.'"';
			}
		}*/
		else
		{
			$err=TRUE;
		}
			
	
	}
if(isset($data['form_new_city']))
{
	$searching = TRUE;
}

$region_id = trim(filter_input(INPUT_POST, 'region_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$new_cityname = trim(filter_input(INPUT_POST, 'new_cityname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$city_exist = City_Exist($link, $new_cityname, $region_id);
if( isset($data['new_city']))
{
	$errors=array();//массив сообшений ошибок
	
	if(empty($region_id))
	{
		$errors[] = 'Не выбран регион!';
	}
	if(empty($new_cityname))
	{
		$errors[] = 'Не указано название населенного пункта!';
	}	
	if($city_exist)
	{
		$errors[] = 'В этом регионе есть такой населенный пункт!';
	}	
	if(empty($errors))
	{		
		$city_add = Add_City ($link, $country_id, $region_id, $new_cityname);
		if($city_add)
		{
			//$region = Get_Geo ($link, $region_id, "region", "region_id");
			//$msg = 'Вы добавили в "'.$region['name'].'" населенный пункт:<br> "'.$new_cityname.'"';
		}
	}
	else
		{
			$err=TRUE;
			$searching = TRUE;
		}
}


	
?>
	<!DOCTYPE html>
	<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>Географические объекты</title>
		<script type="text/javascript" src='js/jquery.js'></script>
	</head>

	<body>
<div class="showany">
<br>
	<div class="reg_sel_object">
	<p class="breadcrumbs"> Редактирование географических объектов:</p>

		<!--<form action="" method="POST">
		
			<tr>
				<td class="rowt">Регион РФ:</td>
				<td>
				<select name="region_id" class="StyleSelectBox">
					<option value="0">- выберите регион -</option>
					<?php foreach($regions as $i => $region)  { ?>
						<option  value="<?= $region['region_id']; ?>"><?= $region['name']; ?></option>
					<?php } ?>
				</select>
				</td>
				-->
			<form action="" method="POST">
				<table>
				<tr>
				<td class="rowt_left">
					<input align="center" type="search" name="city_name" placeholder="Город..."> 
				</td>
				<td class="rowt">
					<input align="center" type="search" name="region_name" placeholder="Регион..."> 
				</td>				
				</tr>
				<tr>
				<td></td>
				<td class="rowt">
					<input name="do_search" class="button-new" type="submit" value="Найти город">
				</td>
				</tr>
				<tr>
				<td class="rowt_left">
					<input name="form_new_city" class="button-new" type="submit" value="Форма добавления города">
				</td>
				<td class="rowt">
				<a href="showregion.php" class="button-new">Регионы РФ</a>
					
				</td>
				</tr>
				</table>
		<!--	</form>	 -->
<?php if($citys){?>
<table border="1">
	<thead>
		<tr class="hdr">
			<th width="1">№</th>
			<!--<th>Страна</th>-->
			<th>Название</th>
			<th>Регион</th>
			<th width="1">Действие</th>

		</tr>
		<tr class='table-filters'>
			<td>
			</td>
			<td>
				<input class="reg_input_filter" type="text" placeholder="..."/><!--Название-->
			</td>
			<td>
				<input class="reg_input_filter" type="text" placeholder="..."/><!--Название-->
			</td>			
			<td colspan="1">
			</td>
		</tr>
	</thead>
<?php	
foreach($citys as $i => $city) { ?>
	<tr class="reg_text_show_tickets">
		<td align="center" width="1"><?=$i + 1?></td>
		<td align="center"><?=$city['name']?></td>
		<?php
		$geo_name= Get_Geo ($link, $city['region_id'], "region", "region_id" );?>
		
		<td align="center"><a href='editregion.php?id_region=<?= $city['region_id']; ?>' title = 'Изменить'><?=$geo_name['name'];?></a></td>
		<td align="center" width="1">
			<a href='editcity.php?id_city=<?= $city['city_id'] ?>' title = 'Изменить'><img src='/images/edit.png' width='20' height='20'></a>
		</td>
	</tr>
<?php } ?>
</table>

<?php }
//if(empty($citys) AND $searching==TRUE) { ?>

	<!--<br><input name="ask_insert" type="submit" value="Добавить?">
	<a href="newcity.php">Хотите добавить?</a> -->

	
<?php //} ?>
<?php 
if(empty($citys) AND $searching==TRUE) { ?>
<span class='reg_text'>Не нашли город в базе данных? Можно добавить!	</span>
	<p class="breadcrumbs"> Добавление населенного пункта:</p>
	<?php if($err==TRUE){?>
		<div class="error-message"><?=array_shift($errors)?></div>
	<?php }?>
		<form action="" method="POST">
		<table>
			<tr>
				<td class="rowt">Регион РФ:</td>
				<td>
				<select name="region_id" class="StyleSelectBox">
					<option value="0">- выберите регион -</option>
					<?php foreach($regions as $i => $region)  { ?>
						<option  value="<?= $region['region_id']; ?>"><?= $region['name']; ?></option>
					<?php } ?>
				</select>
				</td>
				
			</tr>

			<tr>
				<td class="rowt">Населенный пункт:</td>
				<td>
					<input class="StyleSelectBox" name="new_cityname" type="text"/>
					
				</td>
			</tr>
			<?php if(isset($msg) AND !empty($msg)){ ?>
			<tr>
				<td colspan="2" align="center">

					<?=$msg;?>
					
				</td>
			</tr> 
			<?php }?>
			</table>
			<div>
				<p><button name="new_city">Добавить населенный пункт</button></p>
			</div>
<?php }?>
	</form>
	</div>
	<!-- <div id="footer">&copy; ООО "МегаТрейд"</div> -->
	</div>
<script type="text/javascript" src='js/filter_showticket.js'></script>
	</body>
	</html>
	<?php
}
	else
	{
		header('Location: /');
}
?>