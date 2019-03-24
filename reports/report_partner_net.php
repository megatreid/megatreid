<?php

// Создаем объект класса PHPExcel
$xls = new PHPExcel();
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(0);
// Получаем активный лист
$sheet = $xls->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$sheet->getDefaultStyle()->getFont()->setName('Times New Roman');
$sheet->getDefaultStyle()->getFont()->setSize(11);
// Подписываем лист
$sheet->setTitle('Партерская сеть');
$sheet->getColumnDimension('A')->setWidth(3);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(13);
$sheet->getColumnDimension('H')->setWidth(13);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(12);
$sheet->getColumnDimension('K')->setWidth(20);
$sheet->getColumnDimension('L')->setWidth(30);
$sheet->getColumnDimension('M')->setWidth(30);
$sheet->getColumnDimension('N')->setWidth(30);
$sheet->getColumnDimension('O')->setWidth(30);
$sheet->getColumnDimension('P')->setWidth(30);
$sheet->getColumnDimension('Q')->setWidth(30);
$sheet->getColumnDimension('R')->setWidth(30);
$sheet->getColumnDimension('S')->setWidth(30);

$date = date('d-m-Y');
$sheet->setCellValue('B1', 'Дата: ');
$sheet->setCellValue('C1', $date);

$sheet->setCellValue('A3','№');
$sheet->setCellValue('B3','Страна');
$sheet->setCellValue('C3','Регион');
$sheet->setCellValue('D3','Населенный пункт');
$sheet->setCellValue('E3','Организация/Исполнитель');
$sheet->setCellValue('F3','Статус подрядчика');
$sheet->setCellValue('G3','№ договора');
$sheet->setCellValue('H3','Форма расчета');
$sheet->setCellValue('I3','Номер карты');
$sheet->setCellValue('J3','Наличие анкеты');
$sheet->setCellValue('K3','Система налогообложения');
$sheet->setCellValue('L3','Контактное лицо');
$sheet->setCellValue('M3','Паспортные данные');
$sheet->setCellValue('N3','Мобильный телефон');
$sheet->setCellValue('O3','Рабочий телефон');
$sheet->setCellValue('P3','E-mail');
$sheet->setCellValue('Q3','WEB-сайт');
$sheet->setCellValue('R3','Примечание');
$sheet->setCellValue('S3','');



$next=4;
$count = 1;

foreach($contractors as $i => $contractor)
{
	$country_info =  Get_Geo ($link, $contractor['country_id'], "country", "country_id");
	$region_info = Get_Geo ($link, $contractor['region_id'], "region", "region_id");
	$city_info = Get_Geo ($link, $contractor['city_id'], "city", "city_id");	
	
	$sheet->setCellValue('A'.($next), ($i+1));
	$sheet->setCellValueByColumnAndRow(1, $next, $country_info['name']);
	$sheet->setCellValueByColumnAndRow(2, $next, $region_info['name']);
	$sheet->setCellValueByColumnAndRow(3, $next, $city_info['name']);
	$sheet->setCellValueByColumnAndRow(4, $next, $contractor['org_name'].' '.$contractor['ownership']);
	$sheet->setCellValueByColumnAndRow(5, $next, $statusedit[$contractor['status']]);
	$sheet->setCellValueByColumnAndRow(6, $next, $contractor['dogovor']);
	$sheet->setCellValueByColumnAndRow(7, $next, $methodpaymentedit[$contractor['method_payment']]);
	$sheet->setCellValueByColumnAndRow(8, $next, $contractor['card_number']);
	$sheet->setCellValueByColumnAndRow(9, $next, $contractor['anketa']);
	$sheet->setCellValueByColumnAndRow(10, $next, $contractor['system_no']);
	$sheet->setCellValueByColumnAndRow(11, $next, $contractor['contact_name']);
	$sheet->setCellValueByColumnAndRow(12, $next, $contractor['passport']);
	$sheet->setCellValueByColumnAndRow(13, $next, $contractor['mobile']);
	$sheet->setCellValueByColumnAndRow(14, $next, $contractor['phone']);
	$sheet->setCellValueByColumnAndRow(15, $next, $contractor['email']);
	$sheet->setCellValueByColumnAndRow(16, $next, $contractor['web']);
	$sheet->setCellValueByColumnAndRow(17, $next, $contractor['comment']);
	
	$next++;
}

$sheet->getStyle('A3:R'.($next-1))->applyFromArray($style_wrap);
$sheet->getStyle('A3:R3')->applyFromArray($style_header);
$sheet->getStyle('A3:R3')->applyFromArray($style_center);
$sheet->getStyle('A4:R'.($next-1))->applyFromArray($style_left);
$sheet->getStyle('A3:R'.($next-1))->getAlignment()->setWrapText(true);










?>