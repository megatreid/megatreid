<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND ($_SESSION['userlevel']==1) OR $_SESSION['userlevel']==2){
require_once 'blocks/header.php';
require '/func/arrays.php'; 
$data = $_POST;

$err=0;
$customer_name = trim(filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$jur_address = trim(filter_input(INPUT_POST, 'jur_address', FILTER_SANITIZE_FULL_SPECIAL_CHARS ));
$post_address = trim(filter_input(INPUT_POST, 'post_address', FILTER_SANITIZE_FULL_SPECIAL_CHARS ));
$ogrn = trim(filter_input(INPUT_POST, 'ogrn', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$inn = trim(filter_input(INPUT_POST, 'inn', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$kpp = trim(filter_input(INPUT_POST, 'kpp', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$dogovor_number = trim(filter_input(INPUT_POST, 'dogovor_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$bank_name = trim(filter_input(INPUT_POST, 'bank_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$bank_bik = trim(filter_input(INPUT_POST, 'bank_bik', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$korr_schet = trim(filter_input(INPUT_POST, 'korr_schet', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$rasch_schet = trim(filter_input(INPUT_POST, 'rasch_schet', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$recipient = trim(filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$contact_name = trim(filter_input(INPUT_POST, 'contact_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$web = trim(filter_input(INPUT_POST, 'web', FILTER_SANITIZE_FULL_SPECIAL_CHARS  ));
$comment = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS ));

if( isset($data['do_newcustomer']))
	{	
$errors=array();//массив сообшений ошибок


		
/* ------------------------------------------------------------------------------------------------- */
		if(empty($customer_name))
		{
			$errors[] = 'Введите название организации!';
		}
		if(mb_strlen($customer_name)>100 or mb_strlen($customer_name)<3)
		{
			$errors[] = 'Название организации должно содержать не менее 3 и не более 100 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($jur_address))
		{
			$errors[] = 'Укажите юридический адрес организации!';
		}
		if(mb_strlen($jur_address)>400 or mb_strlen($jur_address)<3)
		{
			$errors[] = 'Юридический адрес организации должен содержать не менее 3 и не более 250 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($post_address))
		{
			$errors[] = 'Укажите почтовый (фактический) адрес организации!';
		}
		if(mb_strlen($post_address)>400 or mb_strlen($post_address)<3)
		{
			$errors[] = 'Фактический адрес организации должен содержать не менее 3 и не более 250 символов!';
		}	
/* ------------------------------------------------------------------------------------------------- */
		if(empty($ogrn))
		{
			$errors[] = 'Введите ОГРН организации!';
		}
		if(mb_strlen($ogrn)>15 or mb_strlen($ogrn)<13)
		{
			$errors[] = 'ОГРН организации должен содержать не менее 13 и не более 15 цифр!';
		}	
/* ------------------------------------------------------------------------------------------------- */
		if(empty($inn))
		{
			$errors[] = 'Введите ИНН организации!';
		}
		if(mb_strlen($inn)>12 or mb_strlen($inn)<10)
		{
			$errors[] = 'ИНН организации должен содержать не менее 10 и не более 12 цифр!';
		}

/* ------------------------------------------------------------------------------------------------- */

		if(mb_strlen($kpp)>9)
		{
			$errors[] = 'КПП организации не должен содержать более 9 цифр!';
		}
/* ------------------------------------------------------------------------------------------------- */

		if(mb_strlen($dogovor_number)>50)
		{
			$errors[] = 'Номер договора должен содержать не менее 3 и не более 50 символов!';
		}	
/* ------------------------------------------------------------------------------------------------- */
		if(empty($bank_name))
		{
			$errors[] = 'Укажите наименование банка получателя!';
		}
		if(mb_strlen($bank_name)>200 or mb_strlen($bank_name)<3)
		{
			$errors[] = 'Наименование банка получателя должно содержать не менее 3 и не более 200 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($bank_bik))
		{
			$errors[] = 'Укажите БИК банка получателя!';
		}
		if(mb_strlen($bank_bik)!=9)
		{
			$errors[] = 'БИК банка получателя должен содержать 9 цифр!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($korr_schet))
		{
			$errors[] = 'Введите корреспондентский счёт!';
		}
		if(mb_strlen($korr_schet)!=20)
		{
			$errors[] = 'Корреспондентский счёт организации должен содержать 20 цифр!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($rasch_schet))
		{
			$errors[] = 'Введите расчетный счет!';
		}
		if(mb_strlen($rasch_schet)!=20)
		{
			$errors[] = 'Расчетный счет организации должен содержать 20 цифр!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($recipient))
		{
			$errors[] = 'Введите имя получателя!';
		}
		if(mb_strlen($recipient)>150 or mb_strlen($recipient)<3)
		{
			$errors[] = 'Поле \"Получатель\" должно содержать не менее 3 и не более 150 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */
		if(empty($contact_name))
		{
			$errors[] = 'Укажите контактное лицо';
		}
		if(mb_strlen($contact_name)>200 or mb_strlen($contact_name)<3)
		{
			$errors[] = 'Поле \"КОНТАКТНОЕ ЛИЦО\" должно содержать не менее 3 и не более 200 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($phone))
		{
			$errors[] = 'Укажите контактный телефон';
		}
		if(mb_strlen($phone)>100 or mb_strlen($phone)<3)
		{
			$errors[] = 'Поле \"Контактный телефон\" должно содержать не менее 3 и не более 100 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */		
		if(empty($email))
		{
			$errors[] = 'Укажите E-Mail';
		}
		if(mb_strlen($email)>100 or mb_strlen($email)<3)
		{
			$errors[] = 'Поле \"E-Mail\" должно содержать не менее 3 и не более 100 символов!';
		}			
/* ------------------------------------------------------------------------------------------------- */
		if(mb_strlen($comment)>400)
		{
			$errors[] = 'Поле \"Примечание\" должно содержать не более 400 символов!';
		}			
/* ------------------------------------------------------------------------------------------------- */ 
	if(empty($errors)){  

		$result = Add_Customer ($link, $customer_name, $jur_address, $post_address, $ogrn, $inn, $kpp, $dogovor_number, $status, $bank_name, $bank_bik, $korr_schet, $rasch_schet, $recipient, $phone, $email, $contact_name, $comment); 
		
		if($result){
		?>
		<script>
			setTimeout(function() {window.location.href = '/showcustomer.php';}, 0);
		</script>
		<?php	}
    }
	else
		{
			$err=1;
		}
	}
	
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Добавление нового заказчика</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/selects.js'></script>
	<script type="text/javascript" src='js/jquery.maskedinput.js'></script>
	<script>
	/*
		$(".phone_mask").mask("+7(999)999-99-99");
		$('.mask-card-number').mask('9999 9999 9999 9999');
		$('.mask-inn-individual').mask('999999999999');
		$('.mask-inn-organization').mask('9999999999');
		$('.mask-ogrn').mask('9999999999999');
		$('.mask-ogrnip').mask('999999999999999');
		$('.mask-kpp').mask('999999999');
		$('.mask-bik').mask('049999999');
		$('.mask-account').mask('99999 999 9 9999 9999999');
*/

	</script>
</head>
<body>
	<div class="showany">
		<div class="contr_registr">	
		<p class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > Новый заказчик:</p>

			<?php if($err==1){?>
			<div class="error-message"><?=array_shift($errors)?></div>
			<?php }?>
			
				<form action="/newcustomer.php" method="POST">
				<p style = "font-size: 8pt">Все поля, отмеченные звездочкой, являются обязательными</p>
					<table>
					<tr>
						<td class="rowt"><label for="customer_name">Название организации:*</label></td>
						<td>
							<input id="customer_name" class="StyleSelectBox" name="customer_name" maxlength="100" type="text" title="Название организации должно содержать не менее 3 и не более 100 символов!" value="<?= @$customer_name;?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="jur_address">Юридический адрес:*</label></td>
						<td>
							<input id="jur_address" class="StyleSelectBox" name="jur_address" maxlength="250" type="text" title="Юридический адрес организации должен содержать не менее 3 и не более 400 символов!" value="<?= $jur_address;?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="post_address">Почтовый (фактический) адрес:*</label></td>
						<td>
							<input id="post_address" class="StyleSelectBox" name="post_address" maxlength="250" title="Фактический адрес организации должен содержать не менее 3 и не более 400 символов!" type="text" value="<?= $post_address;?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="mask-ogrn">ОГРН:*</label></td>
						<td>
							<input id="mask-ogrn" class="StyleSelectBox" name="ogrn" type="text" maxlength="15" pattern="[0-9]{13,15}" title="ОГРН организации должен содержать не менее 13 и не более 15 цифр!" value="<?=$ogrn;?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="mask-inn-organization">ИНН:*</label></td>
						<td>
							<input id="mask-inn-organization" class="StyleSelectBox" name="inn" pattern="[0-9]{10,12}" maxlength="12" type="text" title="'ИНН организации должен содержать не менее 10 и не более 12 цифр!" value="<?=$inn;?>"/>
						</td>
					</tr>

					<tr>
						<td class="rowt"><label for="mask-kpp">КПП:</label></td>
						<td>
							<input id="mask-kpp" class="StyleSelectBox" name="kpp" type="text" pattern="[0-9]{9}"  maxlength="9"  title="КПП организации должен содержать не более 9 цифр!" value="<?=$kpp;?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="dogovor_number">Номер договора:*</label></td>
						<td>
							<input id="dogovor_number" class="StyleSelectBox" name="dogovor_number"  maxlength="50"  title="Номер договора должен содержать не менее 3 и не более 50 символов!" type="text" value="<?= $dogovor_number;?>"/>
						</td>
					</tr>
					<tr class="status">
						<td class="rowt">Статус заказчика:*</td>
						<td>
							<select name="status" class="StyleSelectBox" >

								<option value="0">Неактивный</option>
								<option value="1" selected>Активный</option>

							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							РЕКВИЗИТЫ:
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="bank_name">Банк получателя:*</label></td>
						<td>
							<input class="StyleSelectBox" id="bank_name" name="bank_name" maxlength="200" title="Наименование банка получателя должно содержать не менее 3 и не более 200 символов!" value= '<?= $bank_name;?>'/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="mask-bik">"БИК" банка получателя:*</label></td>
						<td>
							<input id="mask-bik" class="StyleSelectBox" name="bank_bik" type="text" maxlength="9" pattern="[0-9]{9}" title="БИК банка получателя должен содержать 9 цифр!" value='<?=$bank_bik;?>'/>
						</td>
					</tr>						
					<tr>
						<td class="rowt"><label for="korr_schet">Корр/счет: *</label></td>
						<td>
							<input id="mask-account" class="StyleSelectBox" name="korr_schet" type="text" maxlength="20" title="Корреспондентский счёт организации должен содержать 20 цифр!" value="<?=$korr_schet;?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="rasch_schet">Расчетный счет: *</label></td>
						<td>
							<input id="mask-account2" class="StyleSelectBox" name="rasch_schet" type="text" maxlength="20" title="Расчетный счет организации должен содержать 20 цифр!" value="<?=$rasch_schet;?>"/>
						</td>
					</tr>						
					<tr>
						<td class="rowt"><label for="recipient">Получатель:*</label></td>
						<td>
							<input class="StyleSelectBox" id="recipient" maxlength="150" name="recipient" title="" value="<?= $recipient;?>"/>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">КОНТАКТЫ:</td>
					</tr>
					<tr>
						<td class="rowt"><label for="contact_name">Контактное лицо:*</label></td>
						<td><textarea class="reg_textarea" id="contact_name" name="contact_name" maxlength="200" placeholder = "При вводе нескольких имен (ФИО) используйте разделитель ';'" title="При вводе нескольких имен (ФИО) используйте разделитель ';' Поле 'Получатель' должно содержать не менее 3 и не более 100 символов!"><?= $contact_name;?></textarea></td>
					</tr>					
					<tr>
						<td class="rowt"><label for="phone">Контактный телефон:*</label></td>
						<td><textarea class="reg_textarea" id="phone" name="phone" maxlength="100" placeholder = "При вводе нескольких номеров используйте разделитель ';'" title="При вводе нескольких номеров используйте разделитель ';' Поле 'Контактный телефон' должно содержать не менее 3 и не более 60 символов!"><?= $phone;?></textarea></td>
					</tr>						
					<tr>
						<td class="rowt"><label for="email">Email:*</label></td>
						<td><input id="email" class="StyleSelectBox" name="email" maxlength="100" title = "При вводе нескольких адресов используйте разделитель ';' Поле 'E-Mail' должно содержать не менее 3 и не более 100 символов!" type="text" value="<?=$email;?>"/></td>
					</tr>
					<tr>
						<td class="rowt"><label for="comment">Примечание:</label></td>
						<td><textarea class="reg_textarea" id="comment" name="comment" maxlength="400" title="Поле должно содержать не более 400 символов!" ><?=$comment;?></textarea></td>
					</tr>
					</table>
					<div>
						<input class="button" type="submit" name="do_newcustomer" value="Добавить"/>
					</div>
				</form>
		</div>
</div>
	<script>
		$(document).ready(function() {
		//$('#mask-inn-individual').mask('999999999999');
		//$('#mask-card-number').mask('9999 9999 9999 9999');
		//$('#mask-inn-organization').mask('9999999999');
		//$('#mask-ogrn').mask('9999999999999');
		//$('#mask-ogrnip').mask('999999999999999');
		//$('#mask-kpp').mask('999999999');
		//$('#mask-bik').mask('049999999');
		$('#mask-account').mask('99999999999999999999');
		$('#mask-account2').mask('99999999999999999999');
		});

	</script>
</body>
</html>
<?php
	}
	else
	{
		header('Location: /');
}
?>