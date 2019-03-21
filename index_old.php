<?php
require_once 'connection/connection.php';//файл с логином и паролем для авторизации
header('Content-Type: text/html; charset=utf-8');
$user_agent = $_SERVER["HTTP_USER_AGENT"];
if (strpos($user_agent, "Firefox") !== false) $browser = "Firefox";
elseif (strpos($user_agent, "Opera") !== false) $browser = "Opera";
elseif (strpos($user_agent, "Chrome") !== false) $browser = "Chrome";
elseif (strpos($user_agent, "MSIE") !== false) $browser = "Internet Explorer";
elseif (strpos($user_agent, "Safari") !== false) $browser = "Safari";
else $browser = "Неизвестный";
if($browser == "Internet Explorer")
	{
		echo '<font size="1" color="red" face="Arial">Вы используете браузер Internet Explorer, в связи с этим мы не гарантируем правильность отображения страниц данного справочника</font>';
	}
echo "<title>Телефонный справочник ПО \"ЦЭС\"</title>
<link rel='shortcut icon' href='/images/favicon.ico' type='image/x-icon'>";
if(isset($_SESSION['adminkey']))
{
	if(isset($_POST['logout']))
	{
		unset($_SESSION['adminkey']);
		$adminkey=0;
		session_destroy();
		goto login;
	}
	$adminkey=$_SESSION['adminkey'];
	if($_SESSION['adminkey']==1)
	{
		logout:
		echo "
		<style type='text/css'>
			#w {
			    width: 50px;
				font-size:10px;}
		</style>
		<form action='index.php' method='POST'>
			<input type='hidden' name='logout'></p>
			<input type='submit' id='w' value='Выйти'>
		</form>";
		echo "Администрирование:
		<ul>
			<li><a style='font-size: 80%; text-align:center; font-family: Tahoma; color: gray' style href='admin_post.php'> Должности </a></li>
			<li><a style='font-size: 80%; text-align:center; font-family: Tahoma; color: gray' style href='admin_department.php'> Отделы </a></<li>
			<li><a style='font-size: 80%; text-align:center; font-family: Tahoma; color: gray' style href='admin_stat.php'> Статистика </a></<li>
		</ul>
		";
	}
}
else
{
	if(isset($_POST['login']) && isset($_POST['password']))
	{
		if($_POST['login']==$login && $_POST['password']==$pass)
		{	
			$_SESSION['adminkey']=1;
			$adminkey=1;
			goto logout;
		}
		else
		{	
			echo"<script>alert(\"Неправильный логин или пароль!\");</script>";
		}
	}
	if($_SESSION['adminkey']!=1)
	{
		login:
		echo "
		<style type='text/css'>
		#w
			{		
				width: 50px;
				font-size:10px;
			}
		</style>
		<form  action='index.php' method='POST'>
	        <input type='text' id='w' name='login' required='' placeholder='Login'>
			<input type='password' id='w' name='password' required='' placeholder='Password'>
			<input type='submit' id='w' name='ln' value='Войти'>
		</form>";
	}
}
echo "<body bgcolor=#F5F5F5>";





//Сортировка по выбранным полям
$keys=array('fio','department'); //Массив значений для переменной $key
if (isset($_GET['sort']))
	{
		$key=$_GET['sort'];
	}
else
	{
		$key='id';
	}
echo "<style>
	#pic
		{
			position: absolute;
			top: 1px;
			left: 180px;
		}
		</style>";
	echo "<style type='text/css'>
	#search
		{
			position: absolute;
			top: 10px;
		}
		</style>
<form id='search' method='post'>
    <input type='search' name='poisk' placeholder='Поиск по базе'>
    <button type='submit'>Найти</button> 
