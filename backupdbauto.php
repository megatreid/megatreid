<?php
require '../../domains/megatreid/connection/config_for_cron.php';
	$config = [
		// ip адрес сервера, с которого будем копировать базу
		'ip'              => 'localhost',
		// Путь до папки в которой будут лежать дампы баз
		'path'            => '../../domains/megatreid/backup',
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

	//exec($command);
	exec($command);
	 
	if (!empty($config['maxFilesCount'])) {
		cleanDirectory($config['path'], $config['maxFilesCount']);
	}
function cleanDir($dir) {
    $files = glob($dir."/sess*");
    $c = count($files);
    if (count($files) > 0) {
        foreach ($files as $file) {      
            if (file_exists($file)) {
            unlink($file);
            }   
        }
    }
    //return $c;
}

cleanDir('../../userdata/temp');	
	
	
	

?>