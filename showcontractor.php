<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<4)
{
require_once '/blocks/header.php';
require '/func/arrays.php';
$geo_table = array("country", "region", "city", "3");
$geo_row = array("country_id", "region_id", "city_id", "3");
$data = $_POST;
$search_contr = trim(filter_input(INPUT_POST, 'search_contr'));
$search_contr_select = trim(filter_input(INPUT_POST, 'search_contr_select'));
$geo_table_search = $geo_table[1];
$geo_row_search = $geo_row[1];
$status = 1;
$condition = "WHERE status = '1'";
if( isset($data['contractor_status']))
	{	
		$status = trim(filter_input(INPUT_POST, 'contractor_status'));
		switch($status)
		{
			case '0':	
				$condition = "WHERE status = '0' ";
			break;	
			case '1':	
				$condition = "WHERE status = '1' ";
			break;	
			case '2':	
				$condition = "";
			break;	
		}
		$_SESSION['condition'] = $condition;
	}
/*
if (isset($_GET['page'])){
		$page = $_GET['page'];
	}else $page = 1;
	$kol = 999;  //количество записей для вывода
	$art = ($page * $kol) - $kol;
	// Определяем все количество записей в таблице
	$count_contr = Contr_Count($link, $geo_table_search, $geo_row_search, $search_contr);
	// Количество страниц для пагинации
	$str_pag = ceil($count_contr / $kol);

	if( isset($data['do_search'])){
	if($search_contr_select == $geo_table[1])
	{
		$geo_table_search = $geo_table[1];
		$geo_row_search = $geo_row[1];
	}
	if($search_contr_select == $geo_table[2])
	{
		$geo_table_search = $geo_table[2];
		$geo_row_search = $geo_row[2];
	}
	if($search_contr_select == "org_name")
	
	{
		$geo_table_search = $geo_table[3];
		$geo_row_search = $geo_row[3];
	}

}
*/
//$contr = Show_Contr($link, $geo_table_search, $geo_row_search, $search_contr, $art, $kol);
$contrs = Show_Contractor($link, $condition);



?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Подрядчики</title>
</head>

<body>
	<div class="showcustomer">
			
			
			<?php
			if($_SESSION['userlevel'] <= 3) { ?>
				<div class="newticket">
					<a href='/newcontr.php' ><button class="button-new">Добавить нового подрядчика</button></a>
				</div>
			<?php }?>
			<div class="breadcrumbs">Подрядчики:</div>
				<!--
				<div class="searchcontr">
				<form action="showcontractor.php" method="POST">
				Параметры поиска:
				<select name="search_contr_select">
					<option disabled selected>Выберите значение:</option>
					<option value="">По всем полям</option>
					
				<option value="region">По области</option>
				<option value="city">По городу</option>
				<option value="org_name">По названию организации</option>
				</select>
					<input align="center" type="search" name="search_contr" placeholder="Поиск по контрагентам"> 
					<input align="right" name="do_search" type="submit" value="Найти">
				</form>
			<!--	<script>
setTimeout(function(){
    location.reload();
}, 15000);
</script> 
				</div>-->
					<table border="1" cellspacing="0">
					<thead>
						<tr class="hdr">
							<th width=1%>№</th>
							<!--<th>Страна</th>-->
							<th>Область</th>
							<th>Город</th>
							<th>Организация /<br>Исполнитель</th>
							<th>Контактное<br>лицо</th>
							<th>Мобильный<br>телефон</th>
							<th>Статус</th>
							<!--<th>Наличие<br>анкеты</th>
							<th>Статус</th>
							<th>Система<br>НО</th> -->
							<th colspan="2" width=1%>Действие</th>
						</tr>
						<tr class='table-filters'>
							<td>
							</td>
							<td>
								<input class="reg_input_filter" type="text" placeholder="..."/><!--Область-->
							</td>
							<td>
								<input class="reg_input_filter" type="text" placeholder="..."/><!--Город-->
							</td>							
							<td>
								<input class="reg_input_filter" type="text" placeholder="..."/><!--Исполнитель-->
							</td>
							<td>
								<input class="reg_input_filter" type="text" placeholder="..."/><!--Контактное лицо-->
							</td>							
							<td>
								<input class="reg_input_filter" type="text" placeholder="..."/><!--Мобильный телефон-->
							</td>
							<form action="" method="POST">
							<td>
								<select class="reg_select_filter" name="contractor_status" onchange="this.form.submit()">
									<?php for($i = 0; $i < 3; $i++) { ?>
									<option  value="<?= $i ?>" <?= ($i == $status) ? 'selected' : ''?>><?= $statusedit[$i] ?></option>
									<?php }?>
								</select>
							</td>
							</form>
							<td colspan="2">

							</td>
						</tr>						
					</thead>	
			<?php if($contrs){

				foreach($contrs as $i => $contr) { 
				?>
					<tr class="reg_text_show_tickets">
						<td align="center"><?=$i + 1?></td>
						<?php /* $geo = Get_Geo($link, $contrs['country'], $geo_table[0],  $geo_row[0]);*/?>
						<!-- <td align="center"><?= $geo['name'] ?></td> -->
						<?php $geo = Get_Geo($link, $contr['region_id'], $geo_table[1],  $geo_row[1]);?>
						<td align="center"><?= $geo['name'] ?></td>
						<?php $geo = Get_Geo($link, $contr['city_id'], $geo_table[2],  $geo_row[2]);?>
						<td align="center"><?= $geo['name'] ?></td>
						<td align="center"><?=$contr['org_name']?></td>
						<td align="center">
						<?php
							$contact_name_exp = explode(";", $contr['contact_name']);
							$count_contact_name = count($contact_name_exp);
							if($count_contact_name<2){
								echo $contact_name_exp[0];
							}
							else {
							
							
							for($i = 0; $i < $count_contact_name; $i++)
						{?>

							<?=($i+1).".".$contact_name_exp[$i]."<br>"?>
							<?php }}?>
						</td>
						<td align="center">
						<?php
							$mobile_exp = explode(";", $contr['mobile']);
							$count_mobile = count($mobile_exp);
							if($count_mobile<2){
								echo $mobile_exp[0];
							}
							else {
							for($i = 0; $i < $count_mobile; $i++){?>
						
						<?=($i+1).".".$mobile_exp[$i]."<br>"?>
					
							<?php }}?>
						</td>
						<!-- <td align="center"><?=$contrs['anketa']?></td> -->
						<td><?=$statusedit[$contr['status']];?></td>
						<!-- <td align="center"><?=$contrs['system_no']?></td> -->
						<?php
							if($_SESSION['userlevel'] <= 3) { ?>
							<td align="center"><a href='/editcontr.php?edit=<?= $contr['id_contractor'] ?>' title = 'Изменить'><img src='/images/edit.png' width='20' height='20'></a></td>
						<?php	} ?>
						<td align="center"><a href='/lookcontr.php?look=<?= $contr['id_contractor'] ?>' title = 'Посмотреть'><img src='/images/lupa.png' width='20' height='20'></a></td>
					</tr>
			<?php }	}else { ?>
				<tr>
					<td colspan="8" align="center" class="date">Не добавлено ни одного подрядчика</td>
 				</tr>
			<?php } ?>
				</table>
	</div>
		<script type="text/javascript" src='js/filter_showticket.js'></script>
<script type="text/javascript" src='js/sideup.js'></script>	

<a id="upbutton" href="#" onclick="smoothJumpUp(); return false;">
    <img src="/images/up.png" alt="Top" border="none" title="Наверх">
</a>		

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