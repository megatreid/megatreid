<?php

//require $_SERVER["DOCUMENT_ROOT"].'/connection/config_for_cron.php';

//$path1 = "../../modules/cron/";
//$file1 = "cron.tab";
//$task1 =  "*/1 * * * * d:\openserver\modules\php\PHP-5.6-x64\php-win.exe -c d:\openserver\modules\php\PHP-5.6-x64\php.ini -q -f d:\openserver\domains\megatreid\backup_db.php";

//file_put_contents($path1.$file1, $task1);
$path = "d:/openserver/domains/megatreid";
$path2 = "../../domains/megatreid";
$path3 = ".";
$file2 = "/text.txt";
$file3 = "/text_path.txt";
//file_put_contents($path.$file3, $path3);

    //$filelist = array();

    if($handle = opendir($path2)){
        while($entry = readdir($handle)){
			echo '<br>';
            file_put_contents($path2.$file2, $entry."\r\n", FILE_APPEND);
        }
      
        closedir($handle);
    }



?>
