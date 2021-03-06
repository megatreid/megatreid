<?php
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(2);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$sheet->getDefaultStyle()->getFont()->setName('Times New Roman');
$sheet->getDefaultStyle()->getFont()->setSize(11);
// Подписываем лист
$sheet->setTitle('Отчет по заявкам');
$sheet->getColumnDimension('A')->setWidth(35);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setWidth(14);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setWidth(17);
$sheet->getColumnDimension('H')->setAutoSize(true);
$sheet->getColumnDimension('I')->setWidth(35);
$sheet->getColumnDimension('J')->setWidth(35);
$sheet->getColumnDimension('K')->setWidth(12);
$sheet->getColumnDimension('L')->setWidth(14);
$sheet->getColumnDimension('M')->setWidth(10);
$sheet->getColumnDimension('N')->setWidth(14);
$sheet->getColumnDimension('O')->setWidth(14);
$sheet->getColumnDimension('P')->setWidth(14);
$sheet->getColumnDimension('Q')->setWidth(14);
$sheet->getColumnDimension('R')->setWidth(14);
$sheet->getColumnDimension('S')->setWidth(14);
$sheet->getColumnDimension('T')->setAutoSize(true);
$date = date('d-m-Y');
$sheet->setCellValue('A1', 'Дата: ');
$sheet->setCellValue('B1', $date);
//$sheet->mergeCells('A3:C3');
$sheet->setCellValue('A2','Отчетный год:');
//$sheet->mergeCells('A4:C4');
$sheet->setCellValue('A3','Отчетный период:');
$sheet->setCellValue('B3',($months[$month_start-1].' - '.$months[$month_end-1].' ('.$month_period.'мес.)'));
//$sheet->setCellValue('A4','Кол-во месяцев:');
$sheet->setCellValue('A4','Способ оплаты:');
$sheet->setCellValue('B4',$methodpaymentedit[$method_payment]);
//$sheet->mergeCells('A6:C6');
$sheet->setCellValue('B2',$year);

