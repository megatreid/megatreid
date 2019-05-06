<?php
require '/connection/config.php';
require_once '/blocks/header.php';
$get_id_object = trim(filter_input(INPUT_GET, 'id_object', FILTER_SANITIZE_NUMBER_INT));
$get_id_ticket = trim(filter_input(INPUT_GET, 'id_ticket', FILTER_SANITIZE_NUMBER_INT));

$customers = Show_Customer_Active($link, '1');
/*
$objects = Edit_Object ($link, $get_id_object);
$city = Get_Geo ($link, $objects['city_id'], "city", "city_id");
$projects = Edit_Project ($link, $objects['id_project']);
$customers_edit = Edit_Customer ($link, $objects['id_customer']);
	
$id_object = $objects['id_object'];
$id_project = $projects['id_project'];
$object_full = $objects['shop_number'].". Адрес: ".$objects['address'];
$city_name = $objects['city_name'];
$project_name = $projects['projectname'];
$customer_name = $customers_edit['customer_name']; 
*/
$user_id = $_SESSION['user_id'];
$currentdatetime = date("Y-m-d H:i:s");
$data_update = $_POST;

$err= FALSE;

$id_customer = trim(filter_input(INPUT_POST, 'id_customer', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$id_project = trim(filter_input(INPUT_POST, 'id_project', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$city_id = trim(filter_input(INPUT_POST, 'city_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$id_object = trim(filter_input(INPUT_POST, 'id_object', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

if(isset($data_update['select_edit_object']))
{
	
		$errors=array();//массив сообшений ошибок

		if(empty($id_customer))
		{
			$errors[] = 'Выберите заказчика!';
		}

/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */	
		if(empty($id_project))
		{
			$errors[] = 'Выберите проект!';
		}

/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */
		if( empty($city_id))
		{
			$errors[] = 'Выберите город';
		}

/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */
		if( empty($id_object))
		{
			$errors[] = 'Выберите объект!';
		}


	if(empty($errors))
	{  

		$updateticket = Update_Ticket_Select($link, $get_id_ticket, $id_object, $user_id, $currentdatetime);
		if($updateticket)
		{
			?>		

				<script>
					setTimeout(function(){window.location.href = 'editticket.php?id_ticket=<?=$get_id_ticket;?>';}, 0);
				</script>
			<?php
		}
	}
	else
		{
			$err=TRUE;
		}
}	

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
			<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
		<?php }?>
		<form action="" method="POST">
					<table>
						<tr>
							<td class="rowt">Заказчик:</td>
							<td>
							<select name="id_customer" id="id_customer" class="StyleSelectBox">
								<option value="0">- выберите заказчика -</option>
								<?php foreach($customers as $i => $customer)  {?>
									<!-- <option  value="<?= $customer['id_customer']; ?>"<?=$customer['id_customer']==$objects['id_customer'] ? 'selected':''?>><?= $customer['customer_name']; ?></option> -->
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
							<p><input name="select_edit_object" type="submit" class="button-new" value="Выбрать объект"><input class="button-new" value="Отмена" type="button" onclick="location.href='editticket.php?id_ticket=<?=$get_id_ticket;?>'" /></p>
						</div>
					</form>
					
					</div>
	<!-- <div id="footer">&copy; ООО "МегаТрейд"</div> -->
	</div>

</body>
</html>