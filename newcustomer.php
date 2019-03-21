<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND ($_SESSION['userlevel']==1) OR $_SESSION['userlevel']==2){
require_once 'blocks/header.php'; 
$data = $_POST;

$err=0;
$customer_name = trim(filter_input(INPUT_POST, 'customer_name'));
$jur_address = trim(filter_input(INPUT_POST, 'jur_address'));
$post_address = trim(filter_input(INPUT_POST, 'post_address'));
$ogrn = trim(filter_input(INPUT_POST, 'ogrn'));
$inn = trim(filter_input(INPUT_POST, 'inn'));
$kpp = trim(filter_input(INPUT_POST, 'kpp'));
$dogovor_number = trim(filter_input(INPUT_POST, 'dogovor_number'));
$bank_name = trim(filter_input(INPUT_POST, 'bank_name'));
$bank_bik = trim(filter_input(INPUT_POST, 'bank_bik'));
$korr_schet = trim(filter_input(INPUT_POST, 'korr_schet'));
$rasch_schet = trim(filter_input(INPUT_POST, 'rasch_schet'));
$recipient = trim(filter_input(INPUT_POST, 'recipient'));
$contact_name = trim(filter_input(INPUT_POST, 'contact_name'));
$phone = trim(filter_input(INPUT_POST, 'phone'));
$email = trim(filter_input(INPUT_POST, 'email'));
$web = trim(filter_input(INPUT_POST, 'web'));
$comment = trim(filter_input(INPUT_POST, 'comment'));

if( isset($data['do_newcustomer']))
	{
		$errors=array();//массив сообшений ошибок

/* ------------------------------------------------------------------------------------------------- */
		if(empty($customer_name))
		{
			$errors[] = 'Введите название организации!';
		}
		if(mb_strlen($customer_name)>60 or mb_strlen($customer_name)<3)
		{
			$errors[] = 'Название организации должно содержать не менее 3 и не более 60 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($jur_address))
		{
			$errors[] = 'Укажите юридический адрес организации!';
		}
		if(mb_strlen($jur_address)>100 or mb_strlen($jur_address)<3)
		{
			$errors[] = 'Юридический адрес организации должен содержать не менее 3 и не более 100 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($post_address))
		{
			$errors[] = 'Укажите почтовый (фактический) адрес организации!';
		}
		if(mb_strlen($post_address)>100 or mb_strlen($post_address)<3)
		{
			$errors[] = 'Фактический адрес организации должен содержать не менее 3 и не более 100 символов!';
		}	
/* ------------------------------------------------------------------------------------------------- */
		if(empty($ogrn))
		{
			$errors[] = 'Введите ОГРН организации!';
		}
		if(mb_strlen($ogrn)>15 or mb_strlen($ogrn)<13)
		{
			$errors[] = 'ОГРН организации должен содержать не менее 13 и не более 15 символов!';
		}	
/* ------------------------------------------------------------------------------------------------- */
		if(empty($inn))
		{
			$errors[] = 'Введите ИНН организации!';
		}
		if(mb_strlen($inn)!=12)
		{
			$errors[] = 'ИНН организации должен содержать 12 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($inn))
		{
			$errors[] = 'Введите ИНН организации!';
		}
		if(mb_strlen($inn)!=12)
		{
			$errors[] = 'ИНН организации должен содержать 12 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($kpp))
		{
			$errors[] = 'Введите КПП организации!';
		}
		if(mb_strlen($kpp)>9)
		{
			$errors[] = 'КПП организации не должен содержать более 9 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($dogovor_number))
		{
			$errors[] = 'Укажите номер договора!';
		}
		if(mb_strlen($dogovor_number)>30 or mb_strlen($dogovor_number)<3)
		{
			$errors[] = 'Номер договора должен содержать не менее 3 и не более 30 символов!';
		}	
/* ------------------------------------------------------------------------------------------------- */
		if(empty($bank_name))
		{
			$errors[] = 'Укажите наименование банка получателя!';
		}
		if(mb_strlen($bank_name)>100 or mb_strlen($bank_name)<3)
		{
			$errors[] = 'Наименование банка получателя должно содержать не менее 3 и не более 100 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($bank_bik))
		{
			$errors[] = 'Укажите БИК банка получателя!';
		}
		if(mb_strlen($bank_bik)!=9)
		{
			$errors[] = 'БИК банка получателя должен содержать 9 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($korr_schet))
		{
			$errors[] = 'Введите корреспондентский счёт!';
		}
		if(mb_strlen($korr_schet)!=20)
		{
			$errors[] = 'Корреспондентский счёт организации должен содержать 20 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($rasch_schet))
		{
			$errors[] = 'Введите расчетный счет!';
		}
		if(mb_strlen($rasch_schet)!=20)
		{
			$errors[] = 'Расчетный счет организации должен содержать 20 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($recipient))
		{
			$errors[] = 'Введите имя получателя!';
		}
		if(mb_strlen($recipient)>100 or mb_strlen($recipient)<3)
		{
			$errors[] = 'Поле \"Получатель\" должно содержать не менее 3 и не более 100 символов!';
		}		
/* ------------------------------------------------------------------------------------------------- */
		if(empty($contact_name))
		{
			$errors[] = 'Укажите контактное лицо';
		}
		if(mb_strlen($contact_name)>100 or mb_strlen($contact_name)<3)
		{
			$errors[] = 'Поле \"КОНТАКТНОЕ ЛИЦО\" должно содержать не менее 3 и не более 100 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */
		if(empty($phone))
		{
			$errors[] = 'Укажите контактный телефон';
		}
		if(mb_strlen($phone)>60 or mb_strlen($phone)<3)
		{
			$errors[] = 'Поле \"Мобильный телефон\" должно содержать не менее 3 и не более 60 символов!';
		}
/* ------------------------------------------------------------------------------------------------- */		
		if(empty($email))
		{
			$errors[] = 'Укажите E-Mail';
		}
		if(mb_strlen($email)>60 or mb_strlen($email)<3)
		{
			$errors[] = 'Поле \"E-Mail\" должно содержать не менее 3 и не более 60 символов!';
		}			
/* ------------------------------------------------------------------------------------------------- */
		if(mb_strlen($comment)>100)
		{
			$errors[] = 'Поле \"Примечание\" должно содержать не более 100 символов!';
		}			
/* ------------------------------------------------------------------------------------------------- */ 
	if(empty($errors)){  

		$result = Add_Customer ($link, $customer_name, $jur_address, $post_address, $ogrn, $inn, $kpp, $dogovor_number, $bank_name, $bank_bik, $korr_schet, $rasch_schet, $recipient, $phone, $email, $contact_name, $comment); 
		
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
</head>
<body>
	<div class="showany">
		<p class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > Новый заказчик:</p>
		<div class="reg_up_table">
			<?php if($err==1){?>
			<div class="error-message"><?=array_shift($errors)?></div>
			<?php }?>
			
				<form action="/newcustomer.php" method="POST">
				<p style = "font-size: 8pt">Все поля, отмеченные звездочкой, являются обязательными</p>
					<table>
					<tr>
						<td class="rowt"><label for="customer_name">Название организации: *</label></td>
						<td>
							<input id="customer_name" class="StyleSelectBox" name="customer_name" maxlength="60" type="text" title="Название организации должно содержать не менее 3 и не более 60 символов!" value="<?= @$data['customer_name'];?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="jur_address">Юридический адрес: *</label></td>
						<td>
							<input id="jur_address" class="StyleSelectBox" name="jur_address" maxlength="100" type="text" title="Юридический адрес организации должен содержать не менее 3 и не более 100 символов!" value="<?= @$data['jur_address'];?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="post_address">Почтовый (фактический) адрес: *</label></td>
						<td>
							<input id="post_address" class="StyleSelectBox" name="post_address" maxlength="100" title="Фактический адрес организации должен содержать не менее 3 и не более 100 символов!" type="text" value="<?= @$data['post_address'];?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="ogrn">ОГРН: *</label></td>
						<td>
							<input id="ogrn" class="StyleSelectBox" name="ogrn" type="number" title="ОГРН организации должен содержать не менее 13 и не более 15 символов!" value="<?= @$data['ogrn'];?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="inn">ИНН: *</label></td>
						<td>
							<input id="inn" class="StyleSelectBox" name="inn"  maxlength="12" type="number" title="'ИНН организации должен содержать 12 символов!" value="<?= @$data['inn'];?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="kpp">КПП: *</label></td>
						<td>
							<input id="kpp" class="StyleSelectBox" name="kpp" type="number"  maxlength="9"  title="КПП организации не должен содержать более 9 символов!" value="<?= @$data['kpp'];?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="dogovor_number">Номер договора: *</label></td>
						<td>
							<input id="dogovor_number" class="StyleSelectBox" name="dogovor_number"  maxlength="30"  title="Номер договора должен содержать не менее 3 и не более 30 символов!" type="text" value="<?= @$data['dogovor_number'];?>"/>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							РЕКВИЗИТЫ:
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="bank_name">Банк получателя: *</label></td>
						<td>
							<textarea class="reg_textarea" id="bank_name" name="bank_name" maxlength="100" title="Наименование банка получателя должно содержать не менее 3 и не более 100 символов!"><?= @$data['bank_name'];?></textarea>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="bank_bik">"БИК" банка получателя: *</label></td>
						<td>
							<input id="bank_bik" class="StyleSelectBox" name="bank_bik" type="number" title="БИК банка получателя должен содержать 9 символов!" value="<?= @$data['bank_bik'];?>"/>
						</td>
					</tr>						
					<tr>
						<td class="rowt"><label for="korr_schet">Корр/счет: *</label></td>
						<td>
							<input id="korr_schet" class="StyleSelectBox" name="korr_schet" type="number" maxlength="20" title="Корреспондентский счёт организации должен содержать 20 символов!" value="<?= @$data['korr_schet'];?>"/>
						</td>
					</tr>
					<tr>
						<td class="rowt"><label for="rasch_schet">Расчетный счет: *</label></td>
						<td>
							<input id="rasch_schet" class="StyleSelectBox" name="rasch_schet" type="number" maxlength="20" title="Расчетный счет организации должен содержать 20 символов!" value="<?= @$data['rasch_schet'];?>"/>
						</td>
					</tr>						
					<tr>
						<td class="rowt"><label for="recipient">Получатель: *</label></td>
						<td><textarea class="reg_textarea" id="recipient" name="recipient"  title=""><?= @$data['recipient']?></textarea></td>
					</tr>
					<tr>
						<td colspan="2" align="center">КОНТАКТЫ:</td>
					</tr>
					<tr>
						<td class="rowt"><label for="contact_name">Контактное лицо: *</label></td>
						<td><textarea class="reg_textarea" id="contact_name" name="contact_name" maxlength="100" placeholder = "При вводе нескольких имен (ФИО) используйте разделитель ';'" title="При вводе нескольких имен (ФИО) используйте разделитель ';' Поле 'Получатель' должно содержать не менее 3 и не более 100 символов!"><?= @$data['contact_name']?></textarea></td>
					</tr>					
					<tr>
						<td class="rowt"><label for="phone">Контактный телефон: *</label></td>
						<td><textarea class="reg_textarea" id="phone" name="phone" placeholder = "При вводе нескольких номеров используйте разделитель ';'" title="При вводе нескольких номеров используйте разделитель ';' Поле 'Контактный телефон' должно содержать не менее 3 и не более 60 символов!"><?= @$data['phone'];?></textarea></td>
					</tr>						
					<tr>
						<td class="rowt"><label for="email">Email: *</label></td>
						<td><input id="email" class="StyleSelectBox" name="email" title = "При вводе нескольких адресов используйте разделитель ';' Поле 'E-Mail' должно содержать не менее 3 и не более 60 символов!" type="text" value="<?= @$data['email'];?>"/></td>
					</tr>
					<tr>
						<td class="rowt"><label for="comment">Примечание:</label></td>
						<td><textarea class="reg_textarea" id="comment" name="comment" title="Поле должно содержать не более 100 символов!" ></textarea></td>
					</tr>
					</table>
					<div>
						<p><button name="do_newcustomer">Добавить</button></p>
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