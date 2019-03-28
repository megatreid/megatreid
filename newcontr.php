<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND ($_SESSION['userlevel']<3))
{
require_once '/blocks/header.php'; 
require '/func/arrays.php';

$data = $_POST;

$err=false;
$card_number = "";
$contact_name = "";
$mobile = "";
$phone = "";
$email = "";
$web = "";
$comment = "";

$country_id = trim(filter_input(INPUT_POST, 'country_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$region_id = trim(filter_input(INPUT_POST, 'region_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$city_id = trim(filter_input(INPUT_POST, 'city_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$org_name = trim(filter_input(INPUT_POST, 'org_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$dogovor = trim(filter_input(INPUT_POST, 'dogovor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$method_payment = trim(filter_input(INPUT_POST, 'method_payment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$card_number = trim(filter_input(INPUT_POST, 'card_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$anketa = trim(filter_input(INPUT_POST, 'anketa', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$system_no = trim(filter_input(INPUT_POST, 'system_no', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$contact_name = trim(filter_input(INPUT_POST, 'contact_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$passport = trim(filter_input(INPUT_POST, 'passport', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$mobile = trim(filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$web = trim(filter_input(INPUT_POST, 'web', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$comment = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$region_info = Get_Geo($link, $region_id, 'region', 'region_id');
$city_info = Get_Geo($link, $city_id, 'city', 'city_id');
if( isset($data['do_newcontr']))
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
		if(empty($org_name))
		{
			$errors[] = 'Введите НАЗВАНИЕ или ИМЯ подрядчика!';
		}
		if(mb_strlen($org_name)>80 or mb_strlen($org_name)<3)
		{
			$errors[] = 'НАЗВАНИЕ или ИМЯ подрядчика должно содержать не менее 3 и не более 80 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(mb_strlen($dogovor)>60)
		{
			$errors[] = 'Номер договора должен содержать не более 60 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */		
		//if(isset($method_payment) && $method_payment=="")
		if(empty($method_payment))
		{
			$errors[] = 'Укажите способ платежа!';
		}
		/* ------------------------------------------------------------------------------------------------- */
		if(mb_strlen($card_number)>18)
		{
			$errors[] = 'Номер карты должен содержать не более 18 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */			
		if(empty($anketa))
		{
			$errors[] = 'Укажите наличие анкеты!';
		}

/* ------------------------------------------------------------------------------------------------- */
		if(empty($status))
		{
			$errors[] = 'Выберите форму собственности подрядчика';
		}		
/* ------------------------------------------------------------------------------------------------- */
		if(empty($system_no))
		{
			$errors[] = 'Выберите систему налогообложения контрагента';
		}		
/* ------------------------------------------------------------------------------------------------- */
		if(empty($contact_name))
		{
			$errors[] = 'Укажите контактное лицо';
		}
		if(mb_strlen($contact_name)>250 or mb_strlen($contact_name)<3)
		{
			$errors[] = 'Поле "КОНТАКТНОЕ ЛИЦО" должно содержать не менее 3 и не более 250 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(mb_strlen($passport)>400)
		{
			$errors[] = 'Поле "Паспортные данные" должно содержать не более 400 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($mobile))
		{
			$errors[] = 'Укажите номер мобильного телефона';
		}
		if(mb_strlen($mobile)>200)
		{
			$errors[] = 'Поле "Мобильный телефон" должно содержать не более 200 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */
 		if(mb_strlen($phone)>200)
		{
			$errors[] = 'Поле "Рабочий телефон" должно содержать не более 200 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */

 		if(mb_strlen($email)>100)
		{
			$errors[] = 'Поле "E-Mail" должно содержать не более 100 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */

 		if(mb_strlen($web)>100)
		{
			$errors[] = 'Поле "WEB" должно содержать не более 100 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */
 		if(mb_strlen($comment)>400)
		{
			$errors[] = 'Поле "Примечание" должно содержать не более 400 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */
	if(empty($errors)){  

		$result = Add_Contr ($link,  $country_id, $region_id, $city_id, $org_name, $status, $dogovor, $method_payment, $card_number, $anketa, $ownership, $system_no, $contact_name, $passport, $mobile, $phone, $email, $web, $comment);
			if($result){
		?>		
		<script>
			setTimeout(function() {window.location.href = '/showcontractor.php';}, 0);
		</script>
			<?php	}
    }
	else
		{
			$err=true;
			//echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
		}
	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Добавление нового подрядчика</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/selects.js'></script>
</head>
<body>
	<div class="showany">
	<p class="breadcrumbs"><a href='/showcontractor.php'>Подрядчики</a> > Новый подрядчик:</p>
	<div class="reg_sel_object">
	<?php if($err==true){?>
	<div class="error-message"><?=array_shift($errors)?></div>
	<?php }?>
				
		<form action="/newcontr.php" method="POST">
		<p style = "font-size: 8pt">Поля, отмеченные звездочкой, являются обязательными</p>
			<table>
			<tr>
				<td class="rowt">Страна:*</td>
				<td>
				<select name="country_id" id="country_id" class="StyleSelectBox" required="">
					<option value="0">- выберите страну -</option>
					<option value="3159" <?=($country_id == 3159 ? 'selected' :'')?>>Россия</option>
					
					<!--<option value="9908">Украина</option>
					<option value="248">Беларусь</option> -->
				</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Регион:*</td>
				<td>
					<select name="region_id" id="region_id"  class="StyleSelectBox" >
						<option value="0" <?=(!isset($region_id) ? 'selected' :'')?>>- выберите регион -</option>
						<option value="<?= @$region_id;?>" <?=(isset($data['region_id']) ? 'selected' :'')?>><?=$region_info['name'];?></option>
						
					</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Населенный пункт:*</td>
				<td>
					<select name="city_id" id="city_id" class="StyleSelectBox" >
						<option value="<?= @$city_id;?>" <?=(isset($data['city_id']) ? 'selected' :'')?>><?=$city_info['name'];?></option>
						<option value="0" <?=(!isset($data['city_id']) ? 'selected' :'')?>>- выберите город -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Организация / исполнитель:*</td>
				<td>
				<input class="StyleSelectBox" maxlength="80" name="org_name" required type="text" value="<?= @$org_name;?>"/>
			</td>
			</tr>
			<tr class="status">
				<td class="rowt">Статус подрядчика:*</td>
				<td>
					<select name="status" class="StyleSelectBox" >

						<option value="0">Неактивный</option>
						<option value="1" selected>Активный</option>

					</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Номер договора:</td>
				<td><input class="StyleSelectBox" name="dogovor" maxlength="60" type="text" value="<?php echo @$dogovor;?>"/></td>
			</tr>
			<tr>
				<td class="rowt">Форма расчета:*</td>
				<td>								
					<select name="method_payment" class="StyleSelectBox" >
						<option disabled selected>Выберите значение:</option>
						<option value="1" <?=($method_payment == 1 ? 'selected' :'')?>>Наличный</option>
						<option value="2" <?=($method_payment == 2 ? 'selected' :'')?>>Безналичный</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Номер карты для оплаты "наличкой":</td>
				<td><input class="StyleSelectBox" name="card_number" maxlength="18" pattern="[0-9]{16,18}" type="number" title="Это поле должно содержать не более 18 цифр" value="<?php echo @$card_number;?>"/></td>
			</tr>
			<tr>
				<td class="rowt">Наличие анкеты:*</td>
				<td>
					<select name="anketa" class="StyleSelectBox" >
						<option disabled selected>Выберите значение:</option>
						<option value="Есть" <?=($anketa == 'Есть' ? 'selected' :'')?>>Есть</option>
						<option value="Нет" <?=($anketa == 'Нет' ? 'selected' :'')?>>Нет</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Форма собственности:*</td>
				<td>
					<select name="status" class="StyleSelectBox" >
						<option disabled selected>Выберите значение:</option>
						<option value="ГПХ" <?=($status == 'ГПХ' ? 'selected' :'')?>>ГПХ</option>
						<option value="ИП" <?=($status == 'ИП' ? 'selected' :'')?>>ИП</option>
						<option value="ООО" <?=($status == 'ООО' ? 'selected' :'')?>>ООО</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Система налогообложения:*</td>
				<td>
					<select name="system_no" class="StyleSelectBox" >
						<option disabled selected>Выберите значение:</option>
						<option value="Без НДС" <?=($system_no == 'Без НДС' ? 'selected' :'')?>>Без НДС</option>
						<option value="ГПХ" <?=($system_no == 'ГПХ' ? 'selected' :'')?>>ГПХ</option>
						<option value="С НДС" <?=($system_no == 'С НДС' ? 'selected' :'')?>>С НДС</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Контактное лицо:*</td>
				<td><textarea class="reg_textarea" name="contact_name" required maxlength="150" placeholder = "При вводе нескольких имен (ФИО) используйте разделитель ';'" title="При вводе нескольких имен (ФИО) используйте разделитель ';'"><?php echo @$contact_name;?></textarea></td>
			</tr>
			<tr>
				<td class="rowt">Паспортные данные:</td>
				<td><textarea class="reg_textarea" name="passport" title="При вводе нескольких имен (ФИО) используйте разделитель ';'"><?=@$passport;?></textarea></td>
			</tr>						
			<tr>
				<td class="rowt">Мобильный телефон:*</td>
				<td><textarea class="reg_textarea" name="mobile" required maxlength="100" placeholder = "При вводе нескольких номеров используйте разделитель ';'" title="При вводе нескольких мобильных номеров используйте разделитель ';'"><?php echo @$mobile;?></textarea></td>
			</tr>
			<tr>
				<td class="rowt">Рабочий телефон:</td>
				<td><textarea class="reg_textarea" name="phone" maxlength="100" placeholder = "При вводе нескольких номеров используйте разделитель ';'" title="При вводе нескольких номеров используйте разделитель ';'"><?php echo @$phone;?></textarea></td>
			</tr>						
			<tr>
				<td class="rowt">Email:</td><td><input class="StyleSelectBox" maxlength="100" name="email"  title = "При вводе нескольких адресов используйте разделитель ';'" type="text" value="<?php echo @$email;?>"/></td>
			</tr>
			<tr>
				<td class="rowt">WEB-сайт:</td><td><input class="StyleSelectBox" maxlength="100" name="web" type="text" value="<?php echo @$web;?>"/></td>
			</tr>
			<tr>
				<td class="rowt">Примечание:</td>
				<td>
					<textarea class="reg_textarea" maxlength="400" name="comment"><?=$comment?></textarea>
				</td>
			</tr>

			</table>
			<div>
				<input class="button" type="submit" value="Сохранить" name="do_newcontr"/>
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