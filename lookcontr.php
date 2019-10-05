<?php
require '/connection/config.php';

//ini_set('display_errors', '1');
if(isset($_SESSION['userlevel']) AND  $_SESSION['userlevel']<=3)
{
require_once 'blocks/header.php'; 
require '/func/arrays.php';

if(isset($_GET['look']))
{
	//$data = $_GET['edit'];
	$data = trim(filter_input(INPUT_GET, 'look', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$_SESSION['id_edit'] = $data;
	$contractors = Edit_Contr($link, $data);
	$country_id_edit = $contractors['country_id'];
	$region_id_edit = $contractors['region_id'];
	$city_id_edit = $contractors['city_id'];
	$org_name_edit = html_entity_decode($contractors['org_name'], ENT_QUOTES);
	$status_edit = $contractors['status'];
	$dogovor_edit = html_entity_decode($contractors['dogovor'], ENT_QUOTES);
	$card_number_edit = html_entity_decode($contractors['card_number'], ENT_QUOTES);
	$anketa_edit = $contractors['anketa'];
	$contact_name_edit = html_entity_decode($contractors['contact_name'], ENT_QUOTES);
	$passport_edit = html_entity_decode($contractors['passport'], ENT_QUOTES);
	$methodpayment_edit = $contractors['method_payment'];
	$ownership_edit = $contractors['ownership'];
	$system_no_edit = $contractors['system_no'];
	$mobile_edit = html_entity_decode($contractors['mobile'], ENT_QUOTES);
	$phone_edit = html_entity_decode($contractors['phone'], ENT_QUOTES);
	$email_edit = html_entity_decode($contractors['email'], ENT_QUOTES);
	$web_edit = html_entity_decode($contractors['web'], ENT_QUOTES);
	$comment_edit = html_entity_decode($contractors['comment'], ENT_QUOTES);
	$cityname = Get_Geo($link, $city_id_edit, 'city', 'city_id');
	$regionname = Get_Geo($link, $cityname['region_id'], 'region', 'region_id');
	$countryname = Get_Geo($link, $regionname['country_id'], 'country', 'country_id');
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
	<div class="breadcrumbs"><a href='/showcontractor.php'>Подрядчики</a> > Просмотр:</div>
	<div class="contr_registr">

			<table>
				<tr>
					<td class="rowt" width=40%>Страна:*</td>
					<td class="reg_text">
						<?=$countryname['name'];?>
					</td>
				</tr>
				<tr>
					<td class="rowt">Регион:*</td>
					<td class="reg_text">
						<?=$regionname['name'];?>
					</td>
				</tr>
				<tr>
					<td class="rowt">Населенный пункт:*</td>
					<td class="reg_text">
						<?=$cityname['name'];?>
					</td>
				</tr>
				<tr>
					<td class="rowt">Организация / исполнитель:*</td>
					<td class="reg_text"><?= $org_name_edit ?></td>
				</tr>
				<tr>
					<td class="rowt">Статус подрядчика:*</td>
					<td class="reg_text">
						<?= $statusedit[$status_edit] ?>
					</td>
				</tr>
				<tr>
					<td class="rowt">Номер договора:</td>
					<td class="reg_text"><?=$dogovor_edit?></td>
				</tr>
				<tr>
					<td class="rowt">Форма расчета:*</td>
					<td class="reg_text">								
						<?= $methodpaymentedit[$methodpayment_edit] ?>
					</td>
				</tr>							
				<tr>
					<td class="rowt">Номер карты для оплаты "наличкой":</td>
					<td class="reg_text"><?=$card_number_edit?></td>
				</tr>						
				<tr>
					<td class="rowt">Наличие анкеты:</td>
					<td class="reg_text">
						<?= $anketa_edit ?>
					</td>
				</tr>
				<tr>
					<td class="rowt">Форма собственности:*</td>
					<td class="reg_text">
						<?= $ownership_edit ?>
					</td>
				</tr>
				<tr>
					<td class="rowt">Система налогообложения:*</td>
					<td class="reg_text">
						<?= $system_no_edit ?>
					</td>
				</tr>
				<tr>
					<td class="rowt">Контактное лицо:*</td>
					<td class="reg_text"><?=$contact_name_edit?></td>
				</tr>
				<tr>
					<td class="rowt">Паспортные данные:</td>
					<td class="reg_text"><?=$passport_edit?></td>
				</tr>
				<tr>
					<td class="rowt">Мобильный телефон:*</td>
					<td class="reg_text">
					<?=$mobile_edit?>
					</td>
				</tr>
				<tr>
					<td class="rowt">Рабочий телефон:</td>
					<td class="reg_text">
					<?=$phone_edit?>
					</td>
				</tr>						
				<tr>
					<td class="rowt">Email:</td>
					<td class="reg_text">
						<?=$email_edit;?>
					</td>
				</tr>
				<tr>
					<td class="rowt">WEB-сайт:</td>
					<td class="reg_text">
						<?=$web_edit;?>
					</td>
				</tr>
				<tr>
					<td class="rowt">Примечание:</td>
					<td class="reg_text">
						<?=$comment_edit?>
					</td>
				</tr>
			</table>
			<p><input class="button" value="К списку подрядчиков" type="button" onclick="location.href='showcontractor.php'"/></p>
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
