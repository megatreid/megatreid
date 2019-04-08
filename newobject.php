<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3)
{
		require_once '/blocks/header.php';
		require '/func/arrays.php';
		$contractors = Show_Contr_for_select ($link);
		if(isset($_GET['id_project']))
	{
		$data = $_GET['id_project'];
		$data_post = $_POST;
		$_SESSION['id_project'] = $data;
		$projects = Edit_Project($link, $data);
		$id_customer = $projects['id_customer'];
		$project_name = $projects['projectname'];
		$customers = Edit_Customer($link, $id_customer);
		/*********************************/
		$country_id = trim(filter_input(INPUT_POST, 'country_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$region_id = trim(filter_input(INPUT_POST, 'region_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$city_id = trim(filter_input(INPUT_POST, 'city_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$shop_number = trim(filter_input(INPUT_POST, 'shop_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$address = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$abon_plata = trim(filter_input(INPUT_POST, 'abon_plata', FILTER_SANITIZE_FULL_SPECIAL_CHARS));		
		$id_contractor = trim(filter_input(INPUT_POST, 'id_contractor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));	
		$abon_plata_contr = trim(filter_input(INPUT_POST, 'abon_plata_contr', FILTER_SANITIZE_FULL_SPECIAL_CHARS));	
		
		$err=FALSE;	

	if( isset($data_post['new_object']))
		{
			$errors=array();//массив сообшений ошибок
			if(empty($country_id) OR $country_id == 0)
			{
				$errors[] = 'Выберите страну!';
			}
	/* ------------------------------------------------------------------------------------------------- */	
			if(empty($region_id) OR $region_id == 0)
			{
				$errors[] = 'Выберите область!';
			}
	/* ------------------------------------------------------------------------------------------------- */
			if(empty($city_id) OR $city_id == 0)
			{
				$errors[] = 'Выберите город!';
			}
	/* ------------------------------------------------------------------------------------------------- */
			if(empty($shop_number))
			{
				$errors[] = 'Укажите название объекта!';
			}
			if( mb_strlen($shop_number,'UTF-8')>20 or mb_strlen($shop_number,'UTF-8')<2)
			{
				$errors[] = 'Название объекта должно содержать не менее 2 и не более 20 символов!';
			}
	/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */	
			if(empty($address))
			{
				$errors[] = 'Укажите адрес объекта!';
			}
			if( mb_strlen($address,'UTF-8')>200 or mb_strlen($address,'UTF-8')<2)
			{
				$errors[] = 'Адрес объекта должен содержать не менее 2 и не более 200 символов!';
			}	
	/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */		

			
	/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */

			if(empty($errors)){  
				
				$result = Add_Object ($link, $data, $id_customer, $country_id, $region_id, $city_id, $shop_number, $address, $status, $abon_plata, $id_contractor, $abon_plata_contr); 
				?>		
				<script>
					setTimeout(function() {window.location.href = '/showobjects.php?id_project=<?=$data?>';}, 0);
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
		<title>Добавление нового объекта</title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
		<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
		<link rel="stylesheet" href="css/index.css">
		<script type="text/javascript" src='js/jquery.js'></script>
		<script type="text/javascript" src='js/selects.js'></script>
		<script type="text/javascript" src='js/contractor_select.js'></script>		
	</head>
	<body>
		<div class="showany">
			<p class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > <a href='showprojects.php?id_customer=<?= $customers['id_customer'] ?>'>Проекты (<?=$customers['customer_name'];?>)</a> > <a href='showobjects.php?id_project=<?=$data;?>'>Объекты (<?=$project_name?>) </a> > Новый объект:</p>
			<div class="reg_sel_object">
			<?php if($err==TRUE){?>
				<div class="error-message"><?=array_shift($errors)?></div>
			<?php }?>
				<form action="newobject.php?id_project=<?=$_SESSION['id_project']?>" method="POST">
				<p style = "font-size: 8pt">Поля, отмеченные звездочкой, являются обязательными</p>
				<table>
				<tr>
				<td class="rowt">Страна:*</td>
				<td>
				<select name="country_id" id="country_id" class="StyleSelectBox">
					<option value="0">- выберите страну -</option>
					<option value="3159">Россия</option>
					<!--<option value="9908">Украина</option>
					<option value="248">Беларусь</option> -->
				</select>
				</td>
				</tr>
				<tr>
					<td class="rowt">Регион:*</td>
					<td>
						<select name="region_id" id="region_id" disabled="disabled" class="StyleSelectBox">
							<option value="0">- выберите регион -</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="rowt">Населенный пункт:*</td>
					<td>
						<select name="city_id" id="city_id" disabled="disabled" class="StyleSelectBox">
							<option value="0">- выберите город -</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="rowt"><label for="shop_number">Объект:*</label></td>
					<td><input class="StyleSelectBox" id="shop_number" name="shop_number" maxlength="20" type="text" value="<?php echo @$shop_number;?>"/></td>
				</tr>
				<tr>
					<td class="rowt"><label for="address">Адрес:*</label></td>
					<td><input class="StyleSelectBox" id="address" name="address" maxlength="200" size="40" type="text" value="<?php echo @$address;?>"/></td>
				</tr>
				<tr class="status">
					<td class="rowt">Статус объекта:*</td>
					<td>
						<select name="status" class="StyleSelectBox" >

							<option value="0">Неактивный</option>
							<option value="1" selected>Активный</option>

						</select>
					</td>
				</tr>	
				<tr>
					<td class="rowt"><label for="abon_plata">Абонентская плата, руб.:</label></td>
					<td><input class="StyleSelectBox" id="abon_plata" name="abon_plata" type="number" min="0" size="11" value="0"/></td>
				</tr>
		<!--------------------------------------------------------------------------------------------->		
				<tr>
					<td colspan="2" align="center"><b>Выбор подрядчика с абонентским обслуживанием</td>
				</tr>
				<td  class="rowt">Город:</td>
				<td>
					<select name="city_id_contr" id="city_id_contr" class="StyleSelectBox" >
						<option value="0">- выберите город -</option>
						<?php foreach($contractors as $i => $contractor)  { 
						$citys= Get_Geo ($link, $contractor['city_id'], "city", "city_id" );
						
						?>
							<option  value="<?= $contractor['city_id']; ?>"><?= $citys['name']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td  class="rowt">Контрагент:</td>
				<td>
					<select name="id_contractor" id="id_contractor" disabled="disabled" class="StyleSelectBox">
						<option value="0">- выберите подрядчика -</option>
					</select>
				</td>				
				<tr>
					<td class="rowt"><label for="abon_plata_contr">Абонентская плата, руб.:</label></td>
					<td><input class="StyleSelectBox" id="abon_plata_contr" name="abon_plata_contr" type="number" min="0" size="11" value="<?php echo @$abon_plata_contr;?>"/></td>
				</tr>				
				</table>
				<div>
					<p><button name="new_object" class="button-new">Добавить</button></p>
					
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