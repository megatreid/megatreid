<?php
require '/connection/config.php';
//require '/func/db.php';
$data = $_POST;
//$doSingup = trim(filter_input(INPUT_POST, 'do_singup'));
$err=0;
$loginuser = trim(filter_input(INPUT_POST, 'login'));
$name = trim(filter_input(INPUT_POST, 'name'));
$surname = trim(filter_input(INPUT_POST, 'surname'));
$email = trim(filter_input(INPUT_POST, 'email'));
//$email = htmlentities(mysql_real_escape_string($data['email']), ENT_QUOTES, 'UTF-8');
$password = trim(filter_input(INPUT_POST, 'password'));
$password_2 = trim(filter_input(INPUT_POST, 'password_2'));
if( isset($data['do_singup']))
	{
		$errors=array();//массив сообшений ошибок
	
		
		if(empty($loginuser)) //проверка на пустое значение поля ввода логина
		{
			$errors[] = 'Введите логин!';
		}
		if(mb_strlen($loginuser)>20 or mb_strlen($loginuser)<3) //проверка на пустое значение поля ввода логина
		{
			
			$errors[] = 'Логин должен содержать не менее 3 и не более 20 символов! ';
		}
		
		if(empty($name))//проверка на пустое значение поля ввода имени
		{
			$errors[] = 'Введите имя!';
		}
		if( mb_strlen($name)>20 or mb_strlen($name)<3) //проверка на пустое значение поля ввода логина
		{
			echo mb_strlen($name);
			$errors[] = 'Имя должно содержать не менее 3 и не более 20 символов!';
		}
		
		if(empty($surname))//проверка на пустое значение поля ввода gender
		{
			$errors[] = 'Введите фамилию!';
		}
		if( mb_strlen($surname)>20 or mb_strlen($surname)<2) //проверка на пустое значение поля ввода логина
		{
			$errors[] = 'Фамилия должна содержать не менее 2 и не более 20 символов!';
		}
		
		
		if( empty($email))//проверка на пустое значение поля ввода email
		{
			$errors[] = 'Введите email!';
		}
		if( mb_strlen($email)>50 or mb_strlen($email)<5) //проверка на пустое значение поля ввода логина
		{
			$errors[] = 'Адрес почты должен содержать не менее 5 и не более 50 символов!';
		}		
		
		if( empty($password))//проверка на пустое значение поля ввода пароля
		{
			$errors[] = 'Введите пароль!';
		}
		if( mb_strlen($password)>30 or mb_strlen($password)<6) //проверка на пустое значение поля ввода логина
		{
			$errors[] = 'Пароль должен содержать не менее 6 и не более 30 символов!';
		}
		
		if( $password_2 != $password)//проверка на совпадение с ранее введенным паролем
		{
			$errors[] = 'Пароль не совпадает!';
		}
		
		//$link = mysqli_connect($host, $login, $pswrd, $db_name) or die("Ошибка " . mysqli_error($link)); 
		//$loginuser = htmlentities(mysqli_real_escape_string($link, $data['login']), ENT_QUOTES, 'UTF-8');
		//$name = htmlentities(mysqli_real_escape_string($link, $data['name']), ENT_QUOTES, 'UTF-8');
		//$surname = htmlentities(mysqli_real_escape_string($link, $data['surname']), ENT_QUOTES, 'UTF-8');
		//$gender = htmlentities(mysqli_real_escape_string($link, $data['gender']), ENT_QUOTES, 'UTF-8');
		//$email = htmlentities(mysqli_real_escape_string($link, $data['email']), ENT_QUOTES, 'UTF-8');
		$password = md5($password);
         
        $is_login = Login_Exist($link, $loginuser); // Проверка логина и пароля через пользовательскую функцию
        if ($is_login == true)                      // Если строка с логином найдена, возвращается 1 и выдаётся ошибка
                $errors[] = 'Такой логин уже существует!';
        else
             $is_email = Email_Exist($link, $email);

        if ($is_email == true)
                $errors[] = 'Такой email уже существует!';  
	if(empty($errors)){                                                                                  
		//"закидывание" данных в файл сессии
		$_SESSION['email']=$email;
		$_SESSION['fio']=$name." ".$surname;
		$_SESSION['login']=$loginuser;
		$_SESSION['name']=$name;
		$_SESSION['surname']=$surname;
		$_SESSION['password']=$password;
		//подключения модуля отправки email с кодом подтверждения
		//require '/mailto.php';
		//перенаправление на страницу проверки кода
		header('Location: reg.php');
		
    }
	else
		{
			$err=1;
			//echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
		}
	}
?>
<?php require_once 'blocks/header.php'; ?>