//$sheet->setCellValue('B4',($month_period));
//$sheet->mergeCells('A6:A7');
$sheet->setCellValue('A7', 'Подрядчик');
//$sheet->mergeCells('B6:B7');
$sheet->setCellValue('B7', 'Заказчик');
//$sheet->mergeCells('C6:C7');
$sheet->setCellValue('C7', 'Проект');
//$sheet->mergeCells('D6:D7');
$sheet->setCellValue('D7', 'Объект');
//$sheet->mergeCells('E6:E7');
$sheet->setCellValue('E7', 'Номер заявки');
//$sheet->mergeCells('F6:F7');
$sheet->setCellValue('F7', 'Статус заявки');
$sheet->setCellValue('G7', 'Дата принятия в работу');
//$sheet->mergeCells('G6:G7');
$sheet->setCellValue('H7', 'Город');
//$sheet->mergeCells('H6:H7');
$sheet->setCellValue('I7', 'Адрес');
//$sheet->mergeCells('I6:I7');
$sheet->setCellValue('J7', 'Задача');
//$sheet->mergeCells('J6:J7');
$sheet->setCellValue('K7', 'Решение');
//$sheet->mergeCells('K6:K7');
$sheet->setCellValue('L7', 'Статус платежа');
//$sheet->mergeCells('L6:L7');
$sheet->setCellValue('M7', 'Дата платежа');
//$sheet->mergeCells('M6:M7');
$sheet->setCellValue('N7', 'Номер счета');
$sheet->mergeCells('O6:R6');
$sheet->setCellValue('O6', 'Расходы по заявке');
$sheet->setCellValue('O7', 'Стоимость');
$sheet->setCellValue('P7', 'Смета');
$sheet->setCellValue('Q7', 'Доставка');
$sheet->setCellValue('R7', 'Материалы');
//$sheet->mergeCells('R7:R7');
$sheet->setCellValue('S7', 'Сумма');
$sheet->setCellValue('T7', 'Комментарий');
$sheet->getStyle('A6:T7')->getAlignment()->setWrapText(true);
$sheet->mergeCells('A6:M6');
$row_start = 8;
$rowplus = 0;
$contr_cost_itog = 0;
foreach($_POST['id_contractors'] as $id_contractor)
{
	$contr_info = edit_contr($link, $id_contractor);
	$city_name = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
	
	$tickets = Show_Rep_Contr_Tickets ($link, $id_contractor, $year, $ticket_status,$paystatus, $month_start, $month_end);
	
	$contr_cost_summ = 0;
	if($tickets){
		foreach($tickets as $ticket)
		{
			$row_next = $row_start + $rowplus;
			$object_info = Edit_Object ($link, $ticket['id_object']);
			$customer_info = Edit_Customer ($link, $object_info['id_customer']);
			$project_info = Edit_Project ($link, $object_info['id_project']);
			$object_city_name = $object_info['city_name'];
			$ticket_status_contr = $ticket['ticket_status'];
			$shop_number = html_entity_decode($object_info['shop_number'], ENT_QUOTES);
			$address = html_entity_decode($object_info['address'], ENT_QUOTES);
			$contr_cost_work = floatval($ticket['contr_cost_work']);
			$contr_cost_smeta = floatval($ticket['contr_cost_smeta']);
			$contr_cost_transport = floatval($ticket['contr_cost_transport']);
			$contr_cost_material = floatval($ticket['contr_cost_material']);
			$ticketdate = strtotime($ticket['ticket_date']);
			$ticketdate = date( 'd-m-Y', $ticketdate );
			$contr_cost_summ = ($contr_cost_work + $contr_cost_smeta + $contr_cost_transport + $contr_cost_material);			
			$sheet->setCellValue('A'.($row_next), html_entity_decode($contr_info['org_name'], ENT_QUOTES).' '.$contr_info['ownership'].' ('.$city_name['name'].')');
			$sheet->setCellValue('B'.($row_next), html_entity_decode($customer_info['customer_name'], ENT_QUOTES));
			$sheet->setCellValue('C'.($row_next), html_entity_decode($project_info['projectname'], ENT_QUOTES));			

			$sheet->setCellValue('D'.($row_next), $shop_number);
			
			$sheet->setCellValue('E'.($row_next), $ticket['ticket_number']);
			$sheet->setCellValue('F'.($row_next), $ticket_status_array[$ticket_status_contr]);
			$sheet->setCellValue('G'.($row_next), $ticketdate);
			$sheet->setCellValue('H'.($row_next), $object_city_name);
			$sheet->setCellValue('I'.($row_next), $address);
			$sheet->setCellValue('J'.($row_next), html_entity_decode($ticket['ticket_task'], ENT_QUOTES));
			$sheet->setCellValue('K'.($row_next), html_entity_decode($ticket['ticket_solution'], ENT_QUOTES));
			$sheet->setCellValue('L'.($row_next), $paymentstatus_array[$ticket['contr_payment_status']]);
			if($ticket['contr_date_payment'] != 0){
							$convertticketdate = strtotime($ticket['contr_date_payment']);
							$ticketdate = date( 'd-m-Y', $convertticketdate );
						}
						else{
							$ticketdate = '';
						}
			$sheet->setCellValue('M'.($row_next), $ticketdate);
			$sheet->setCellValue('N'.($row_next), $ticket['contr_account_number']);
			$sheet->setCellValue('O'.($row_next), $contr_cost_work);
			$sheet->setCellValue('P'.($row_next), $contr_cost_smeta);
			$sheet->setCellValue('Q'.($row_next), $contr_cost_transport);
			$sheet->setCellValue('R'.($row_next), $contr_cost_material);
			$sheet->setCellValue('S'.($row_next), $contr_cost_summ);
			$sheet->setCellValue('T'.($row_next), $ticket['contr_comment']);
			$rowplus++;
			$contr_cost_itog += $contr_cost_summ;
		}
		//$sheet->setCellValue('B'.($row_next), $contr_cost_summ);
		
	}
	else {
		//$sheet->setCellValue('B'.($row_next), $contr_cost_summ);
	}
	
}
$sheet->setCellValue('R'.($row_next + 1),"ИТОГО:");
$sheet->setCellValue('S'.($row_next + 1), $contr_cost_itog);
/* ПРИМЕНЕНИЕ СТИЛЕЙ */
$sheet->getStyle('A6:T'.($row_next))->applyFromArray($style_wrap);
$sheet->getStyle('A6:T7')->applyFromArray($style_header);
$sheet->getStyle('A'.($row_next + 1))->applyFromArray($style_right);
$sheet->getStyle('A6:T7')->applyFromArray($style_center);
$sheet->getStyle('B1:B5')->applyFromArray($style_left);
$sheet->getStyle('R'.($row_next + 1).':S'.($row_next + 1))->applyFromArray($style_header);
$sheet->getStyle('R'.($row_next + 1).':S'.($row_next + 1))->applyFromArray($style_wrap);
$sheet->getStyle('A7:H'.($row_next))->applyFromArray($style_center);
$sheet->getStyle('L8:S'.($row_next+1))->applyFromArray($style_center);
//$sheet->getStyle('J8:N'.($row_next + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
$sheet->getStyle('O8:S'.($row_next + 1))->getNumberFormat()->setFormatCode('# ### ##0.00');
?>