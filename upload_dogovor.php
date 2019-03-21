<?php
require '/connection/config.php';
if(isset($_SESSION['userlevel']) AND $_SESSION['userlevel']==1)
{
require_once '/blocks/header.php';





?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Контрагенты</title>
</head>
<body>
<br>
<div>
</div>
<p><a href="/contractor.php">К списку контрагентов</a></p>

</body>
</html>
<?php
	}
	else
	{
		header('Location: /');
}
?>