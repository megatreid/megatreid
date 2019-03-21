<?php 
require '/connection/config.php';
require '/func/arrays.php';
	if(isset($_SESSION['user_id']))
	{
		$data = $_SESSION['user_id'];
		$users = Edit_User($link, $data);
		$surname = $users['surname'];
		$name = $users['name'];
		$th_name = $users['th_name'];
		$email = $users['email'];
		$mobile = $users['mobile'];
		$login = $users['login'];
		$userlevel = $users['userlevel'];
		$id_edit = $_SESSION['user_id'];
		$data_update = $_POST;
		$err=0;
		$surname_update = trim(filter_input(INPUT_POST, 'surname'));
		$name_update = trim(filter_input(INPUT_POST, 'name'));
		$th_name_update = trim(filter_input(INPUT_POST, 'th_name'));
		$email_update = trim(filter_input(INPUT_POST, 'email'));
		$mobile_update = trim(filter_input(INPUT_POST, 'mobile'));
		$loginuser_update = trim(filter_input(INPUT_POST, 'login'));
		$password_update = trim(filter_input(INPUT_POST, 'password'));
		$password_2_update = trim(filter_input(INPUT_POST, 'password_2'));
		$userlevel_update = trim(filter_input(INPUT_POST, 'userlevel'));
		if(isset($data_update['lk_edit_user']))
			{
			$errors=array();
			if($_SESSION['userlevel']==1) { 
				if(empty($surname_update))
				{
					$errors[] = 'Введите фамилию!';
				}
				if( mb_strlen($surname_update)>20 or mb_strlen($surname_update)<2)
				{
					$errors[] = 'Фамилия должна содержать не менее 2 и не более 20 символов!';
				if(empty($name_update))
				{
					$errors[] = 'Введите имя!';
				}
				if( mb_strlen($name_update)>20 or mb_strlen($name_update)<3)
				{
					echo mb_strlen($name_update);
					$errors[] = 'Имя должно содержать не менее 3 и не более 20 символов!';
				}
				if(empty($th_name_update))
				{
					$errors[] = 'Введите отчество!';
				}
				if( mb_strlen($th_name_update)>25 or mb_strlen($th_name_update)<3)
				{
					echo mb_strlen($th_name_update);
					$errors[] = 'Имя должно содержать не менее 3 и не более 25 символов!';
				}		
				if( empty($email_update))
				{
					$errors[] = 'Введите email!';
				}
				if( mb_strlen($email_update)>50 or mb_strlen($email_update)<5)
				{
					$errors[] = 'Адрес почты должен содержать не менее 5 и не более 50 символов!';
				}
				if( empty($mobile_update))
				{
					$errors[] = 'Введите номер мобильного телефона!';
				}
				if( mb_strlen($mobile_update)>20 or mb_strlen($mobile_update)<10)
				{
					$errors[] = 'Номер мобильного телефона должен содержать не менее 10 и не более 20 символов!';
				}		
				if(empty($loginuser_update)) //проверка на пустое значение поля ввода логина
				{
					$errors[] = 'Введите логин!';
				}
					if(mb_strlen($loginuser_update)>15 or mb_strlen($loginuser_update)<3)
				{
					$errors[] = 'Логин должен содержать не менее 3 и не более 15 символов! ';
				}
			}
				if(!empty($password_update))
				{
					if( mb_strlen($password_update)>25 or mb_strlen($password_update)<6)
						{
							$errors[] = 'Пароль должен содержать не менее 6 и не более 25 символов!';
						}
					if(empty($password_2_update))
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
			if($_SESSION['userlevel']==1) { 
				if( empty($userlevel_update))
					{
						$errors[] = 'Выберите уровень пользователя!';
					}
			}					
				if(($login) != $loginuser_update)
				{
					$is_login = Login_Exist($link, $loginuser_update); 
					if ($is_login == true)                      
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
				$updateuser = update_user ($link, $id_edit, $loginuser_update, $password, $surname_update, $name_update, $th_name_update, $email_update, $mobile_update, $userlevel_update); 
				if($updateuser)
				{
					?>		
						<script>
							setTimeout(function(){window.location.href = '/lk.php';}, 0);
						</script>
					<?php
				}
			}
			else
				{
					$err=1;
				}
		}
	}
	?>
	<?php require_once 'blocks/header.php'; ?>
	<!DOCTYPE html>
	<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Личный кабинет</title>
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
					<h2>Личный кабинет</h2>
					<?php if($err==1){?>
					<div class="error-message"><?=array_shift($errors)?></div>
					<?php }?>
					
						<form action="/lk.php" method="POST">
						<p style = "font-size: 8pt">Все поля являются обязательными</p>
							<table class="add-edit-user-table">
							<tr>
								<td>Фамилия:</td>
								<td><input name="surname" <?php if($_SESSION['userlevel']!=1) { echo "readonly"; }?> placeholder="Фамилия" type="text" value="<?= @$surname?>"/></td>
							</tr>
							<tr>
								<td>Имя:</td>
								<td><input name="name" <?php if($_SESSION['userlevel']!=1) { echo "readonly"; }?> placeholder="Имя" type="text" value="<?=@$name?>"/></td>
							</tr>
							<tr>
								<td>Отчество:</td>
								<td><input name="th_name" <?php if($_SESSION['userlevel']!=1) { echo "readonly"; }?> placeholder="Отчество" type="text" value="<?= @$th_name;?>"/></td>
							</tr>						
							<tr>
								<td>Email:</td>
								<td><input name="email" <?php if($_SESSION['userlevel']!=1) { echo "readonly"; }?> placeholder="abc@domain.com" type="text" pattern = "^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$" value="<?= @$email;?>"/></td>
							</tr>
							<tr title="Номер телефона должен быть записан в виде +7(901)234-56-78">
								<td>Мобильный:</td>
								<td><input name="mobile" <?php if($_SESSION['userlevel']!=1) { echo "readonly"; }?> id="phone_mask" title="Номер телефона должен быть записан в виде +7(901)234-56-78" placeholder="+7(901)234-56-78" type="text" value="<?php echo @$mobile;?>"/></td>
							</tr>
							<script>
								$(document).ready(function() {$("#phone_mask").mask("+7(999)999-99-99");});
							</script>
							<tr>
								<td>Логин:</td>
								<td><input name="login" <?php if($_SESSION['userlevel']!=1) { echo "readonly"; }?> placeholder="Логин" type="text" value="<?php echo @$login;?>"/></td>
							</tr>
							<tr>
								<td>Пароль:</td>
								<td><input name="password" placeholder="Пароль" type="password"/></td>
							</tr>
							<tr>
								<td>Подтвердите пароль:</td>
								<td><input name="password_2" placeholder="Подтвердите пароль" type="password"/></td>
							</tr>
							<tr>
							<td>Уровень пользователя:</td>
							<td>
							<?php if($_SESSION['userlevel']==1) { ?>
							<select name="userlevel">
								<option disabled selected>Выберите значение:</option>
								<?php for($i = 1; $i < 4; $i++) { ?>
									<option  value="<?= $i ?>" <?= ($i == $userlevel) ? 'selected' : ''?>><?= $user_level[$i-1] ?></option>
								<?php } ?>
							</select>
							<?php }else{?>
								<input hidden name="userlevel" readonly type="text" value="<?php echo @$userlevel;?>"/>
								<?= $user_level[$userlevel-1]?>
							<?php }?>
							</td>
							</tr>


							</table>
							<button class="button" name="lk_edit_user">Изменить данные</button>
							<!-- <p><a href="/" class="">На главную страницу</a></p> -->
						</form>
					</div>
				</div>

	</body>
<?php
	}
	else
	{
		header('Location: /');
}
?>