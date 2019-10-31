<?php
$current_month = date('n');
$tickets = Show_Tickets($link, "", "", "", "","");
if($tickets){
$count_tickets = count($tickets);
}else {$count_tickets = 0;}

$tickets_all = Show_Tickets($link, "", "", "", $current_month,"");
if($tickets_all){
$count_tickets_all = count($tickets_all);
}else {$count_tickets_all = 0;}

$tickets_inwork = Show_Tickets($link, "0", "", "", "","");
if($tickets_inwork){
$count_tickets_work = count($tickets_inwork);
}else{$count_tickets_work = 0;}

$tickets_insogl = Show_Tickets($link, "2", "", "", "","");
if($tickets_insogl){
$count_tickets_sogl = count($tickets_insogl);	
}else{$count_tickets_sogl = 0;}

$tickets_closed_current = Show_Tickets($link, "1", "", "", $current_month,"");
if($tickets_closed_current){
$count_tickets_closed_current = count($tickets_closed_current);	
}else{$count_tickets_closed_current = 0;}

$tickets_closed = Show_Tickets($link, "1", "", "", "","");
if($tickets_closed){
$count_tickets_closed = count($tickets_closed);	
}else{$count_tickets_closed = 0;}
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
					<td align="right">Всего заявок в системе:</td>
					<td><b><?=$count_tickets;?></b></td>
				</tr>				
				<tr>
					<td align="right">из них открыто в текущем месяце:</td>
					<td><b><?=$count_tickets_all;?></b></td>
				</tr>
				<tr>
					<td align="right">Всего заявок "В работе":</td>
					<td><b><?=$count_tickets_work;?></b></td>
				</tr>
				<tr>
					<td align="right">Всего заявок "На согласовании":</td>
					<td><b><?=$count_tickets_sogl;?></b></td>
				</tr>
				<tr>
					<td align="right">Всего закрытых заявок:</td>
					<td><b><?=$count_tickets_closed;?></b></td>
				</tr>
				<tr>
					<td align="right">Всего закрытых заявок в текущем месяце:</td>
					<td><b><?=$count_tickets_closed_current;?></b></td>
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
