<?php
require '/connection/config.php';
$wwwlink=$_SERVER['HTTP_REFERER'];
$_SESSION['www']=$wwwlink;
$data=$_POST;
if( isset($data['do_login']))
	{
		//$_SESSION['count']=0;
		//$count = $_SESSION['count'];
		//$link = mysqli_connect($host, $login, $pswrd, $db_name) or die("Ошибка " . mysqli_error($link)); 
		$loginuser = trim(filter_input(INPUT_POST, 'login'));
		//$loginuser = htmlentities(mysqli_real_escape_string($link, $data['login']), ENT_QUOTES, 'UTF-8');
		$password = md5($data['userpassword']);
		$checkpassword=mysqli_query($link, "SELECT * FROM users WHERE pass='$password' AND login='$loginuser'") or die("Ошибка " . mysqli_error($link));	
		$results=mysqli_num_rows($checkpassword);
		if($results!=0)
		{
			$username=mysqli_query($link, "SELECT * FROM users WHERE login='$loginuser'") or die("Ошибка " . mysqli_error($link));
			
			if($username) 
			{
				$row = mysqli_fetch_row($username);
				$user_id = $row[0];
				$login = $row[1];
				//$surname = $row[3];
				$name = $row[4];
				$th_name = $row[5];
				//$email = $row[6];
				//$mobile = $row[7];
				$userlevel=$row[8];

				$_SESSION['user_id'] = $user_id;
				//$_SESSION['login'] = $login;
				//$_SESSION['email'] = $email;
				//$_SESSION['name']=$name;
				$_SESSION['userlevel']=$userlevel;
				$_SESSION['io']=$name." ".$th_name;
				//$_SESSION['userlevelname']=$userlevelname;
			}
			$_SESSION['auch']=1;
			//unset($_SESSION['count']);
			mysqli_close($link);
			header('Location:'.$wwwlink.'');
			//header('Location:/');
		}
		else 
		{
			$_SESSION['auch']=2;
			mysqli_close($link);
			//header('Location:'.$wwwlink.'');
			header('Location:/');
		}
	}
?>