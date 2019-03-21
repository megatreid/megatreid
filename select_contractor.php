<?php
require '/connection/config.php';
require_once '/blocks/header.php';

$contractors = Show_Contr_for_select ($link);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Выбор контрагента</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/contractor_select.js'></script>
</head>
	<div class="showany">
	<br>
		<div class="reg_sel_object">
	<p class="breadcrumbs"> Выбор контрагента:</p>
		<form action="newreg.php" method="POST">
					<table>
						<tr>
							<td class="rowt">Город:</td>
							<td>
							<select name="city_id" id="city_id" class="StyleSelectBox">
								<option value="0">- выберите город -</option>
								<?php foreach($contractors as $i => $contractor)  { 
								$citys= Get_Geo ($link, $contractor['city_id'], "city", "city_id" );
								
								?>
									<option  value="<?= $contractor['city_id']; ?>"><?= $citys['name']; ?></option>
								<?php } ?>
							</select>
							</td>
							
						</tr>
						<tr>
							<td class="rowt">Контрагент:</td>
							<td>
								<select name="id_contractor" id="id_contractor" disabled="disabled" class="StyleSelectBox">
									<option value="0">- выберите контрагента -</option>
								</select>
								
							</td>
						</tr>
						
						<tr>
							<td colspan="2" align="center">
								<span class="reg_link" id=link_contractor></span>
							</td>
						</tr>
						</table>
						<div>
							<p><button name="select_contractor">Выбрать контрагента</button></p>
						</div>
					</form>

					</div>
	<!-- <div id="footer">&copy; ООО "МегаТрейд"</div> -->
	</div>

</body>
</html>