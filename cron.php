<?php
$date = date("Y-m-d H-i-s");
$filename = 'data'.$date.'.txt';
$text = 'Этот текст будет добавлен в файл' . PHP_EOL; // Перенос строки лучше делать константой PHP_EOL
$text2 = 'И этот тоже!';

// Открываем файл, флаг W означает - файл открыт на запись
$f_hdl = fopen($filename, 'w');

// Записываем в файл $text
fwrite($f_hdl, $text);

// и $text2
fwrite($f_hdl, $text2);

// Закрывает открытый файл
fclose($f_hdl);

?>