<?php
require '/connection/config.php';
require_once '/blocks/header.php';

$customers = Show_Customer ($link);
 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Поиск объекта</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/object_select.js'></script>
</head>
	<div class="showany">
	<br>
	<div class="reg_sel_object">
	<p class="breadcrumbs"> Поиск объекта:</p>
		<form action="newticket.php" method="POST">
					<table>
						<tr>
							<td class="rowt">Заказчик:</td>
							<td>
							<select name="id_customer" id="id_customer" class="StyleSelectBox">
								<option value="0">- выберите заказчика -</option>
								<?php foreach($customers as $i => $customer)  { ?>
									<option  value="<?= $customer['id_customer']; ?>"><?= $customer['customer_name']; ?></option>
								<?php } ?>
							</select>
							</td>
							
						</tr>
						<tr >
							
							<td colspan="2" align="center">
								<span  class="reg_link"><a href="newcustomer.php">Добавить заказчика?</a></span>
							</td>
						</tr>
						<tr>
							<td class="rowt">Проект:</td>
							<td>
								<select name="id_project" id="id_project" disabled="disabled" class="StyleSelectBox">
									<option value="0">- выберите проект -</option>
								</select>
								
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<span class="reg_link"  id=link_project></span>
							</td>
						</tr>
						<tr>
							<td class="rowt">Населенный пункт:</td>
							<td>
								<select name="city_id" id="city_id" disabled="disabled" class="StyleSelectBox">
									<option value="0">- выберите город -</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="rowt">Объект:</td>
							<td>
								<select name="id_object" id="id_object" disabled="disabled" class="StyleSelectBox">
									<option value="0">- выберите объект -</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<span class="reg_link"  id=link_object></span>
							</td>
						</tr>
						</table>
						<div>
							<p><button name="select_object">Выбрать объект</button></p>
						</div>
					</form>
					</div>
	<!-- <div id="footer">&copy; ООО "МегаТрейд"</div> -->
	</div>

</body>
</html>