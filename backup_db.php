<?php
require '/connection/config.php';
require_once 'blocks/header.php'; 
$data = $_POST;
if( isset($data['backup']))
{
	
	$config = [
		// ip адрес сервера, с которого будем копировать базу
		'ip'              => 'localhost',
		// Путь до папки в которой будут лежать дампы баз
		'path'            => 'backup',
		// Шаблон имени файла дампа базы. Вместо <date> подставится дата в формате 2015-04-19
		'filenamePattern' => 'dump_<date>.sql',
		// Максимальное количество дампов, хранящихся на сервере
		'maxFilesCount'   => 3,
		// Настройка подключения к БД
		'db' => [
			'name'     => 'megatreid',
			'user'     => 'baseuser',
			'password' => 'qazwsxedc',
		],
	];
	 
	$ip = !empty($config['ip']) ? "-h $config[ip]" : '';
	$filename = str_replace('<date>', date('d-m-Y_H-i-s'), $config['filenamePattern']);
	$command = "mysqldump $ip -u {$config['db']['user']} -p{$config['db']['password']} --extended-insert=false {$config['db']['name']} > {$config['path']}/$filename";

	//exec($command);
	system($command);
	 
	if (!empty($config['maxFilesCount'])) {
		cleanDirectory($config['path'], $config['maxFilesCount']);
	}
	 
	/**
	 * Clears the directory of the files, leaving no more than $maxFilesCount number of files
	 *
	 * @param string $dir
	 * @param string $maxFilesCount
	 */

}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Контрагенты</title>
</head>
<body>
<div class="main">
<form action="" method="POST">
	<div>
		<p>Создать резервную копию базы данных?</p>
		<input class="button" type="submit" value="Создать" name="backup">
	</div>

</form>
<?php 
if(isset($filename)){
?>
<div>
	Резервная копия базы данных "megatreid" сохранена в папке <a href="/backup/" title="открыть каталог" target="_blank"><b>/backup/</b></a> корневого каталога системы.<br>Имя файла:  <a href="backup/<?=$filename;?>" title="скачать файл"><b><?=$filename;?></b></a>.
	
</div>
			<div>
				<input class="button" value="На главную страницу" type="button" onclick="location.href='/'" />
			</div>
</div>
<?php }?>
</body>
</html>