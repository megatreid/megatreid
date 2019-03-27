<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<3)
{
require_once '/blocks/header.php';
	
//require_once 'Classes/PHPExcel.php';

$country_id = "3159";
$regions = Show_Region ($link, $country_id);
$data = $_POST;
$err=FALSE;

$region_id = trim(filter_input(INPUT_POST, 'region_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$city_name = trim(filter_input(INPUT_POST, 'city_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
if( isset($data['new_city']))
	{
		$errors=array();//массив сообшений ошибок
		
		if(empty($region_id))
		{
			$errors[] = 'Не выбран регион!';
		}
		if(empty($city_name))
		{
			$errors[] = 'Не указано название населенного пункта!';
		}		
		if(empty($errors))
		{		
		
		
			$city_add = Add_City ($link, $country_id, $region_id, $city_name);
			if($city_add)
			{
				$region = Get_Geo ($link, $region_id, "region", "region_id");
				$msg = 'Вы добавили в "'.$region['name'].'" населенный пункт:<br> "'.$city_name.'"';
			}
		}
		else
			{
				$err=TRUE;
			}
	}
?>
	<!DOCTYPE html>
	<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>Географические объекты</title>
	</head>

	<body>
<div class="showany">
<br>
	<div class="reg_sel_object">
	<p class="breadcrumbs"> Добавление населенного пункта:</p>
	<?php if($err==TRUE){?>
		<h1><div class="error-message"><?=array_shift($errors)?></div></h1>
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
								<input class="StyleSelectBox" name="city_name" type="text"/>
								
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
					</form>
					</div>
	<!-- <div id="footer">&copy; ООО "МегаТрейд"</div> -->
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