</form>";
if(isset($_POST['poisk']))
{
	$search = $_POST['poisk'];
	$post_exist = post_exist($link, $search);
	$department_exist = department_exist($link, $search);
	if(isset($post_exist))
	{
		$post_id = $post_exist['id_post'];
	}

	if(isset($department_exist))
	{
		$department_id = $department_exist['id_dep'];
	}
	$query_new="SELECT * FROM `phones` WHERE `fio` LIKE '%$search%' OR `post` LIKE '$post_id' OR `department` LIKE '$department_id' 
	OR `intnumber` LIKE '%$search%' OR `extnumber` LIKE '%$search%' OR `mobile` LIKE '%$search%'";
}
else
{
	$query_new ="SELECT * FROM `phones` ORDER BY $key";
}
//Работа с БД
$query = $query_new;
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
if($result)
{
    $rows = mysqli_num_rows($result); // количество полученных строк
	echo "<style type='text/css'>
		table {
			margin: auto;
			border-collapse: collapse; 
			line-height: 1;
			position: relative;
		}
		a { 
			text-decoration: none;
			color: white
		} 
		A:visited {color: #white}
		a:hover{color:#ffc821;
		}
		tr:hover{color:#black;
		background: #ffc821;}
		A:active {color:yellow}
		th {
			background:#013A8A;
			border: 1px solid black;
			font-family: Sans;
			color: white;
		}
		td {
			border: 1px solid black;
			border-spacing: 10px 20px 4px 5px;
		}
		#search
		{
			position: absolute;
			top: 10px;
			right: 10px;
		}
	</style>
	<table><caption><h2>Телефонный справочник ПО \"ЦЭС\"</h2></caption>
		<tr>
			<th rowspan=2>№</th>
		<form method='GET'> 
			<th rowspan=2><a href='index.php?sort=".$keys[0]."' title='Сортировка по ФИО'>Ф.И.О.*</th>
			<th rowspan=2>Должность</th>
			<th rowspan=2><a href='index.php?sort=".$keys[1]."' title='Сортировка по отделам'>Отдел*</th>
		</form>
			<th colspan=3>Номер телефона</th>";
	if($adminkey==1) //В режиме администрирования отображается дополнительное поле в таблице
		{
			echo "<th rowspan=2 colspan=2>Действие</th>
			</tr>";
		}
	else //В режиме просмотра дополнительное поле в таблице не отображается 
		{
			echo "</tr>";
		}
	echo "
		</tr>
		<tr>
			<th>внутр.</th>
			<th>городской</th>
			<th>сотовый</th>
		</tr>";
   $id_number=0; //Нумерация строк в таблице
   for ($i = 0 ; $i < $rows ; ++$i)
    {
		
		if($i%2==1)
		{
			$col="#FAFAD2"; //Цвет четных строк в таблице
		}
		else $col="#F0F8FF";//Цвет нечетных строк в таблице
        $row = mysqli_fetch_row($result);
		$one_post = show_one_post($link, $row[2]);
		
		$ppp = array();
		if(empty($one_post)){
			$ppp[0] = "Должность не указана";
		}
		else{
			$ppp[0] = $one_post[post];
		}
		
		$one_department = show_one_department($link, $row[3]);
		$ddd = array();
		if(empty($one_department)){
			$ddd[0] = "Отдел не указан";
		}
		else{
			$ddd[0] = $one_department[department];
		}
		++$id_number;
        echo "<tr align='left' bgcolor=$col>";
		echo "<td align='center'>$id_number</td>";
		echo "<td>$row[1]</td>"; //Вывод ФИО абонента
		echo "<td>$ppp[0]</td>"; //Вывод должности абонента
		echo "<td align='center'>$ddd[0]</td>"; //Вывод названия отдела
		echo "<td align='center'>$row[4]</td>"; //Вывод внутр.тлф.
		echo "<td align='center'>$row[5]</td>"; //Вывод город.тлф.
		echo "<td align='center'>$row[6]</td>"; //Вывод сот.тлф.
		if($adminkey==1)
		{ //В режиме администрирования выводятся дополнительные функциональные возможности: редактирование и удаление
			$colspan=9;			
			echo "<td align='center'><a href='edit.php?id=".$row[0]."' title='Редактировать'><img src='images/edit-icon.png' width='20' height='20'></a></td> 
			<td align='center'><a href='delete.php?id=".$row[0]."'title='Удалить'><img src='images/delete-icon.png' width='20' height='20'></a>
			</tr>";
		}
		else
		{
			$colspan=7;				
		}		
        echo "</tr>";
    }
	if($adminkey==1)//В режиме администрирования выводятся дополнительные функциональные возможности: добавление новых абонентов
	{
	if($rows<1)
	{
		$colspan=8;
	}
		echo "<td colspan=$colspan align='center'>";
		echo "<a href='create.php'title='Добавить абонента'><img src='images/adduser.png' width='25' height='25'></a>";
	}
	echo "</td>";
    echo "</table>";
	echo '<p style="font-size: 60%; text-align:center; font-family: Tahoma; color: #gray">* Звездочкой помечены столбцы, которые можно сортировать по алфавиту (А-я)</p>';
    // очищаем результат
    mysqli_free_result($result);
}
mysqli_close($link); //Закрываем доступ в БД
exit;
?>