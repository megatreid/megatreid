<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']==1)
{
require_once '/blocks/header.php';
$geo_table = array("country", "region", "city", "3");
$geo_row = array("country_id", "region_id", "city_id", "3");
$data = $_POST;
$search_contr = trim(filter_input(INPUT_POST, 'search_contr'));
$search_contr_select = trim(filter_input(INPUT_POST, 'search_contr_select'));
$geo_table_search = $geo_table[1];
$geo_row_search = $geo_row[1];
if (isset($_GET['page'])){
		$page = $_GET['page'];
	}else $page = 1;
	
	$kol = 15;  //количество записей для вывода
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
$contr = Show_Contr($link, $geo_table_search, $geo_row_search, $search_contr, $art, $kol);




?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Контрагенты</title>
</head>
<body>

<section >
	<div>
		<div>
			<div class="showcontr">
			<h3>Контрагенты:</h3>
				<div class="newcontr"><a href='/newcontr.php' ><button>Добавить нового контрагента</button></a></div>
				<div class="searchcontr">
				<form action="contractor.php" method="POST">
				Параметры поиска:
				<select name="search_contr_select">
					<!-- <option disabled selected>Выберите значение:</option>
					<option value="">По всем полям</option> -->
					
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
</script> -->
				</div>
					<table border="1" cellspacing="0">

						<tr>
							<th>№</th>
							<!--<th>Страна</th>-->
							<th>Область</th>
							<th>Город</th>
							<th>Организация /<br>Исполнитель</th>
							<th>Контактное<br>лицо</th>
							<th>Мобильный<br>телефон</th>
							<!--<th>Наличие<br>анкеты</th>
							<th>Статус</th>
							<th>Система<br>НО</th> -->
							<th colspan="2">Действие</th>
						</tr>
						
			<?php if($contr){

				foreach($contr as $i => $contrs) { 
				?>
					<tr>
						<td align="center"><?=$art + $i + 1?></td>
						<?php /* $geo = Get_Geo($link, $contrs['country'], $geo_table[0],  $geo_row[0]);*/?>
						<!-- <td align="center"><?= $geo['name'] ?></td> -->
						<?php $geo = Get_Geo($link, $contrs['region_id'], $geo_table[1],  $geo_row[1]);?>
						<td align="center"><?= $geo['name'] ?></td>
						<?php $geo = Get_Geo($link, $contrs['city_id'], $geo_table[2],  $geo_row[2]);?>
						<td align="center"><?= $geo['name'] ?></td>
						<td align="center"><?=$contrs['org_name']?></td>
						<td align="center">
						<?php
							$contact_name_exp = explode(";", $contrs['contact_name']);
							$count_contact_name = count($contact_name_exp);
							if($count_contact_name<2){
								echo $contact_name_exp[0];
							}
							else {
							
							
							for($i = 0; $i < $count_contact_name; $i++)
						{?>

							<?=($i+1).". ".$contact_name_exp[$i]."<br>"?>
							<?php }}?>
						</td>
						<td align="center">
						<?php
							$mobile_exp = explode(";", $contrs['mobile']);
							$count_mobile = count($mobile_exp);
							if($count_mobile<2){
								echo $mobile_exp[0];
							}
							else {
							for($i = 0; $i < $count_mobile; $i++){?>
						
						<?=($i+1).". ".$mobile_exp[$i]."<br>"?>
					
							<?php }}?>
						</td>
						<!-- <td align="center"><?=$contrs['anketa']?></td> -->
						<!-- <td align="center"><?=$contrs['status']?></td> -->
						<!-- <td align="center"><?=$contrs['system_no']?></td> -->
						<td align="center"><a href='/editcontr.php?edit=<?= $contrs['id_contractor'] ?>' title = 'Изменить'><img src='/images/edit.png' width='20' height='20'></a></td>
						<td align="center"><a href='/lookcontr.php?look=<?= $contrs['id_contractor'] ?>' title = 'Посмотреть'><img src='/images/lupa.png' width='20' height='20'></a></td>
					</tr>
			<?php }	}

			
			
			
			?>
					</table>
			</div>
		</div>
	</div>
</section>

<div class = "pagination">
	<?="Страницы: "?>
	<?php
	for ($i = 1; $i <= $str_pag; $i++){ 
	if($i == $page) {?>
		<?= "<a href=contractor.php?page=".$i.">(".$i.") </a>"?>
	<?php } else {?>
		<?= "<a href=contractor.php?page=".$i.">".$i." </a>"?>
	<?php }}?>	
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