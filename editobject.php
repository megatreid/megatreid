<?php
require 'connection/config.php';

if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3)
{
	require_once 'blocks/header.php';
	require 'func/arrays.php';
	if(isset($_GET['edit_object']))
{
	//$data = $_GET['edit_object'];
	$data = trim(filter_input(INPUT_GET, 'edit_object', FILTER_SANITIZE_NUMBER_INT));
	$_SESSION['edit_object'] = $data;
	$objects = Edit_Object($link, $data); //массив по объекту
	$id_project = $objects['id_project'];
	$projects = Edit_Project($link, $id_project);// массив по проекту
	$id_customer = $projects['id_customer'];
	$customers = Edit_Customer($link, $id_customer); //массив по заказчику
	$contractors = Show_Contr_for_select ($link);
	$show_city_names = Show_City_Name($link);
	$projectname = $projects['projectname'];
	$country_id = $objects['country_id'];
	
	$region_id = $objects['region_id'];
	$city_id = $objects['city_id'];
	$country = Get_Geo ($link, $country_id, "country", "country_id");
	$region = Get_Geo ($link, $region_id, "region", "region_id");
	$city = Get_Geo ($link, $city_id, "city", "city_id" );
	$contractor_select = Edit_Contr ($link, $objects['id_contractor']);
	$shop_number = $objects['shop_number'];
	$address = $objects['address'];
	$status = $objects['status'];
	$abon_plata = $objects['abon_plata'];
	$abon_plata_contr = $objects['abon_plata_contr'];
	/*********************************/
	$data_post = $_POST;
	$country_id_edit = trim(filter_input(INPUT_POST, 'country_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$region_id_edit = trim(filter_input(INPUT_POST, 'region_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$city_id_edit = trim(filter_input(INPUT_POST, 'city_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$shop_number_edit = trim(filter_input(INPUT_POST, 'shop_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$address_edit = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$status_edit = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$abon_plata_edit = trim(filter_input(INPUT_POST, 'abon_plata', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$id_contractor_edit = trim(filter_input(INPUT_POST, 'id_contractor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));	
	$abon_plata_contr_edit = trim(filter_input(INPUT_POST, 'abon_plata_contr', FILTER_SANITIZE_FULL_SPECIAL_CHARS));	
	$err=FALSE;	

if( isset($data_post['edit_object']))
		{
			$errors=array();//массив сообшений ошибок
			if(empty($country_id_edit) OR $country_id_edit == 0)
			{
				$errors[] = 'Выберите страну!';
			}
	/* ------------------------------------------------------------------------------------------------- */	
			if(empty($region_id_edit) OR $region_id_edit == 0)
			{
				$errors[] = 'Выберите область!';
			}
	/* ------------------------------------------------------------------------------------------------- */
			if(empty($city_id_edit) OR $city_id_edit == 0)
			{
				$errors[] = 'Выберите город!';
			}
	/* ------------------------------------------------------------------------------------------------- */
			if(empty($shop_number_edit))
			{
				$errors[] = 'Укажите объект!';
			}
			if( mb_strlen($shop_number_edit,'UTF-8')>20 or mb_strlen($shop_number_edit,'UTF-8')<2)
			{
				$errors[] = 'Название объекта должно содержать не менее 2 и не более 50 символов!';
			}	
	/* ------------------------------------------------------------------------------------------------- */
			if(empty($address_edit))
			{
				$errors[] = 'Укажите адрес объекта!';
			}
			if( mb_strlen($address_edit,'UTF-8')>200 or mb_strlen($address_edit,'UTF-8')<2)
			{
				$errors[] = 'Адрес объекта должен содержать не менее 2 и не более 200 символов!';
			}
			if(empty($abon_plata_edit))
			{
				$abon_plata_edit = 0;
			}
			if(empty($id_contractor_edit))
			{
				$id_contractor_edit="0";
				$abon_plata_contr_edit="0";
			}
	if(empty($errors)){  
		
		$result = Update_Object ($link, $data, $id_project, $id_customer, $country_id_edit, $region_id_edit, $city_id_edit, $shop_number_edit, $address_edit, $status_edit, $abon_plata_edit, $id_contractor_edit, $abon_plata_contr_edit);
		if(isset($result)){
		unset($_SESSION['id_customer']);
		?>		
		<script>
			setTimeout(function() {window.location.href = '/showobjects.php?id_project=<?=$id_project;?>';}, 0);
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
	if(isset($data_post['delete_object']))
	{
		$delete_object = Delete_Object($link, $data);
		if($delete_object)
		{
			unset($_SESSION['edit_object']);
			?>
				<script>
					setTimeout(function() {window.location.href = '/showobjects.php?id_project=<?=$id_project?>';}, 0);
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
	<title>Редактирование объекта</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/selects.js'></script>
	<script type="text/javascript" src='js/contractor_select.js'></script>	
</head>
<body>
	<div class="showany">
		<p class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > <a href='showprojects.php?id_customer=<?= $customers['id_customer'] ?>'>Проекты (<?=$customers['customer_name'];?>)</a> > <a href='showobjects.php?id_project=<?= $id_project;?>'>Объекты (<?=$projectname;?>)</a> > Редактирование:</p>
		<div class="reg_sel_object">
		<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
		<?php }?>
			<form action="editobject.php?edit_object=<?=$data;?>" method="POST">
				<p style = "font-size: 8pt">Поля, отмеченные звездочкой, являются обязательными</p>
				<table>
				
				<tr title="Текущее значение: <?=$country['name'];?>">
				<td class="rowt">Страна:*</td>
				<td>
				
					<select name="country_id" id="country_id" class="StyleSelectBox">
						<option value="0">- выберите страну -</option>
						<option value="3159" selected>Россия</option>
						<!--<option value="9908">Украина</option>
						<option value="248">Беларусь</option> -->
					</select>
				
				</td>
				</tr>
				<tr title="Текущее значение: <?=$region['name'];?>">
					<td class="rowt">Регион:*</td>
					<td>
						<select name="region_id" id="region_id"  class="StyleSelectBox">
							<option value="0">- выберите регион -</option>
							<option value="<?=$region_id?>" selected><?=$region['name']?></option>
						</select>
					</td>
				</tr>
				<tr title="Текущее значение: <?=$city['name'];?>">
					<td class="rowt">Населенный пункт:*</td>
					<td>
						<select name="city_id" id="city_id" class="StyleSelectBox">
							<option value="0">- выберите город -</option>
							<option value="<?=$city_id?>" selected><?=$city['name']?></option>
						</select>
					</td>
				</tr>
				<tr title="Номер магазина, офис" >
					<td class="rowt"><label for="shop_number">Объект:*</label></td>
					<td><input class="StyleSelectBox" id="shop_number" maxlength="20" name="shop_number" type="text" value="<?= @$shop_number;?>"/></td>
				</tr>
				<tr title="Название улицы, номер дома, номе офиса в здании" >
					<td class="rowt"><label for="address">Адрес:*</label></td>
					<td><input class="StyleSelectBox" id="address" name="address" maxlength="200" type="text" value="<?= @$address;?>"/></td>
				</tr>
				<tr>
					<td class="rowt"><label for="abon_plata">Абонентская плата, руб.:</label></td>
					<td><input class="StyleSelectBox" id="abon_plata" name="abon_plata" type="number" min="0" value="<?=$abon_plata;?>"/></td>
				</tr>
				<tr class="status">
					<td class="rowt">Статус объекта: *</td>
					<td>
						<select name="status" class="StyleSelectBox">
						<option disabled>Выберите значение:</option>
						<?php for($i = 0; $i < 2; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == $status) ? 'selected' : ''?>><?= $statusedit[$i] ?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<!--------------------------------------------------------------------------------------------->		
				<tr>
					<td colspan="2" align="center"><b>Выбор подрядчика с абонентским обслуживанием</td>
				</tr>
				<td  class="rowt">Город:</td>
				<td>
					<!--<select name="city_id_contr" id="city_id_contr" class="StyleSelectBox" >
						<option value="0">- выберите город -</option>
						<?php foreach($contractors as $i => $contractor)  {
							$citys= Get_Geo ($link, $contractor['city_id'], "city", "city_id" );?>
							<option  value="<?= $contractor['city_id'];?>" <?= ($contractor['city_id'] == $contractor_select['city_id']) ? 'selected' : ''?>><?= $citys['name']; ?></option>
						<?php } ?>
					</select> -->
					<select name="city_id_contr" id="city_id_contr" class="StyleSelectBox">
						<option value="0">- Выберите город -</option>
						<?php foreach($show_city_names as $i => $show_city_name)  { 
						//$citys= Get_Geo ($link, $contractor['city_id'], "city", "city_id" );
						?>
							<option  value="<?= $show_city_name['city_id']; ?>"<?= ($show_city_name['city_id'] == $contractor_select['city_id']) ? 'selected' : ''?>><?= $show_city_name['name']; ?></option>
						<?php } ?>
					</select>					
				</td>
			</tr>
			<tr>
				<td  class="rowt">Контрагент:</td>
				<td>
					<select name='id_contractor' id="id_contractor" class="StyleSelectBox">
						<option value='0'>- выберите контрагента -</option>
						<?php if($objects['id_contractor']){ ?>
						<option value='<?=$objects['id_contractor'];?>' selected><?=$contractor_select['org_name'];?></option>
						<?php }?>
					</select>
				</td>				
				<tr>
					<td class="rowt"><label for="abon_plata_contr">Абонентская плата, руб.:</label></td>
					<td><input class="StyleSelectBox" id="abon_plata_contr" name="abon_plata_contr" type="number" min="0" value="<?=$abon_plata_contr;?>"/></td>
				</tr>				
				</table>
				<div>
					
					<input class="button" value="Сохранить" type="submit" name="edit_object"/>
					<input class="button" value="К списку объектов" type="button" onclick="location.href='showobjects.php?id_project=<?=$id_project;?>'"/>
					<?php if(isset($_SESSION['userlevel']) AND  $_SESSION['userlevel']<3) { ?>
				<a href="#delete_object" class="button-delete">Удалить объект</a>
					<div id="delete_object" class="modalDialog">
						<div>
							<!-- <a href="#close"  title="Закрыть" class="close">X</a> -->
						<h2>Удаление объекта</h2>
						<p>Вы уверены, что хотите удалить этот объект?</p>
						<p>Это может привести к потери данных в других разделах системы!</p>
						<input class="button-delete" value="Да" name="delete_object" type="submit"/>
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
