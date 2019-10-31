<?php
require '/connection/config.php';
//require '../../domains/megatreid/connection/config_for_cron.php';
require_once '/blocks/header.php'; 
$data = $_POST;
$path = "../../modules/cron/";
$file = "cron.tab";
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
		'maxFilesCount'   => 5,
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

	exec($command);
	//system($command);
	 
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
if( isset($data['daily_on']))
{

	$task =  "0 7 * * * d:\openserver\modules\php\PHP-5.6-x64\php-win.exe -c d:\openserver\modules\php\PHP-5.6-x64\php.ini -q -f d:\openserver\domains\megatreid\backupdbauto.php";
	file_put_contents($path.$file, $task);	
}

if( isset($data['daily_off']))
{
	$task =  "";
	file_put_contents($path.$file, $task);
}

if (file_get_contents($path.$file))
{
	$msg = "<b>Включено ежедневное резервирование базы данных. <br>Резервирование происходит ежедневно в 7:00</b>";
	$backup_daily = true; 
}
else
{
	$msg = "<b>Отключено ежедневное резервирование базы данных</b>";
	$backup_daily = false; 
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Бэкап БД</title>
</head>

	
	<div class="showany">
	<h2>Создание резервной копии базы данных</h2>
<div class="reg_sel_object">

<form action="" method="POST">

	<div >
		<p>Создать резервную копию базы данных?
		<input type="submit" value="Создать" name="backup"></p>
		<?php
		if($backup_daily == true){ ?>
		<p><?=$msg;?></p>
		<p>Отключить ежедневное резервирование базы данных?
		<input type="submit" value="Отключить" name="daily_off"></p>
		<?php }
		else { ?>
		<p><?=$msg;?></p>
		<p>Включить ежедневное резервирование базы данных?
		<input  type="submit" value="Включить" name="daily_on"></p>
		<?php } ?>
	</div>
</div>
</form>
<?php 
if(isset($filename)){
?>
<div>
	<p>Резервная копия базы данных "megatreid" сохранена в папке <a href="/backup/" title="открыть каталог" target="_blank"><b>/backup/</b></a> корневого каталога системы.<br>Имя файла:  <a href="backup/<?=$filename;?>" title="скачать файл"><b><?=$filename;?></b></a>.</p>
	
</div>
			<div>
				<input class="button" value="На главную страницу" type="button" onclick="location.href='/'" />
			</div>
</div>
<?php }?>

</html>