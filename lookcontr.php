<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']))
{
require_once 'blocks/header.php';
require '/func/arrays.php';
if(isset($_GET['look']))
{
	$geo_table = array("country", "region", "city", "3");
	$geo_row = array("country_id", "region_id", "city_id", "3");
	$data = $_GET['look'];
	$contractors = Edit_Contr($link, $data);
	$region_id = $contractors['region_id'];
	$city_id = $contractors['city_id'];
	$org_name_edit = $contractors['org_name'];
	$status = $contractors['status'];
	$anketa_edit = $contractors['anketa'];
	$contact_name_edit = $contractors['contact_name'];
	$contact_name_exp = explode(";", $contact_name_edit);
	$count_contact_name = count($contact_name_exp);
	$passport = $contractors['passport'];
	$ownership_edit = $contractors['ownership'];
	$system_no_edit = $contractors['system_no'];
	$mobile_edit = $contractors['mobile'];
	$mobile_exp = explode(";", $mobile_edit);
	$count_mobile = count($mobile_exp);
	$phone_edit = $contractors['phone'];
	$phone_exp = explode(";", $phone_edit);
	$count_phone = count($phone_exp);
	$email_edit = $contractors['email'];
	$web_edit = $contractors['web'];
	$comment_edit = $contractors['comment'];
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Просмотр данных по подрядчику</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/selects.js'></script>
</head>
<body>
		<div class="showany">
			<div class="contr_registr">
				<p class="breadcrumbs"><a href='/showcontractor.php'>Подрядчики</a> > Просмотр:</p>

					<table>

						<tr>
							<td class="rowt">Регион:</td>
							<td>
							<?php $geo = Get_Geo($link, $region_id, $geo_table[1],  $geo_row[1]);
							echo $geo['name'];?>
							</td>
						</tr>
						<tr>
							<td class="rowt">Населенный пункт:</td>
							<td>
							<?php $geo = Get_Geo($link, $city_id, $geo_table[2],  $geo_row[2]);
							echo $geo['name'];?>
							</td>
						</tr>
						<tr>
							<td class="rowt">Организация / исполнитель:</td><td><?= $org_name_edit?></td>
						</tr>
						<tr>
							<td class="rowt">Статус:</td><td><?=$statusedit[$status];?></td>
						</tr>
						<tr>
							<td class="rowt">Наличие анкеты:</td>
							<td>
								<?=$anketa_edit?>
							</td>
						</tr>
						<tr>
							<td class="rowt">Форма собственности:</td>
							<td>
								<?=$ownership_edit;?>
							</td>
						</tr>
						<tr>
							<td class="rowt">Система налогообложения:</td>
							<td>
								<?=$system_no_edit?>
							</td>
						</tr>
						<tr>
							<td class="rowt">Контактное лицо:</td>
							<td>
								<?php if(!empty($contact_name_edit))  {?>
										<?php
										if($count_contact_name<2){
											echo $contact_name_exp[0];
										}
										else {	
								for($i = 0; $i < $count_contact_name; $i++)
								{?>
									<?=($i+1).". ".$contact_name_exp[$i]."<br>"?>
								<?php }}}?>
							</td>
						</tr>
						<tr>
							<td class="rowt">Паспортные данные:</td>
							<td>
								<textarea readonly disabled rows="5" cols = "32" class="textarea"><?=$passport?></textarea>
							</td>
						</tr>						
						<tr>
							<td class="rowt">Мобильный телефон:</td><td>
							<?php if(!empty($mobile_edit))  {?>
									<?php
									if($count_mobile<2){
										echo $mobile_exp[0];
									}
									else {	
							for($i = 0; $i < $count_mobile; $i++)
							{?>
								<?=($i+1).". ".$mobile_exp[$i]."<br>"?>
							<?php }}}?>
							</td>
						</tr>
						<tr>
							<td class="rowt">Рабочий телефон:</td><td>
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
							<td class="rowt">Email:</td><td><?=$email_edit?></td>
						</tr>
						<tr>
							<td class="rowt">WEB-сайт:</td><td><?=$web_edit;?></td>
						</tr>
						<tr>
							<td class="rowt">Примечание:</td>
							<td>
								<textarea readonly disabled rows="5" cols = "32" class="textarea"><?=$comment_edit?></textarea>
							</td>
						</tr>
						</table>
												
						<div>
<input class="button" value="К списку подрядчиков" type="button" onclick="location.href='showcontractor.php'" /><br>
						</div>
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