<?php 
require '/connection/config.php';
require '/func/arrays.php';
if($_SESSION['userlevel']==1)
{
//require_once '/blocks/header.php';
	
	if(isset($_GET['edit']))
	{
		$data = $_GET['edit'];
		$_SESSION['id_edit'] = $data;
		$users = Edit_User($link, $data);
		$surname = $users['surname'];
		$name = $users['name'];
		$th_name = $users['th_name'];
		$email = $users['email'];
		$mobile = $users['mobile'];
		$login = $users['login'];
		$userlevel = $users['userlevel'];
		$id_edit = $_SESSION['id_edit'];
	}

	$data_update = $_POST;

	$err=0;
	//$id_user = trim(filter_input(INPUT_POST, 'id_users'));
	$surname_update = trim(filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$name_update = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$th_name_update = trim(filter_input(INPUT_POST, 'th_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$email_update = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$mobile_update = trim(filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$loginuser_update = trim(filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$password_update = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$password_2_update = trim(filter_input(INPUT_POST, 'password_2', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$userlevel_update = trim(filter_input(INPUT_POST, 'userlevel', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	if(isset($data_update['edit_user']))
		{
			
				$errors=array();//массив сообшений ошибок
				$users = Edit_User($link, $_SESSION['id_edit']);
				if(empty($surname_update))//проверка на пустое значение поля ввода gender
				{
					$errors[] = 'Введите фамилию!';
				}
				if( mb_strlen($surname_update)>50 or mb_strlen($surname_update)<2)
				{
					$errors[] = 'Фамилия должна содержать не менее 2 и не более 50 символов!';
				}	
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */	
				if(empty($name_update))//проверка на пустое значение поля ввода имени
				{
					$errors[] = 'Введите имя!';
				}
				if( mb_strlen($name_update)>50 or mb_strlen($name_update)<3)
				{
					echo mb_strlen($name_update);
					$errors[] = 'Имя должно содержать не менее 3 и не более 50 символов!';
				}
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */		
				if(empty($th_name_update))//проверка на пустое значение поля ввода имени
				{
					$errors[] = 'Введите отчество!';
				}
				if( mb_strlen($th_name_update)>50 or mb_strlen($th_name_update)<3)
				{
					echo mb_strlen($th_name_update);
					$errors[] = 'Имя должно содержать не менее 3 и не более 50 символов!';
				}		
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */
				if( empty($email_update))//проверка на пустое значение поля ввода email
				{
					$errors[] = 'Введите email!';
				}
				if( mb_strlen($email_update)>50 or mb_strlen($email_update)<5)
				{
					$errors[] = 'Адрес почты должен содержать не менее 5 и не более 50 символов!';
				}
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */
				if( empty($mobile_update))//проверка на пустое значение поля ввода email
				{
					$errors[] = 'Введите номер мобильного телефона!';
				}
				if( mb_strlen($mobile_update)>50 or mb_strlen($mobile_update)<10)
				{
					$errors[] = 'Номер мобильного телефона должен содержать не менее 10 и не более 50 символов!';
				}		
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */		
				if(empty($loginuser_update)) //проверка на пустое значение поля ввода логина
				{
					$errors[] = 'Введите логин!';
				}
					if(mb_strlen($loginuser_update)>50 or mb_strlen($loginuser_update)<3)
				{
					$errors[] = 'Логин должен содержать не менее 3 и не более 50 символов! ';
				}
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */
				if(!empty($password_update))//проверка на пустое значение поля ввода пароля
				{
					if( mb_strlen($password_update)>25 or mb_strlen($password_update)<6)
						{
							$errors[] = 'Пароль должен содержать не менее 6 и не более 25 символов!';
						}
					if(empty($password_2_update))//проверка на пустое значение поля ввода пароля
						{
							$errors[] = 'Подтвердите пароль!';
						}
					if( $password_2_update != $password_update)
						{
							$errors[] = 'Пароль не совпадает!';
						}

					$password = md5($password_update);				
				}
				else
				{
					
					$password = $users['pass'];
				}

				if( empty($userlevel_update))//проверка на пустое значение поля ввода пароля
					{
						$errors[] = 'Выберите уровень пользователя!';
					}		
		/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */
				if(($users['login']) != $loginuser_update)
				{
					$is_login = Login_Exist($link, $loginuser_update); // Проверка логина и пароля через пользовательскую функцию
					if ($is_login == true)                      // Если строка с логином найдена, возвращается 1 и выдаётся ошибка
						$errors[] = 'Логин '.$loginuser_update.' уже существует!';
				}
				/*if($email != $email_update)
				{
					$is_email = Email_Exist($link, $email_update);
					if ($is_email == true)
						$errors[] = 'E-mail '.$email_update.' уже существует!';  
				}*/
			if(empty($errors))
			{  
				$id_user = $_SESSION['id_edit'];
				$updateuser = update_user ($link, $id_user, $loginuser_update, $password, $surname_update, $name_update, $th_name_update, $email_update, $mobile_update, $userlevel_update);
				if($updateuser)
				{
					unset($_SESSION['id_edit']);
					?>		
						<script>
							setTimeout(function(){window.location.href = '/showusers.php';}, 0);
						</script>
					<?php
				}
			}
			else
				{
					$err=1;
				}
		}
		if(isset($_POST['delete_user']) AND ($_SESSION['userlevel']==1))
		{
			$deleteuser = Delete_User($link, $id_edit);
			if($deleteuser)
			{
				unset($_SESSION['id_edit']);
				?>
					<script>
						setTimeout(function() {window.location.href = '/showusers.php';}, 0);
					</script>
				<?php		
			}
		}
	?>
<?php require_once 'blocks/header.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Редактирование данных пользователя</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script>
		$(".phone_mask").mask("+7(999)999-99-99");
	</script>
</head>
<body>
	<div class="showany">
		
		<div class="reg_sel_object">
		<h2>Редактирование данных пользователя:</h2>
		<?php if($err==1){?>
			<div class="error-message"><?=array_shift($errors)?></div>
		<?php }?>
		<form action="/edituser.php?edit=<?= $_SESSION['id_edit'];?>" method="POST">
			<p style = "font-size: 8pt">Все поля являются обязательными</p>
				<!--<input type="hidden" name="user_id" value="<?= $data ?>"> -->
			
			<table class="add-edit-user-table">
				<tr>
					<td class="rowt"><label for="surname">Фамилия:</label></td>
					<td><input id="surname" name="surname" placeholder="Фамилия" required type="text" value="<?php echo @$surname;?>"/></td>
				</tr>
				<tr>
					<td class="rowt"><label for="name">Имя:</label></td>
					<td><input id="name" name="name" placeholder="Имя" type="text" required value="<?php echo @$name;?>"/></td>
				</tr>
				<tr>
					<td class="rowt"><label for="th_name">Отчество:</label></td>
					<td><input id="th_name" name="th_name" placeholder="Отчество" required type="text" value="<?php echo @$th_name;?>"/></td>
				</tr>						
				<tr>
					<td class="rowt"><label for="email">Email:</label></td>
					<td><input id="email" name="email" placeholder="abc@domain.com" required type="text" pattern = "^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$" value="<?php echo @$email;?>"/></td>
				</tr>
				<tr>
					<td class="rowt"><label for="mobile">Мобильный:</label></td>
					<td><input name="mobile" id="phone_mask" placeholder="7(901)234-56-78" required type="text" value="<?php echo @$mobile;?>"/></td>
				</tr>
				<script>
					$("#phone_mask").mask("+7(999)999-99-99");
				</script>
				<tr>
					<td class="rowt"><label for="login">Логин:</label></td>
					<td><input id="login" name="login" placeholder="Логин" type="text" required value="<?php echo @$login;?>"/></td>
				</tr>
				<tr>
					<td class="rowt"><label for="password">Пароль:</label></td>
					<td><input id="password" name="password" placeholder="Пароль" type="password"/></td>
				</tr>
				<tr>
					<td class="rowt"><label for="password_2">Подтвердите пароль:</label></td>
					<td><input id="password_2" name="password_2" placeholder="Подтвердите пароль" type="password"/></td>
				</tr>
				<tr>
				<td class="rowt"><label for="userlevel">Уровень пользователя:</label></td>
				
				<td>
				<select name="userlevel" id="userlevel" >
					<option disabled selected>Выберите значение:</option>
					<?php for($i = 1; $i < 5; $i++) { ?>
						<option  value="<?= $i ?>" <?= ($i == $userlevel) ? 'selected' : ''?>><?= $user_level[$i-1] ?></option>
					<?php } ?>

				</select>
				</td>
				
				</tr>
				</table>
			
			<input class="button" value="Сохранить" type="submit" name="edit_user"/>
				<input class="button" value="К списку пользователей" type="button" onclick="location.href='showusers.php'"/>
				
	
				<?php
				if($_SESSION['user_id'] != $id_edit)
				{ ?>	
			

				<a href="#delete_user" class="button-delete">Удалить пользователя</a>
					<div id="delete_user" class="modalDialog">
						<div>
							<!-- <a href="#close"  title="Закрыть" class="close">X</a> -->
						<h2>Удаление пользователя</h2>
						<p>Вы уверены, что хотите удалить этого пользователя?</p>
						<p>Это может привести к потери данных в других разделах системы!</p>
						<input class="button-delete" value="Да" name="delete_user" type="submit"/>
						<a href="#close"  title="Отменить" class="button">Нет</a>

						</div>
					</div>
			

				<?php }?>
		
			
		</form>
		</div>
	</div>
	<br>
<div id="footer">&copy; ООО "МегаТрейд"</div>
</body>
<?php
}
else
{
	header('Location: /');
}
?>