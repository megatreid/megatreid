<?php
require '/connection/config.php';
//ini_set('display_errors', '1');
if(isset($_SESSION['userlevel']) AND  $_SESSION['userlevel']<=3)
{
require_once 'blocks/header.php'; 
require '/func/arrays.php';

if(isset($_GET['edit']))
{
	//$data = $_GET['edit'];
	$data = trim(filter_input(INPUT_GET, 'edit', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
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
$country_id = trim(filter_input(INPUT_POST, 'country_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$region_id = trim(filter_input(INPUT_POST, 'region_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$city_id = trim(filter_input(INPUT_POST, 'city_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$org_name = trim(filter_input(INPUT_POST, 'org_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$dogovor = trim(filter_input(INPUT_POST, 'dogovor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$method_payment = trim(filter_input(INPUT_POST, 'method_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$card_number = trim(filter_input(INPUT_POST, 'card_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$anketa = trim(filter_input(INPUT_POST, 'anketa', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$ownership = trim(filter_input(INPUT_POST, 'ownership', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$system_no = trim(filter_input(INPUT_POST, 'system_no', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contact_name = trim(filter_input(INPUT_POST, 'contact_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$passport = trim(filter_input(INPUT_POST, 'passport', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$mobile = trim(filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$web = trim(filter_input(INPUT_POST, 'web', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$comment = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

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
		if(mb_strlen($org_name,'UTF-8')>80 or mb_strlen($org_name,'UTF-8')<3)
		{
			$errors[] = 'НАЗВАНИЕ или ИМЯ подрядчика должно содержать не менее 3 и не более 80 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(mb_strlen($dogovor,'UTF-8')>60)
		{
			$errors[] = 'Номер договора должен содержать не более 60 символов!';
		}	
/* ------------------------------------------------------------------------------------------------- */		
		if(empty($method_payment))
		{
			$errors[] = 'Укажите способ платежа!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(mb_strlen($card_number,'UTF-8')>18)
		{
			$errors[] = 'Номер карты должен содержать не более 18 символов!';
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
		if(mb_strlen($contact_name,'UTF-8')>250 or mb_strlen($contact_name,'UTF-8')<3)
		{
			$errors[] = 'Поле "КОНТАКТНОЕ ЛИЦО" должно содержать не менее 3 и не более 150 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(mb_strlen($passport,'UTF-8')>400)
		{
			$errors[] = 'Поле "Паспортные данные" должно содержать не более 400 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($mobile))
		{
			$errors[] = 'Укажите номер мобильного телефона';
		}
		if(mb_strlen($mobile,'UTF-8')>200)
		{
			$errors[] = 'Поле "Мобильный телефон" должно содержать не более 200 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */
 		if(mb_strlen($phone,'UTF-8')>200)
		{
			$errors[] = 'Поле "Рабочий телефон" должно содержать не более 200 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */

 		if(mb_strlen($email,'UTF-8')>100)
		{
			$errors[] = 'Поле "E-Mail" должно содержать не более 100 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */

 		if(mb_strlen($web,'UTF-8')>100)
		{
			$errors[] = 'Поле "WEB" должно содержать не более 100 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */
 		if(mb_strlen($comment,'UTF-8')>400)
		{
			$errors[] = 'Поле "Примечание" должно содержать не более 400 символов!';
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
	<div class="breadcrumbs"><a href='/showcontractor.php'>Подрядчики</a> > Редактирование:</div>
	<div class="contr_registr">
	

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
					<td><input class="StyleSelectBox" name="org_name" required maxlength="80" type="text" value='<?= $org_name_edit ?>'/></td>
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
					<td><input class="StyleSelectBox" name="dogovor" maxlength="60" type="text" value="<?=$dogovor_edit?>"/></td>
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
					<td><input class="StyleSelectBox" name="card_number" type="text" maxlength="18" pattern="[0-9]{16,18}" title="Это поле должно содержать не более 18 цифр" value="<?=$card_number_edit?>"/></td>
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
					<td class="rowt">Контактное лицо:*</td><td><textarea class="reg_textarea" name="contact_name"  required maxlength="250" placeholder = "При вводе нескольких имен (ФИО) используйте разделитель ';'" title="При вводе нескольких имен (ФИО) используйте разделитель ';'"><?=$contact_name_edit?></textarea></td>
				</tr>
				<tr>
					<td class="rowt">Паспортные данные:</td><td><textarea class="reg_textarea" name="passport" maxlength="400" ><?=$passport_edit?></textarea></td>
				</tr>
				<tr>
					<td class="rowt">Мобильный телефон:*</td>
					<td>
					<textarea class="reg_textarea" name="mobile"  maxlength="200" required placeholder = "При вводе нескольких мобильных номеров используйте разделитель ';'" title="При вводе нескольких мобильных номеров используйте разделитель ';'"><?=$mobile_edit?></textarea>
					</td>
				</tr>
				<tr>
					<td class="rowt">Рабочий телефон:</td>
					<td>
					<textarea class="reg_textarea" name="phone" maxlength="200" placeholder = "При вводе нескольких номеров используйте разделитель ';'" title="При вводе нескольких номеров используйте разделитель ';'"><?=$phone_edit?></textarea>
					</td>
				</tr>						
				<tr>
					<td class="rowt">Email:</td>
					<td>
					<input class="StyleSelectBox" name="email" maxlength="100" title = "При вводе нескольких адресов используйте разделитель ';'" type="text" value="<?php echo @$email_edit;?>"/>
					</td>
				</tr>
				<tr>
					<td class="rowt">WEB-сайт:</td>
					<td>
					<input class="StyleSelectBox" name="web" maxlength="100" type="text" value='<?php echo @$web_edit;?>'/>
					</td>
				</tr>
				<tr>
					<td class="rowt">Примечание:</td>
					<td>
					<textarea class="reg_textarea" name="comment" maxlength="400" title = "Максимальное количество символов 400" ><?=$comment_edit?></textarea>
					</td>
				</tr>
			</table>
			<input class="button" value="Сохранить" type="submit" name="do_editcontr"/>
			<input class="button" value="К списку подрядчиков" type="button" onclick="location.href='showcontractor.php'"/>
			<?php if(isset($_SESSION['userlevel']) AND  $_SESSION['userlevel']<3) { ?>
			<a href="#delete_contr" class="button-delete">Удалить подрядчика</a>
			<div id="delete_contr" class="modalDialog">
			<div>
				<!-- <a href="#close"  title="Закрыть" class="close">X</a> -->
			<h2>Удаление подрядчика</h2>
			<p>Вы уверены, что хотите удалить этого подрядчика?</p>
			<p>Это может привести к потери данных в других разделах системы!</p>
			<input class="button-delete" value="Да" name="delete_contr" type="submit"/>
			<a href="#close"  title="Отменить" class="button">Нет</a>
			<!-- <button class="button-delete" onclick='return confirm("Вы уверены, что хотите удалить эту заявку?")' name="delete_ticket">Удалить заявку</button> -->
			</div>
			</div>
			<?php }?>
		</form>
	</div>
<div id="footer">&copy; ООО "МегаТрейд"</div>			
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
