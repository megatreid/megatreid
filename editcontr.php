<?php
require '/connection/config.php';
//ini_set('display_errors', '1');
if(isset($_SESSION['userlevel']) AND ($_SESSION['userlevel']==1) OR $_SESSION['userlevel']==2)
{
require_once 'blocks/header.php'; 
require '/func/arrays.php';

if(isset($_GET['edit']))
{
	$data = $_GET['edit'];
	$_SESSION['id_edit'] = $data;
	$contractors = Edit_Contr($link, $data);
	$country_id_edit = $contractors['country_id'];
	$region_id_edit = $contractors['region_id'];
	$city_id_edit = $contractors['city_id'];
	$org_name_edit = $contractors['org_name'];
	$status_edit = $contractors['status'];
	$dogovor_edit = $contractors['dogovor'];
	$card_number_edit = $contractors['card_number'];
	$anketa_edit = $contractors['anketa'];
	$contact_name_edit = $contractors['contact_name'];
	$passport_edit = $contractors['passport'];
	$methodpayment_edit = $contractors['method_payment'];
	$ownership_edit = $contractors['ownership'];
	$system_no_edit = $contractors['system_no'];
	$mobile_edit = $contractors['mobile'];
	$phone_edit = $contractors['phone'];
	$email_edit = $contractors['email'];
	$web_edit = $contractors['web'];
	$comment_edit = $contractors['comment'];
}
$data_update = $_POST;
$err=FALSE;
$country_id = trim(filter_input(INPUT_POST, 'country_id'));
$region_id = trim(filter_input(INPUT_POST, 'region_id'));
$city_id = trim(filter_input(INPUT_POST, 'city_id'));
$org_name = trim(filter_input(INPUT_POST, 'org_name'));
$status = trim(filter_input(INPUT_POST, 'status'));
$dogovor = trim(filter_input(INPUT_POST, 'dogovor'));
$method_payment = trim(filter_input(INPUT_POST, 'method_payment'));
$card_number = trim(filter_input(INPUT_POST, 'card_number'));
$anketa = trim(filter_input(INPUT_POST, 'anketa'));
$ownership = trim(filter_input(INPUT_POST, 'ownership'));
$system_no = trim(filter_input(INPUT_POST, 'system_no'));
$contact_name = trim(filter_input(INPUT_POST, 'contact_name'));
$passport = trim(filter_input(INPUT_POST, 'passport'));
$mobile = trim(filter_input(INPUT_POST, 'mobile'));
$phone = trim(filter_input(INPUT_POST, 'phone'));
$email = trim(filter_input(INPUT_POST, 'email'));
$web = trim(filter_input(INPUT_POST, 'web'));
$comment = trim(filter_input(INPUT_POST, 'comment'));

if( isset($data_update['do_editcontr']))
	{
		$errors=array();//массив сообшений ошибок
		echo $country_id;
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
		if(empty($org_name))
		{
			$errors[] = 'Введите НАЗВАНИЕ или ИМЯ подрядчика!';
		}
		if(mb_strlen($org_name)>50 or mb_strlen($org_name)<3)
		{
			$errors[] = 'НАЗВАНИЕ или ИМЯ подрядчика должно содержать не менее 3 и не более 50 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($anketa))
		{
			$errors[] = 'Укажите наличие анкеты!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($ownership))
		{
			$errors[] = 'Выберите форму собственности подрядчика';
		}		
/* ------------------------------------------------------------------------------------------------- */
		if(empty($system_no))
		{
			$errors[] = 'Выберите систему налогообложения подрядчика';
		}		
/* ------------------------------------------------------------------------------------------------- */
		if(empty($contact_name))
		{
			$errors[] = 'Укажите контактное лицо!';
		}
		if(mb_strlen($contact_name)>100 or mb_strlen($contact_name)<3)
		{
			$errors[] = 'Поле \"КОНТАКТНОЕ ЛИЦО\" должно содержать не менее 3 и не более 100 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(!empty($passport) AND (mb_strlen($passport)>250 or mb_strlen($passport)<3))
		{
			$errors[] = 'Поле \"Паспортные данные\" должно содержать не менее 3 и не более 250 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($mobile))
		{
			$errors[] = 'Укажите мобильный номер';
		}
		if(mb_strlen($mobile)>100 or mb_strlen($mobile)<3)
		{
			$errors[] = 'Поле \"Мобильный телефон\" должно содержать не менее 3 и не более 100 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */
 
	if(empty($errors))
	{  
?>
<br><br>
<?php
		$id_contractor = $_SESSION['id_edit'];
		$update_contr = Update_Contr ($link, $id_contractor, $country_id, $region_id, $city_id, $org_name, $status, $dogovor, $method_payment, $card_number, $anketa, $ownership, $system_no, $contact_name, $passport, $mobile, $phone, $email, $web, $comment);

		if($update_contr)
		{
			unset($_SESSION['id_edit']);?>				
			<script>
				setTimeout(function() {window.location.href = '/showcontractor.php';}, 0);
			</script>
			<?php	
		}
	}
	else
		{
			$err=TRUE;
		}
	}
	if(isset($_POST['delete_contr']))
	{
		$delete_contr = delete_contr($link, $_SESSION['id_edit']);
		if($delete_contr)
		{
			unset($_SESSION['id_edit']);
			?>
				<script>
					setTimeout(function() {window.location.href = '/showcontractor.php';}, 0);
				</script>
			<?php		
		}
	}	
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Изменение данных по подрядчику</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/selects.js'></script>
</head>
<body>
	<div class="showany">
	
	<p class="breadcrumbs"><a href='/showcontractor.php'>Подрядчики</a> > Редактирование:</p>
	<div class="reg_up_table">
				<?php if($err == TRUE){?>
				<div class="error-message"><?=array_shift($errors)?></div>
				<?php }?>
					<form action = "/editcontr.php?edit=<?= $_SESSION['id_edit'];?>" method = "POST">
					<p style = "font-size: 8pt">Поля, отмеченные звездочкой, являются обязательными</p>
						<table>
							<tr>
								<td class="rowt">Страна:*</td>
								<td>
								<?php $geo1 = Get_Geo($link, $country_id_edit, $geo_table[0],  $geo_row[0]);?>
								<select name="country_id" id="country_id" class="StyleSelectBox" title="Текущее значение: <?=$geo1['name']?>">
									<option value="0">- выберите страну -</option>
									<option value="3159" selected>Россия</option>
									<!--<option value="9908">Украина</option>
									<option value="248">Беларусь</option> -->
								</select>
								</td>
							</tr>
							<tr>
								<td class="rowt">Регион:*</td>
								<td>
									<?php $geo2 = Get_Geo($link, $region_id_edit, $geo_table[1],  $geo_row[1]);?>
									<select name="region_id" id="region_id" class="StyleSelectBox" title="Текущее значение: <?=$geo2['name']?>">
										<option value="0">- выберите регион -</option>
										<option value="<?=$region_id_edit?>" selected><?=$geo2['name']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="rowt">Населенный пункт:*</td>
								<td>
									<?php $geo3 = Get_Geo($link, $city_id_edit, $geo_table[2],  $geo_row[2]);?>
									<select name="city_id" id="city_id" class="StyleSelectBox" title="Текущее значение: <?=$geo3['name']?>">
										<option value="0">- выберите город -</option>
										<option value="<?=$city_id_edit?>" selected><?=$geo3['name']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="rowt">Организация / исполнитель:*</td>
								<td><input class="StyleSelectBox" name="org_name" type="text" value='<?= $org_name_edit ?>'/></td>
							</tr>
							<tr class="status">
								<td class="rowt">Статус подрядчика:*</td>
								<td>
									<select name="status" class="StyleSelectBox">
									<option disabled>Выберите значение:</option>
									<?php for($i = 0; $i < 2; $i++) { ?>
										<option  value="<?= $i ?>" <?= ($i == $status_edit) ? 'selected' : ''?>><?= $statusedit[$i] ?></option>
									<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="rowt">Номер договора:</td>
								<td><input class="StyleSelectBox" name="dogovor" type="text" value="<?=$dogovor_edit?>"/></td>
							</tr>
							<tr>
								<td class="rowt">Форма расчета:*</td>
								<td>								
									<select name="method_payment" class="StyleSelectBox">
										<option disabled>Выберите значение:</option>
										<?php for($i = 1; $i < 3; $i++) { ?>
										<option  value="<?= $i ?>" <?= ($i == $methodpayment_edit) ? 'selected' : ''?>><?= $methodpaymentedit[$i] ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>							
							<tr>
								<td class="rowt">Номер карты для оплаты "наличкой":</td>
								<td><input class="StyleSelectBox" name="card_number" type="text" value="<?=$card_number_edit?>"/></td>
							</tr>						
							<tr>
								<td class="rowt">Наличие анкеты:</td>
								<td>
									<select name="anketa" class="StyleSelectBox">
									<option disabled>Выберите значение:</option>
									<?php for($i = 1; $i < 3; $i++) { ?>
										<option  value="<?= $anketaedit[$i] ?>" <?= ($anketaedit[$i] == $anketa_edit) ? 'selected' : ''?>><?= $anketaedit[$i] ?></option>
									<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="rowt">Форма собственности:*</td>
								<td>
									<select name="ownership" class="StyleSelectBox">
									<option disabled>Выберите значение:</option>
									<?php for($i = 1; $i < 4; $i++) { ?>
										<option  value="<?= $ownershipedit[$i] ?>" <?= ($ownershipedit[$i] == $ownership_edit) ? 'selected' : ''?>><?= $ownershipedit[$i] ?></option>
									<?php } ?>

									</select>
								</td>
							</tr>
							<tr>
								<td class="rowt">Система налогообложения:*</td>
								<td>
									<select name="system_no" class="StyleSelectBox">
									<option disabled>Выберите значение:</option>
									<?php for($i = 1; $i < 4; $i++) { ?>
										<option  value="<?= $systemnoedit[$i] ?>" <?= ($systemnoedit[$i] == $system_no_edit) ? 'selected' : ''?>><?= $systemnoedit[$i] ?></option>
									<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="rowt">Контактное лицо:*</td><td><textarea name="contact_name" cols="32" rows="5" placeholder = "При вводе нескольких имен (ФИО) используйте разделитель ';'" title="При вводе нескольких имен (ФИО) используйте разделитель ';'"><?=$contact_name_edit?></textarea></td>
							</tr>
							<tr>
								<td class="rowt">Паспортные данные:</td><td><textarea name="passport" cols="32" rows="5"><?=$passport_edit?></textarea></td>
							</tr>
							<tr>
								<td class="rowt">Мобильный телефон:*</td><td><textarea name="mobile" cols="32" rows="5" placeholder = "При вводе нескольких мобильных номеров используйте разделитель ';'" title="При вводе нескольких мобильных номеров используйте разделитель ';'"><?=$mobile_edit?></textarea></td>
							</tr>
							<tr>
								<td class="rowt">Рабочий телефон:</td><td><textarea name="phone" cols="32" rows="5" placeholder = "При вводе нескольких номеров используйте разделитель ';'" title="При вводе нескольких номеров используйте разделитель ';'"><?=$phone_edit?></textarea></td>
							</tr>						
							<tr>
								<td class="rowt">Email:</td><td><input class="StyleSelectBox" name="email"  title = "При вводе нескольких адресов используйте разделитель ';'" type="text" value="<?php echo @$email_edit;?>"/></td>
							</tr>
							<tr>
								<td class="rowt">WEB-сайт:</td><td><input class="StyleSelectBox" name="web" type="text" value='<?php echo @$web_edit;?>'/></td>
							</tr>
							<tr>
								<td class="rowt">Примечание:</td><td><textarea name="comment" cols="32" rows="5"><?=$comment_edit?></textarea></td>
							</tr>
						</table>
						<input class="button" value="Сохранить" type="submit" name="do_editcontr"/>
						<input class="button" value="К списку подрядчиков" type="button" onclick="location.href='showcontractor.php'"/>
						
						<input class="button-delete" value="Удалить" type="submit" onclick='return confirm("Вы уверены, что хотите удалить эти данные?")' name="delete_contr"/>
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