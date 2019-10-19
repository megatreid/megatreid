<?php
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(0);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$sheet->getDefaultStyle()->getFont()->setName('Times New Roman');
$sheet->getDefaultStyle()->getFont()->setSize(11);
// Подписываем лист
$sheet->setTitle('Главная страница отчета');
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$date = date('d-m-Y');
$sheet->setCellValue('A1', 'Дата: ');
$sheet->setCellValue('B1', $date);
//$sheet->mergeCells('A3:C3');
$sheet->setCellValue('A2','Отчетный год:');
$sheet->setCellValue('B2',$year);
//$sheet->mergeCells('A4:C4');
$sheet->setCellValue('A3','Отчетный период:');
$sheet->setCellValue('B3',($months[$month_start-1].' - '.$months[$month_end-1].' ('.$month_period.'мес.)'));

//$sheet->setCellValue('A4','Кол-во месяцев:');
$sheet->setCellValue('A4','Форма оплаты:');
$sheet->setCellValue('B4',$methodpaymentedit[$method_payment]);
//$sheet->mergeCells('A6:C6');
$sheet->getStyle('A6')->applyFromArray($style_zagolovok);
$sheet->setCellValue('A6','1.Абонентская плата (отображается сумма за неоплаченные счета):');

/* ПОДСЧЕТ АБОНЕНТСКИХ ПЛАТ */

//$sheet->setCellValue('C3',($months[$month_end-1]));
//$sheet->setCellValue('B4',($month_period));
$row_start = 7;
$rowplus = 0;
$abon_plata_contr_itog = 0;

foreach($_POST['id_contractors'] as $id_contractor)
{
	$paystatusabon = "AND paystatus = 0";
	//$paystatusabon = "";
	$objects = Show_objects_contr ($link, $id_contractor, $year, $month_start, $month_end, $paystatusabon);

	if($objects){
	$contr_info = edit_contr($link, $id_contractor);
	$city_name = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
	$row_next = $row_start + $rowplus;		
	$abon_plata_contr_summ = 0;		

		$sheet->setCellValue('A'.($row_next), html_entity_decode($contr_info['org_name'], ENT_QUOTES).' ('.$city_name['name'].')');
		foreach($objects as $object)
		{
			$abon_plata_contr = $object['summ'];
			$abon_plata_contr_summ += $abon_plata_contr;
		}
		//$abon_plata_contr_summ *= $month_period;
		
		$sheet->setCellValue('B'.($row_next), $abon_plata_contr_summ);
		$abon_plata_contr_itog += $abon_plata_contr_summ;
		$rowplus++;
	}
	else {
		$row_next = $row_start + $rowplus;
	}
		
	
}
$sheet->setCellValue('A'.($row_next+1),"ИТОГО:");
$sheet->setCellValue('B'.($row_next+1), $abon_plata_contr_itog);
$sheet->getStyle('A7:B'.($row_next + 1))->applyFromArray($style_wrap);
$sheet->getStyle('A'.($row_next + 1).':B'.($row_next + 1))->applyFromArray($style_header);
$sheet->getStyle('A'.($row_next + 1))->applyFromArray($style_right);
$row_start2 = $row_next + 2;
$sheet->getStyle('A'.$row_start2)->applyFromArray($style_zagolovok);
$sheet->setCellValue('A'.$row_start2,'2.Затраты по заявкам:');
$row_start3 = $row_next + 3;
$rowplus = 0;
$row_next4 = $row_start3;
foreach($_POST['id_contractors'] as $id_contractor)
{
	$contr_info = edit_contr($link, $id_contractor);
	$city_name = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
	$tickets = Show_Rep_Contr_Tickets ($link, $id_contractor, $year, $ticket_status, $paystatus, $month_start, $month_end);
	$contr_cost_summ = 0;

	if($tickets){
		
		foreach($tickets as $ticket)
		{
			$contr_cost_work = floatval($ticket['contr_cost_work']);
			$contr_cost_smeta = floatval($ticket['contr_cost_smeta']);
			$contr_cost_transport = floatval($ticket['contr_cost_transport']);
			$contr_cost_material = floatval($ticket['contr_cost_material']);
			$contr_cost_summ += ($contr_cost_work + $contr_cost_smeta + $contr_cost_transport + $contr_cost_material);
		}
		
		$row_next4 = $row_start3 + $rowplus;
		$sheet->setCellValue('A'.($row_next4), html_entity_decode($contr_info['org_name'], ENT_QUOTES).' ('.$city_name['name'].')');
		$sheet->setCellValue('B'.($row_next4), $contr_cost_summ);
		$contr_cost_itog += $contr_cost_summ;
		$rowplus++;
	
	}
}

$sheet->setCellValue('A'.($row_next4+1),"ИТОГО:");
$sheet->setCellValue('B'.($row_next4+1), $contr_cost_itog);

$to_pay = $abon_plata_contr_itog + $contr_cost_itog;

$sheet->setCellValue('A'.($row_next4 + 3),"К ОПЛАТЕ:");
$sheet->setCellValue('B'.($row_next4 + 3), $to_pay);
/* ПРИМЕНЕНИЕ СТИЛЕЙ */
$sheet->getStyle('A'.($row_start2+1).':B'.($row_next4 + 1))->applyFromArray($style_wrap);
$sheet->getStyle('A'.($row_next4 + 1).':B'.($row_next4+ 1))->applyFromArray($style_header);
$sheet->getStyle('A'.($row_next4+3).':A'.($row_next4+3))->applyFromArray($style_right);
$sheet->getStyle('A'.($row_next4+3).':B'.($row_next4+3))->applyFromArray($style_wrap);
$sheet->getStyle('A'.($row_next4+3).':B'.($row_next4+3))->applyFromArray($style_header);
$sheet->getStyle('B7:B'.($row_next4 + 3))->applyFromArray($style_center);
$sheet->getStyle('B1:B5')->applyFromArray($style_left);
$sheet->getStyle('B7:B'.($row_next4 +3))->getNumberFormat()->setFormatCode('# ### ##0.00');
?>
