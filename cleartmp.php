<?php
//require '/connection/config.php';
//require '../../domains/megatreid/connection/config_for_cron.php';
require_once '/blocks/header.php'; 
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
    return $c;
}

$cleanresult = cleanDir('../../userdata/temp');

if ($cleanresult == 0){
    echo "Папка 'TEMP' очищена!";
}

?>