<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND ($_SESSION['userlevel']==1) OR $_SESSION['userlevel']==2)
{
require_once 'blocks/header.php'; 
if(isset($_GET['edit']))
{
	$data = $_GET['edit'];
	$_SESSION['id_edit'] = $data;
	$customers = Edit_Customer($link, $data);
	$customer_name_edit = $customers['customer_name'];
	$jur_address_edit = $customers['jur_address'];
	$post_address_edit = $customers['post_address'];
	$ogrn_edit = $customers['ogrn'];
	$inn_edit = $customers['inn'];
	$kpp_edit = $customers['kpp'];
	$dogovor_number_edit = $customers['dogovor_number'];
	$bank_name_edit = $customers['bank_name'];
	$bank_bik_edit = $customers['bank_bik'];
	$korr_schet_edit = $customers['korr_schet'];
	$rasch_schet_edit = $customers['rasch_schet'];
	$recipient_edit = $customers['recipient'];
	$contact_name_edit = $customers['contact_name'];
	$phone_edit = $customers['phone'];
	$email_edit = $customers['email'];
	$comment_edit = $customers['comment'];
}
$data_update = $_POST;
$err=FALSE;
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
$comment = trim(filter_input(INPUT_POST, 'comment'));

if( isset($data_update['do_editcustomer']))
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
 
	if(empty($errors))
	{  
?>
<br><br>
<?php
		$id_customer = $_SESSION['id_edit'];
		
		$update_customer = Update_Customer ($link, $id_customer, $customer_name, $jur_address, $post_address, $ogrn, $inn, $kpp, $dogovor_number, $bank_name, $bank_bik, $korr_schet, $rasch_schet, $recipient, $phone, $email, $contact_name, $comment);

		if($update_customer)
		{
			unset($_SESSION['id_edit']);?>				
			<script>
				setTimeout(function() {window.location.href = '/showcustomer.php';}, 0);
			</script>
			<?php	
		}
		
	}
	else
		{

			$err=TRUE;
		}
	}
	if(isset($_POST['delete_customer'])){
		
	$deletecustomer = Delete_Customer($link, $_SESSION['id_edit']);
	if($deletecustomer)
	{
		unset($_SESSION['id_edit']);
		?>
			<script>
				setTimeout(function() {window.location.href = '/showcustomer.php';}, 0);
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
	<title>Изменение данных по заказчику</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/selects.js'></script>
</head>
<body>
	<div class="showany">

	<p class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > Редактирование:</p>
	<div class="reg_up_table">
			<?php if($err == TRUE){?>
				<div class="error-message"><?=array_shift($errors)?></div>
				<?php }?>
				<form action="/editcustomer.php?edit=<?= $_SESSION['id_edit'];?>" method="POST">
					<p style = "font-size: 8pt">Все поля, отмеченные звездочкой, являются обязательными</p>
						<table>
						<tr>
							<td class="rowt"><label for="customer_name">Название организации: *</label></td>
							<td>
								<input id="customer_name" class="StyleSelectBox" name="customer_name" type="text" title="Название организации должно содержать не менее 3 и не более 60 символов!" value="<?= $customer_name_edit;?>"/>
							</td>
						</tr>
						<tr>
							<td class="rowt"><label for="jur_address">Юридический адрес: *</label></td>
							<td>
								<input id="jur_address" class="StyleSelectBox" name="jur_address" type="text" title="Юридический адрес организации должен содержать не менее 3 и не более 100 символов!" value="<?= $jur_address_edit;?>"/>
							</td>
						</tr>
						<tr>
							<td class="rowt"><label for="post_address">Почтовый (фактический) адрес: *</label></td>
							<td>
								<input id="post_address" class="StyleSelectBox" name="post_address" title="Фактический адрес организации должен содержать не менее 3 и не более 100 символов!" type="text" value="<?= $post_address_edit;?>"/>
							</td>
						</tr>
						<tr>
							<td class="rowt"><label for="ogrn">ОГРН: *</label></td>
							<td>
								<input id="ogrn" class="StyleSelectBox" name="ogrn" type="text" title="ОГРН организации должен содержать не менее 13 и не более 15 символов!" value="<?= @$ogrn_edit;?>"/>
							</td>
						</tr>
						<tr>
							<td class="rowt"><label for="inn">ИНН: *</label></td>
							<td>
								<input id="inn" class="StyleSelectBox" name="inn" type="text" title="'ИНН организации должен содержать 12 символов!" value="<?= @$inn_edit;?>"/>
							</td>
						</tr>
						<tr>
							<td class="rowt"><label for="kpp">КПП: *</label></td>
							<td>
								<input id="kpp" class="StyleSelectBox" name="kpp" type="text" title="КПП организации не должен содержать более 9 символов!" value="<?= @$kpp_edit;?>"/>
							</td>
						</tr>
						<tr>
							<td class="rowt"><label for="dogovor_number">Номер договора: *</label></td>
							<td>
								<input id="dogovor_number" class="StyleSelectBox" name="dogovor_number" title="Номер договора должен содержать не менее 3 и не более 30 символов!" type="text" value="<?= @$dogovor_number_edit;?>"/>
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
								<textarea class="reg_textarea" id="bank_name" name="bank_name" cols="32" rows="3" title="Наименование банка получателя должно содержать не менее 3 и не более 100 символов!"><?= @$bank_name_edit;?></textarea>
							</td>
						</tr>
						<tr>
							<td class="rowt"><label for="bank_bik">"БИК" банка получателя: *</label></td>
							<td>
								<input id="bank_bik" class="StyleSelectBox" name="bank_bik" type="text" title="БИК банка получателя должен содержать 9 символов!" value="<?= @$bank_bik_edit;?>"/>
							</td>
						</tr>						
						<tr>
							<td class="rowt"><label for="korr_schet">Корр/счет: *</label></td>
							<td>
								<input id="korr_schet" class="StyleSelectBox" name="korr_schet" type="text" title="Корреспондентский счёт организации должен содержать 20 символов!" value="<?= @$korr_schet_edit;?>"/>
							</td>
						</tr>
						<tr>
							<td class="rowt"><label for="rasch_schet">Расчетный счет: *</label></td>
							<td>
								<input id="rasch_schet" class="StyleSelectBox" name="rasch_schet" type="text" title="Расчетный счет организации должен содержать 20 символов!" value="<?= @$rasch_schet_edit;?>"/>
							</td>
						</tr>						
						<tr>
							<td class="rowt"><label for="recipient">Получатель: *</label></td>
							<td><textarea class="reg_textarea" id="recipient" name="recipient" cols="32" title="" rows="3"><?= @$recipient_edit?></textarea></td>
						</tr>
						<tr>
							<td colspan="2" align="center">КОНТАКТЫ:</td>
						</tr>
						<tr>
							<td class="rowt"><label for="contact_name">Контактное лицо: *</label></td>
							<td><textarea class="reg_textarea" id="contact_name" name="contact_name" cols="32" rows="3" placeholder = "При вводе нескольких имен (ФИО) используйте разделитель ';'" title="При вводе нескольких имен (ФИО) используйте разделитель ';' Поле 'Получатель' должно содержать не менее 3 и не более 100 символов!"><?= @$contact_name_edit?></textarea></td>
						</tr>					
						<tr>
							<td class="rowt"><label for="phone">Контактный телефон: *</label></td>
							<td><textarea class="reg_textarea" id="phone" name="phone" cols="32" rows="3" placeholder = "При вводе нескольких номеров используйте разделитель ';'" title="При вводе нескольких номеров используйте разделитель ';' Поле 'Контактный телефон' должно содержать не менее 3 и не более 60 символов!"><?= @$phone_edit;?></textarea></td>
						</tr>						
						<tr>
							<td class="rowt"><label for="email">Email: *</label></td>
							<td><input id="email" class="StyleSelectBox" name="email"  title = "При вводе нескольких адресов используйте разделитель ';' Поле 'E-Mail' должно содержать не менее 3 и не более 60 символов!" type="text" value="<?= @$email_edit;?>"/></td>
						</tr>
						<tr>
							<td class="rowt"><label for="comment">Примечание:</label></td>
							<td><textarea class="reg_textarea" id="comment" name="comment" cols="32" rows="3" value="<?= @$email_edit;?>" title="Поле должно содержать не более 100 символов!" ></textarea></td>
						</tr>
						<tr>
						</table>
						<button class="button" name="do_editcustomer">Сохранить</button></td>
						<button class="button_back" onclick="history.go(-1); return false;">Назад</button>
						<td align = "center"><button class="button-delete" onclick='return confirm("Вы уверены, что хотите удалить этого заказчика?")' name="delete_customer">Удалить</button></td>
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