<?php
require '/connection/config.php';
require_once 'blocks/header.php'; 
require '/func/arrays.php';
$config = [
    // ip адрес сервера, с которого будем копировать базу
    'ip'              => '127.0.0.1',
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
$filename = str_replace('<date>', date('d-m-Y'), $config['filenamePattern']);
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
function cleanDirectory($dir, $maxFilesCount)
{
    $filenames = [];
 
    foreach(scandir($dir) as $file) {
        $filename = "$dir/$file";
        if (is_file($filename)) {
            $filenames[] = $filename;
        }
    }
 
    if (count($filenames) <= $maxFilesCount) {
        return;
    }
 
    $freshFilenames = array_reverse($filenames);
    array_splice($freshFilenames, $maxFilesCount);
    $oldFilenames = array_diff($filenames, $freshFilenames);
 
    foreach ($oldFilenames as $filename) {
        unlink($filename);
    }
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
<div>
	Резервная копия базы данных "megatreid" сохранена в папке <b>/backup/</b> корневого каталога. Имя файла: <b><?=$filename;?></b>.
	
</div>
			<div>
				<input class="button" value="На главную страницу" type="button" onclick="location.href='/'" />
			</div>
</div>

</body>
</html>