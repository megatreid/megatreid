<?php
require '/connection/config.php';
require '/func/arrays.php';
// Подключаем класс для работы с excel
require_once('/Classes/PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
require_once('/Classes/PHPExcel/Writer/Excel5.php');
$ticket_status=0;
$data = $_POST;
$status = $ticket_status;
$current_month = "";
$tickets = Show_Tickets($link, $ticket_status, $current_month);






// Создаем объект класса PHPExcel
$xls = new PHPExcel();
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(0);
// Получаем активный лист
$sheet = $xls->getActiveSheet();


$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
// Подписываем лист
$sheet->setTitle('Отчет по открытым заявкам');
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(13);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(30);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(25);
$sheet->getColumnDimension('L')->setWidth(30);

$sheet->setCellValue('A3','№');
$sheet->setCellValue('B3','Номер заявки');
$sheet->setCellValue('C3','Дата заведения');
$sheet->setCellValue('D3','Отчетный год');
$sheet->setCellValue('E3','Отчетный месяц');
$sheet->setCellValue('F3','Заказчик');
$sheet->setCellValue('G3','Проект');
$sheet->setCellValue('H3','Город');
$sheet->setCellValue('I3','Объект');
$sheet->setCellValue('J3','Статус заявки');
$sheet->setCellValue('K3','Исполнитель');
$sheet->setCellValue('L3','Сотрудник');
$sheet->setCellValue('M3','Дата');

$start=4;
$i=0;
foreach($tickets as $ticket){
	$convertticketdate = strtotime($ticket['ticket_date']);
	$objects = Edit_Object ($link, $ticket['id_object']);
	$city = Get_Geo ($link, $objects['city_id'], "city", "city_id");
	$projects = Edit_Project ($link, $objects['id_project']);
	$customers = Edit_Customer ($link, $objects['id_customer']);
	$contractor = Edit_Contr ($link, $ticket['id_contractor']);
	$ticketdate = date( 'd-m-Y H:i:s', $convertticketdate );
	$convertlast_edit_datetime = strtotime($ticket['last_edit_datetime']);
	$last_edit_datetime = date( 'd-m-Y H:i:s', $convertlast_edit_datetime );
	$users = Edit_User($link, $ticket['last_edit_user_id']);
	$next = $start+$i;
	$sheet->setCellValueByColumnAndRow(0, $next, $i+1);
	$sheet->setCellValueByColumnAndRow(1, $next, $ticket['ticket_number']);
	$sheet->setCellValueByColumnAndRow(2, $next, $ticketdate);
	$sheet->setCellValueByColumnAndRow(3, $next, $ticket['year']);
	$sheet->setCellValueByColumnAndRow(4, $next, $months[$ticket['month']-1]);
	$sheet->setCellValueByColumnAndRow(5, $next, $customers['customer_name']);
	$sheet->setCellValueByColumnAndRow(6, $next, $projects['projectname']);
	$sheet->setCellValueByColumnAndRow(7, $next, $city['name']);
	$sheet->setCellValueByColumnAndRow(8, $next, $objects['shop_number']." Адрес: ".$objects['address']);
	$sheet->setCellValueByColumnAndRow(9, $next, $ticket_status_array[$ticket['ticket_status']]);
	$sheet->setCellValueByColumnAndRow(10, $next, $contractor['org_name']." ".$contractor['status']);
	$sheet->setCellValueByColumnAndRow(11, $next, $users['surname']." ".$users['name']);
	$sheet->setCellValueByColumnAndRow(12, $next, $last_edit_datetime);
	$i++;
	};
header("Content-Type:application/vnd.ms-excel");
header("Content-Disposition:attachment;filename='simple.xlsx'");

    //Сохраняем файл с помощью PHPExcel_IOFactory и указываем тип Excel
    $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');

    //Отправляем файл
    $objWriter->save('php://output');

 
?>
