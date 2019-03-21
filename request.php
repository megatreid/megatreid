<?php
require_once '/connection/config.php';

/**
 *  Данные передаются методом POST
 *  Если массив POST, пустой, что-то идет не так
 *  Более того, переменная $_POST[ 'request' ] пустая, или её не существует,
 *  значит тоже что-то не так
 */
if ( empty( $_POST ) ) {
	
	die( "Массив \$_POST пустой" );
	
}
elseif ( empty( $_POST[ 'request' ] ) ) {
	
	die( "Не передан запрос" );
	
}
else {
	
	// Очищаем строку с типом запроса от лишних пробелов и защищаемся от возможных SQL-инъекций
	$request = htmlspecialchars( trim( $_POST[ 'request' ] ) );
	echo $request;
	// Убираем тип запроса из массива $_POST
	unset( $_POST[ 'request' ] );
	
}

// В переменной $response будем возвращать данные AJAX-запросу
$response = NULL;

switch ( $request ) {
	case "getCity":
		
		$response = getCity($link, $_POST );
		
	break;
}

echo json_encode( $response );
?>