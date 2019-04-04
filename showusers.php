<?php
require '/connection/config.php';
if($_SESSION['userlevel']==1)
{
require_once '/blocks/header.php';
require '/func/arrays.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Пользователи системы</title>
</head>
<body>
<?php
	$users = Show_Users($link);
?>
<div class="newuser"><a href='/newuser.php' ><button class="button-new">Добавить нового пользователя</button></a></div>
	<div class="showusers">
			<p class="breadcrumbs">Пользователи системы:</p>
				
				<table border="1" cellspacing="0">
					<thead>
						<tr class="hdr">
							<th>№</th>
							<th>Фамилия</th>
							<th>Имя</th>
							<th>Отчество</th>
							<th>E-mail</th>
							<th>Мобильный</th>
							<th>Логин</th>
							<th>Уровень</th>
							<th>Действие</th>
						</tr>
					</thead>
					<tbody>
				<?php foreach($users as $i => $users) { ?>
					<tr>
						<td align="center"><?=$i+1?></td>
						<td align="center"><?=$users['surname']?></td>
						<td align="center"><?=$users['name']?></td>
						<td align="center"><?=$users['th_name']?></td>
						<td align="center"><?=$users['email']?></td>
						<td align="center"><?=$users['mobile']?></td>
						<td align="center"><?=$users['login']?></td>
						<td align="center"><?=$user_level[$users['userlevel']-1]?></td>
						<td align="center"><a href='/edituser.php?edit=<?= $users['id_users'] ?>' title = 'Изменить'>
						<img src='/images/edit.png' width='20' height='20'></td>
					</tr>
				<?php }?>
			</tbody>
		</table>
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