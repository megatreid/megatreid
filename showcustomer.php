<?php
require 'connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']<4){
require_once 'blocks/header.php';
require 'func/arrays.php';
$customer = Show_Customer($link);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Заказчики</title>
	<script type="text/javascript" src='js/jquery.js'></script>
</head>
<body>
	<div class="showcustomer">
		<div class="breadcrumbs">Заказчики:</div>
		<?php if($_SESSION['userlevel']<=3){ ?>
			<div class="newticket">
				<a href='newcustomer.php'><button class="button-new">Добавить нового заказчика</button></a>
			</div>
		<?php }?>
		<table border="1">
			<thead>
				<tr class="hdr">
					<th rowspan="2" width="1">№</th>
					<!--<th>Страна</th>-->
					<th>Название организации</th>
					<th>Контактное лицо</th>
					<th>Контактный телефон</th>							
					<th>E-Mail</th>
					<th  rowspan="2" width=1%>Статус</th>
					<th  rowspan="2" width="1" <?php if($_SESSION['userlevel'] <= 3){ ?> colspan="2"<?php }?>>Действие</th>
					<th  rowspan="2" width="1">Проекты</th>
				</tr>
				<tr class='table-filters'>

					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Название организации-->
					</td>
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Контактное лицо-->
					</td>							
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--Контактный телефон-->
					</td>
					<td>
						<input class="reg_input_filter" type="text" placeholder="..."/><!--E-Mail.-->
					</td>							

				</tr>
			</thead>	
	<?php if($customer){
		foreach($customer as $i => $customers) { 
		?>
			<tr class="reg_text_show_tickets">
				<td align="center" width="1"><?=$i + 1?></td>
				<td align="center"><?=$customers['customer_name']?></td>
				<td align="center">
				<?php
					$contact_name_exp = explode(";", $customers['contact_name']);
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
					$phone = explode(";", $customers['phone']);
					$count_phone = count($phone);
					if($count_phone<2){
						echo $phone[0];
					}
					else {
					for($i = 0; $i < $count_phone; $i++){?>
				
				<?=($i+1).". ".$phone[$i]."<br>"?>
			
					<?php }}?>
				</td>
				<td align="center">
				<?php
					$email = explode(";", $customers['email']);
					$count_email = count($email);
					if($count_email<2){
						echo $email[0];
					}
					else {
					for($i = 0; $i < $count_email; $i++){?>
				
				<?=($i+1).". ".$email[$i]."<br>"?>
			
					<?php }}?>
				</td>
				<td align="center"><?=$statusedit[$customers['status']];?></td>
				<?php if($_SESSION['userlevel']<=3){ ?>
				<td align="center" width="1">
					<a href='editcustomer.php?edit=<?= $customers['id_customer'] ?>' title = 'Изменить'><img src='images/edit.png' width='20' height='20'></a>
				</td>
				<?php }?>
				<td align="center" width="1">
					<a href='lookcustomer.php?look=<?= $customers['id_customer'] ?>' title = 'Посмотреть'><img src='images/lupa.png' width='20' height='20'></a>
				</td>
				
				<td align="center" width="1">
					<a href='showprojects.php?id_customer=<?= $customers['id_customer'] ?>' title = 'Проекты'><img src='images/projects.png' width='20' height='20'></a>
				</td>	
			</tr>
<?php }} else { ?>
				<tr>
					<td colspan="9" align="center" class="date">Не добавлено ни одного заказчика</td>
 				</tr>
<?php } ?>
		</table>
	</div>
	<script type="text/javascript" src='js/filter_showticket.js'></script>
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