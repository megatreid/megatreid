<?php
require '/connection/config.php';
if($_SESSION['userlevel']==1)
{
require_once '/blocks/header.php';?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Заказчики</title>
</head>
<body>
<?php
	$customer = Show_Customer($link);
?>
		<div class="showusers">
			<caption><h3>Заказчики:</h3></caption>

		<?php if($customer){ ?>
		<select onchange="with (this) if (selectedIndex) location = options [selectedIndex].value"/>
		<option>Выберите из списка:</option>
		<?php foreach($customer as $i => $customers) { 
		?>
			
			<!-- <a href="showprojects.php?id_customer=<?=$customers['id_customer']?>"><?=$i+1?><?=". ".$customers['customer_name']?></a><br> -->
			
				
				<option  value="showprojects.php?id_customer=<?=$customers['id_customer']?>"><?=$i+1?><?=". ".$customers['customer_name']?></option>

			
			
		<?php } ?>
		</select>
		
		<?php } ?>
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