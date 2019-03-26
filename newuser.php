<?php
require '/connection/config.php';
if($_SESSION['userlevel']==1)
{
require_once 'blocks/header.php'; 
//require '/func/db.php';
$data = $_POST;
//$doSingup = trim(filter_input(INPUT_POST, 'do_singup'));
$err=0;

$surname = trim(filter_input(INPUT_POST, 'surname'));
$name = trim(filter_input(INPUT_POST, 'name'));
$th_name = trim(filter_input(INPUT_POST, 'th_name'));
$email = trim(filter_input(INPUT_POST, 'email'));
$mobile = trim(filter_input(INPUT_POST, 'mobile'));
//$email = htmlentities(mysql_real_escape_string($data['email']), ENT_QUOTES, 'UTF-8');
$loginuser = trim(filter_input(INPUT_POST, 'login'));
$password = trim(filter_input(INPUT_POST, 'password'));
$password_2 = trim(filter_input(INPUT_POST, 'password_2'));
$level = trim(filter_input(INPUT_POST, 'level'));
if( isset($data['new_user']))
	{
		$errors=array();//массив сообшений ошибок
		if(empty($surname))//проверка на пустое значение поля ввода gender
		{
			$errors[] = 'Введите фамилию!';
		}
		if( mb_strlen($surname)>20 or mb_strlen($surname)<2)
		{
			$errors[] = 'Фамилия должна содержать не менее 2 и не более 20 символов!';
		}	
/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */	
		if(empty($name))//проверка на пустое значение поля ввода имени
		{
			$errors[] = 'Введите имя!';
		}
		if( mb_strlen($name)>20 or mb_strlen($name)<3)
		{
			echo mb_strlen($name);
			$errors[] = 'Имя должно содержать не менее 3 и не более 20 символов!';
		}
/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */		
		if(empty($th_name))//проверка на пустое значение поля ввода имени
		{
			$errors[] = 'Введите отчество!';
		}
		if( mb_strlen($th_name)>25 or mb_strlen($th_name)<3)
		{
			echo mb_strlen($th_name);
			$errors[] = 'Имя должно содержать не менее 3 и не более 25 символов!';
		}		
/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */
		if( empty($email))//проверка на пустое значение поля ввода email
		{
			$errors[] = 'Введите email!';
		}
		if( mb_strlen($email)>50 or mb_strlen($email)<5)
		{
			$errors[] = 'Адрес почты должен содержать не менее 5 и не более 50 символов!';
		}
/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */
		if( empty($mobile))//проверка на пустое значение поля ввода email
		{
			$errors[] = 'Введите номер мобильного телефона!';
		}
		if( mb_strlen($mobile)>20 or mb_strlen($mobile)<10)
		{
			$errors[] = 'Номер мобильного телефона должен содержать не менее 10 и не более 20 символов!';
		}		
/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */		
		if(empty($loginuser)) //проверка на пустое значение поля ввода логина
		{
			$errors[] = 'Введите логин!';
		}
			if(mb_strlen($loginuser)>15 or mb_strlen($loginuser)<3)
		{
			$errors[] = 'Логин должен содержать не менее 3 и не более 15 символов! ';
		}
/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */
		if( empty($password))//проверка на пустое значение поля ввода пароля
		{
			$errors[] = 'Введите пароль!';
		}
		
		if( mb_strlen($password)>25 or mb_strlen($password)<6)
		{
			$errors[] = 'Пароль должен содержать не менее 6 и не более 25 символов!';
		}
		
		if(empty($password_2))//проверка на пустое значение поля ввода пароля
		{
			$errors[] = 'Подтвердите пароль!';
		}
		
		if( $password_2 != $password)
		{
			$errors[] = 'Пароль не совпадает!';
		}
		if( empty($level))//проверка на пустое значение поля ввода пароля
		{
			$errors[] = 'Выберите уровень пользователя!';
		}		
/* # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # */

        $is_login = Login_Exist($link, $loginuser); // Проверка логина и пароля через пользовательскую функцию
        if ($is_login == true)                      // Если строка с логином найдена, возвращается 1 и выдаётся ошибка
                $errors[] = 'Такой логин уже существует!';
        /*else
             $is_email = Email_Exist($link, $email);

        if ($is_email == true)
                $errors[] = 'Такой email уже существует!';  */
	if(empty($errors)){  
		$password = md5($password);
		//"закидывание" данных в файл сессии
		$_SESSION['email']=$email;
		$_SESSION['mobile']=$mobile;
		$_SESSION['login']=$loginuser;
		$_SESSION['name']=$name;
		$_SESSION['surname']=$surname;
		$_SESSION['th_name']=$th_name;
		$_SESSION['password']=$password;
		$result = Add_User ($link, $loginuser, $password, $surname, $name, $th_name, $email, $mobile, $level); // Добавление юзера 
		?>		
		<script>
			setTimeout(function() {window.location.href = '/showusers.php';}, 0);
		</script>	
		<?php		
    }
	else
		{
			$err=1;
			//echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
		}
	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Регистрация нового пользователя</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script>
		//$(".phone_mask").mask("+7(999)999-99-99");
	</script>
</head>
<body>
	<div class="showany">
	<br>
		<div class="reg_sel_object">
				<h2>Регистрация нового пользователя:</h2>
				<?php if($err==1){?>
				<div class="error-message"><?=array_shift($errors)?></div>
				<?php }?>
				
					<form action="" method="POST">
					<p style = "font-size: 8pt">Все поля являются обязательными</p>
						<table>
						<tr>
							<td class="rowt"><label for="surname">Фамилия:</label></td>
							<td><input id="surname" name="surname" placeholder="Фамилия" type="text" value="<?php echo @$data['surname'];?>"/></td>
						</tr>
						<tr>
							<td class="rowt"><label for="name">Имя:</label></td>
							<td><input id="name" name="name" placeholder="Имя" type="text" value="<?php echo @$data['name'];?>"/></td>
						</tr>
						<tr>
							<td class="rowt"><label for="th_name">Отчество:</label></td><td><input id="th_name" name="th_name" placeholder="Отчество" type="text" value="<?php echo @$data['th_name'];?>"/></td>
						</tr>						
						<tr>
							<td class="rowt"><label for="email">Email:</label></td><td><input id="email" name="email" placeholder="abc@domain.com" type="text" pattern = "^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$" value="<?php echo @$data['email'];?>"/></td>
						</tr>
						<tr>
							<td class="rowt"><label for="phone_mask">Мобильный:</label></td><td><input id="phone_mask" name="mobile" placeholder="+7(901)234-56-78" type="text" value="<?php echo @$data['mobile'];?>"/></td>
						</tr>
						<script>
							$(document).ready(function() {
							$("#phone_mask").mask("+7(999)999-99-99");
							});
						</script>
						<tr>
							<td class="rowt" for="login"><label for="login">Логин:</label></td><td><input id="login" name="login" placeholder="Логин" type="text" value="<?php echo @$data['login'];?>"/></td>
						</tr>
						<tr>
							<td class="rowt"><label for="password">Пароль:</label></td><td><input id="password" name="password" placeholder="Пароль" type="password"/></td>
						</tr>
						<tr>
							<td class="rowt"><label for="password_2">Подтвердите пароль:</label></td><td><input id="password_2" name="password_2" placeholder="Подтвердите пароль" type="password"/></td>
						</tr>
						<tr>
						<td class="rowt"><label for="level">Уровень пользователя:</label></td>
						<td>
						<!--<select name="userlevel"> -->
						<select name="level" id="level">
							<option disabled selected>Выберите значение:</option>
							<option value="1">Администратор</option>
							<option value="2">Руководитель проекта</option>
							<option value="3">Менеджер</option>
							<option value="4">Инженер</option>
						</select>
					</td>
						<tr>
						</table>
						<div>
							<p><button name="new_user">Зарегистрировать</button></p>
					</div>
				</form>
		<a href="/showusers.php">К списку пользователей</a>
		</div>
		
	</div>
	<br>
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