<?php
$current_month = date('n');
$tickets_all = Show_Tickets($link, "", "", $current_month,"");
if($tickets_all){
$count_tickets_all = count($tickets_all);
}else {$count_tickets_all = 0;}

$tickets_inwork = Show_Tickets($link, "0", "", $current_month,"");
if($tickets_inwork){
$count_tickets_work = count($tickets_inwork);
}else{$count_tickets_work = 0;}

$tickets_insogl = Show_Tickets($link, "2", "", $current_month,"");
if($tickets_insogl){
$count_tickets_sogl = count($tickets_insogl);	
}else{$count_tickets_sogl = 0;}

?>
<!DOCTYPE HTML public "-//W3C//DTD HTML 3.2//EN">
<html lang="ru">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div class="main">
			<h1>Статистика</h1>
			<table border="0" cellspacing="0">
				
				
				
				<tr>
					<td colspan="2" align="center">
					<div class = "date">
					<?php
					$months = array( 1 => 'января' , 'февраля' , 'марта' , 'апреля' , 'мая' , 'июня' , 'июля' , 'августа' , 'сентября' , 'октября' , 'ноября' , 'декабря' );

					echo "Сегодня: ".date( 'd ' . $months[date( 'n' )] . ' Y' )." года";?>
					</div>
					</td>
				</tr>
				<tr>
					<td align="right">Общее количество заявок в текущем месяце:</td>
					<td><?=$count_tickets_all;?></td>
				</tr>
				<tr>
					<td align="right">Количество заявок "В работе":</td>
					<td><?=$count_tickets_work;?></td>
				</tr>
				<tr>
					<td align="right">Количество заявок "На согласовании":</td>
					<td><?=$count_tickets_sogl;?></td>
				</tr>
				<!--
				<tr>
					<td align="right">Общая задолженность контрагентам:</td>
					<td>______</td>
				</tr>	
				<tr>
					<td align="right">В том числе:</td>
					<td></td>
				</tr>	
				<tr>
					<td align="right">Задолженность по безналичному расчету:</td>
					<td>______</td>
				</tr>	
				<tr>
					<td align="right">Задолженность по наличному расчету:</td>
					<td>______</td>
				</tr>	 -->
			</table>
		</div>
		
	</body>
</html>
