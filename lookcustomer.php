<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<=3)
{
require_once 'blocks/header.php';
require '/func/arrays.php';
if(isset($_GET['look']))
{
	$data = $_GET['look'];
	$customers = Edit_Customer($link, $data);
	$contact_name_edit = $customers['contact_name'];
	$contact_name_exp = explode(";", $contact_name_edit);
	$count_contact_name = count($contact_name_exp);
	$phone_edit = $customers['phone'];
	$phone_exp = explode(";", $phone_edit);
	$count_phone = count($phone_exp);
	$email_edit = $customers['email'];
	$email_exp = explode(";", $email_edit);
	$count_email = count($email_exp);
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Просмотр данных по заказчику</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/selects.js'></script>
</head>
<body>
	<div class="showany">
	<p class="breadcrumbs"><a href='/showcustomer.php'>Заказчики</a> > Просмотр:</p>
	<div class="for_look">
		<table>
		<tr>
			<td class="rowt">Название организации:</td>
			<td>
				<?= $customers['customer_name'];?>
			</td>
		</tr>
		<tr>
			<td class="rowt">Юридический адрес:</td>
			<td>
				<?= $customers['jur_address'];?>
			</td>
		</tr>
		<tr>
			<td class="rowt">Фактический адрес:</td>
			<td>
				<?= $customers['post_address']?>
			</td>
		</tr>

		<tr>
			<td class="rowt">ОГРН:</td>
			<td>
				<?=$customers['ogrn']?>
			</td>
		</tr>
		<tr>
			<td class="rowt">ИНН:</td>
			<td>
				<?=$customers['inn']?>
			</td>
		</tr>
		<tr>
			<td class="rowt">КПП:</td>
			<td>
				<?=$customers['kpp']?>
			</td>
		</tr>
		<tr>
			<td class="rowt">Номер договора:</td>
			<td>
				<?=$customers['dogovor_number']?>
			</td>
		</tr>
		<tr>
			<td class="rowt">Статус:</td><td><?=$statusedit[$customers['status']];?></td>
		</tr>					
		<tr>
			<td colspan="2" align="center">
				<b>РЕКВИЗИТЫ:</b>
			</td>
		</tr>						
		<tr>
			<td class="rowt">Банк получателя:</td>
			<td>
				<?=$customers['bank_name']?>
			</td>
		</tr>
		<tr>
			<td class="rowt">БИК банка получателя:</td>
			<td>
				<?=$customers['bank_bik']?>
			</td>
		</tr>
		<tr>
			<td class="rowt">Корр/счет:</td>
			<td>
				<?=$customers['korr_schet']?>
			</td>
		</tr>
		<tr>
			<td class="rowt">Расчетный счет:</td>
			<td>
				<?=$customers['rasch_schet']?>
			</td>
		</tr>
		<tr>
			<td class="rowt">Получатель:</td>
			<td>
				<?=$customers['recipient']?>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><b>КОНТАКТЫ:</b></td>
		</tr>
		<tr>
			<td class="rowt">Контактное лицо:</td>
			<td>
				<?php
				if($count_contact_name<2){
					echo $contact_name_exp[0];
				}
				else {							
				for($i = 0; $i < $count_contact_name; $i++)
				{?>
				
					<?=($i+1).". ".$contact_name_exp[$i]."<br>"?>
					
				<?php }}?>
			</td>
		</tr>
		<tr>
			<td class="rowt">Рабочий телефон:</td>
			<td>
				<?php if(!empty($phone_edit))  {?>
					
						<?php
						if($count_phone<2){
							echo $phone_exp[0];
						}
							else {
						for($i = 0; $i < $count_phone; $i++)
						{?>
							<?=($i+1).". ".$phone_exp[$i]."<br>"?>
							<?php }}?>
					
				<?php }?>
			</td>
		</tr>
		<tr>
			<td class="rowt">E-Mail:</td>
			<td>
				<?php if(!empty($email_edit))  {?>
						<?php
						if($count_email<2){
							echo $email_exp[0];
						}
							else {									
						for($i = 0; $i < $count_email; $i++)
						{?>
							<?=($i+1).". ".$email_exp[$i]."<br>"?>
							<?php }}?>
				<?php }?>
			</td>
		</tr>						
		<tr>
			<td class="rowt">Примечание:</td><td><textarea readonly disabled rows="5" cols = "32"><?=$customers['comment']?></textarea></td>
		</tr>
		</table>
			<div>
				<input class="button" value="К списку заказчиков" type="button" onclick="location.href='showcustomer.php'" />
			</div>
			<br>
	</div>	
	</div>
<div id="footer">&copy; ООО "МегаТрейд"</div>
</body>
</html>
<?php
	}
	else
	{
		header('Location: /');
}
?>