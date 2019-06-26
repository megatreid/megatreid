<?php
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(1);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
// Подписываем лист
$sheet->setTitle('Абонентская плата');
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getColumnDimension('H')->setAutoSize(true);
$sheet->getColumnDimension('I')->setAutoSize(true);
$sheet->getColumnDimension('J')->setAutoSize(true);
$date = date('d-m-Y');
$sheet->setCellValue('A1', 'Дата: ');
$sheet->setCellValue('B1', $date);
//$sheet->mergeCells('A3:C3');
$sheet->setCellValue('A2','Отчетный год:');
//$sheet->mergeCells('A4:C4');
$sheet->setCellValue('A3','Отчетный месяц:');
//$sheet->setCellValue('A4','Кол-во месяцев:');
$sheet->setCellValue('A4','Способ оплаты:');
$sheet->setCellValue('B4',$methodpaymentedit[$method_payment]);
$sheet->setCellValue('B2',$year);
$sheet->setCellValue('B3',($month_name));
//$sheet->setCellValue('B4',($month_period));
/* ПОДСЧЕТ АБОНЕНТСКИХ ПЛАТ */
$sheet->setCellValue('A6','Подрядчик');
$sheet->setCellValue('B6','Заказчик');
$sheet->setCellValue('C6','Проект');
$sheet->setCellValue('D6','Город объекта');
$sheet->setCellValue('E6','Объект');
$sheet->setCellValue('F6','Адрес');
$sheet->setCellValue('G6','Абонентская плата');
$sheet->setCellValue('H6','Статус оплаты');
$sheet->setCellValue('I6','Дата платежа');
$sheet->setCellValue('J6','Номер счета');
$row_start = 7;
$rowplus = 0;
$abon_plata_contr_itog = 0;
foreach($_POST['id_contractors'] as $id_contractor)
{
	$paystatusabon = "";
	$contr_info = edit_contr($link, $id_contractor);
	$city_name = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
	$objects = Show_objects_contr ($link, $id_contractor, $year, $month, $paystatusabon);
	$abon_plata_contr = 0;
	if($objects){
		foreach($objects as $object)
		{
			$object_info = Edit_Object($link, $object['id_object']);
			$project_info = Edit_Project($link, $object_info['id_project']);
			$customer_info = Edit_Customer($link, $object_info['id_customer']);
			$city_info = get_geo($link, $object_info['city_id'], 'city', 'city_id');
			//$customer_info = Edit_Customer ($link, $object['id_customer']);
	
			$row_next = $row_start + $rowplus;
			$object_city_name = $city_info['name'];
			$shop_number = html_entity_decode($object_info['shop_number'], ENT_QUOTES);
			$address = html_entity_decode($object_info['address'], ENT_QUOTES);
			$abon_plata_contr = floatval($object['summ']);
			//$abon_plata_contr_period = $abon_plata_contr * $month_period;
			$sheet->setCellValue('A'.($row_next), $contr_info['org_name'].' '.$contr_info['ownership'].' ('.$city_name['name'].')');
			$sheet->setCellValue('B'.($row_next), html_entity_decode($customer_info['customer_name'], ENT_QUOTES));
			$sheet->setCellValue('C'.($row_next), html_entity_decode($project_info['projectname'], ENT_QUOTES));
			$sheet->setCellValue('D'.($row_next), $object_city_name);
			$sheet->setCellValue('E'.($row_next), $shop_number);
			$sheet->setCellValue('F'.($row_next), $address);
			$sheet->setCellValue('G'.($row_next), $abon_plata_contr);
			$sheet->setCellValue('H'.($row_next), $paymentstatus_array[$object['paystatus']]);
			$sheet->setCellValue('I'.($row_next), $object['paydate']);
			$sheet->setCellValue('J'.($row_next), $object['pay_account']);
			//$sheet->setCellValue('H'.($row_next), $abon_plata_contr_period);
			$rowplus++;
			$abon_plata_contr_itog += $abon_plata_contr;
		}
	}
	else {
	}
}
$sheet->setCellValue('F'.($row_next + 1),"ИТОГО:");
$sheet->setCellValue('G'.($row_next + 1), $abon_plata_contr_itog);
/* ПРИМЕНЕНИЕ СТИЛЕЙ */
$sheet->getStyle('A6:J'.($row_next))->applyFromArray($style_wrap);
$sheet->getStyle('A6:J6')->applyFromArray($style_header);
$sheet->getStyle('F'.($row_next + 1).':G'.($row_next + 1))->applyFromArray($style_header);
$sheet->getStyle('F'.($row_next + 1).':G'.($row_next + 1))->applyFromArray($style_wrap);
$sheet->getStyle('F'.($row_next + 1))->applyFromArray($style_right);
$sheet->getStyle('A6:J6')->applyFromArray($style_center);
$sheet->getStyle('B1:B5')->applyFromArray($style_left);
$sheet->getStyle('A7:F'.($row_next))->applyFromArray($style_left);
$sheet->getStyle('F7:J'.($row_next+1))->applyFromArray($style_center);
$sheet->getStyle('F7:G'.($row_next + 1))->getNumberFormat()->setFormatCode('# ### ##0.00');
?>
