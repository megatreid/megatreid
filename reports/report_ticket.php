<?php
$move = 0;
$movepay = 0;
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(2);
require '/func/arrays.php';
// Получаем активный лист
$sheet = $xls->getActiveSheet();
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$sheet->setTitle('Отчет по заявкам');
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setWidth(35);
$sheet->getColumnDimension('H')->setWidth(35);

//$ispolnitel
if(isset($ispolnitel) AND $ispolnitel=="yes"){
	$move = 1;
	$sheet->getColumnDimension($row[7])->setWidth(35);
}
$sheet->getColumnDimension($row[8+$move])->setWidth(35);
$sheet->getColumnDimension($row[9+$move])->setWidth(15);
$sheet->getColumnDimension($row[10+$move])->setWidth(15);
$sheet->getColumnDimension($row[11+$move])->setWidth(15);
$sheet->getColumnDimension($row[12+$move])->setWidth(15);
$sheet->getColumnDimension($row[13+$move])->setWidth(15);
$sheet->getColumnDimension($row[14+$move])->setWidth(14);
//$sheet->mergeCells('A2:C2');
$sheet->setCellValue('A2','Заказчик:');
//$sheet->mergeCells('A3:C3');
$sheet->setCellValue('A3','Отчетный год:');
//$sheet->mergeCells('A4:C4');
$sheet->setCellValue('A4','Отчетный период:');
$sheet->setCellValue('B4',($month_start_name." - ".$month_end_name.' ('.$month_period.'мес.)'));
//$sheet->mergeCells('A6:C6');
$date = date('d-m-Y');
$sheet->setCellValue('A1', 'Дата: ');
$sheet->setCellValue('B1', $date);
$sheet->setCellValue('B2',html_entity_decode($customer_sel['customer_name'], ENT_QUOTES));
$sheet->setCellValue('B3',$year);

//$sheet->mergeCells('A6:A7');
$sheet->setCellValue('A7', 'Проект');
//$sheet->mergeCells('B6:B7');
$sheet->setCellValue('B7', '№ заявки');
//$sheet->mergeCells('C6:C7');
$sheet->setCellValue('C7', 'Статус заявки');
$sheet->setCellValue('D7', 'Дата принятия в работу');
//$sheet->mergeCells('D6:D7');
$sheet->setCellValue('E7', 'Город');
//$sheet->mergeCells('E6:E7');
$sheet->setCellValue('F7', 'Объект');
//$sheet->mergeCells('F6:F7');
$sheet->setCellValue('G7', 'Адрес');
//$sheet->mergeCells('G6:G7');
$sheet->setCellValue('H7', 'Задача');
$sheet->mergeCells('A6:I6');
$sheet->setCellValue('I7', 'Решение');
if(isset($ispolnitel) AND $ispolnitel=="yes"){
	
	$sheet->setCellValue('J7', 'Исполнитель');
}
$sheet->mergeCells($row[9 + $move].'6:'.$row[13 + $move].'6');
$sheet->setCellValue($row[9+$move].'6', 'Расходы по заявке');
$sheet->setCellValue($row[9+$move].'7', 'Инцидентная');
$sheet->setCellValue($row[10+$move].'7', 'Почасовая');
$sheet->setCellValue($row[11+$move].'7', 'Смета');
$sheet->setCellValue($row[12+$move].'7', 'Материалы');
$sheet->setCellValue($row[13+$move].'7', 'Доставка');
//$sheet->mergeCells($row[13 + $move].'6:'.$row[13 + $move].'7');
$sheet->setCellValue($row[14+$move].'7', 'Сумма');

if(isset($paystatus) AND $paystatus=="yes"){
	$movepay = 3;
	$sheet->mergeCells($row[15 + $move].'6:'.$row[17 + $move].'6');
	$sheet->setCellValue($row[15+$move].'7', 'Номер счета');
	//$sheet->mergeCells($row[15 + $move].'6:'.$row[15 + $move].'7');
	$sheet->setCellValue($row[16+$move].'7', 'Дата платежа');	
	//$sheet->mergeCells($row[16 + $move].'6:'.$row[16 + $move].'7');
	$sheet->setCellValue($row[17+$move].'7', 'Статус платежа');
	$sheet->getColumnDimension($row[15+$move])->setWidth(16);
	$sheet->getColumnDimension($row[16+$move])->setWidth(16);
	$sheet->getColumnDimension($row[17+$move])->setWidth(14);

	$sheet->getStyle('A6:'.$row[14 + $move].'7')->getAlignment()->setWrapText(true);
}
$sheet->getColumnDimension($row[15 + $move + $movepay])->setWidth(50);
$sheet->setCellValue($row[15 + $move + $movepay].'7', 'Комментарий');

$all_cost_in_project = 0;
$row_start = 8;
$rowplus = 0;
$row_inc_plus = 0;
$counter = 1;
$summ = 0;
$cash_abplata_month_1 = 0;
$all_cost_in_project_summ = 0;
foreach($_POST['id_projects'] as $id_project)
{
	$projects = Edit_Project ($link, $id_project);
	$objects = Show_Objects_report ($link, $id_project);

	if($objects)
	{
		$cash_abplata_month = 0; //Месячная абонплата

		foreach($objects as $object)
		{
			$id_odject = $object['id_object'];
			$shop_number = html_entity_decode($object['shop_number'], ENT_QUOTES);
			$address = html_entity_decode($object['address'], ENT_QUOTES);
			/*$abon_plata = $object['abon_plata'];
			$cash_abplata_month = $abon_plata * $month_period;
			$cash_abplata_month_summ += floatval($cash_abplata_month); //Сумма месячных абонплат со всех объектов одного проекта
			*/
			$rep_tickets = Show_Rep_Tickets ($link, $id_odject, $year, $ticket_status, $custompaystatus, $month_start, $month_end);
			if($rep_tickets)
			{
				foreach($rep_tickets as $rep_ticket)
				{
					
					$implementer_new = "";					
					$implementer = html_entity_decode($rep_ticket['implementer']);
					$contr_info = edit_contr($link, $rep_ticket['id_contractor']);
					$city_name_contr = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
					$row_next = $row_start + $rowplus;
					if($rep_ticket['work_type'] == 1) //если выбран вид работы "Инцидентное обслуживание"
					{	
						switch ($rep_ticket['ticket_sla'])
						{
							case 0:
								$cost_incident = floatval($projects['cost_incident_critical']);
								break;
							case 1:
								$cost_incident = floatval($projects['cost_incident_high']);
								break;
							case 2:
								$cost_incident = floatval($projects['cost_incident_medium']);
								break;
							case 3:
								$cost_incident = floatval($projects['cost_incident_low']);
								break;
						}
					}
					else {
						$cost_incident = 0;
					}
					$ticket_number = $rep_ticket['ticket_number'];
					$ticket_task = html_entity_decode($rep_ticket['ticket_task'], ENT_QUOTES);
					$ticket_solution = html_entity_decode($rep_ticket['ticket_solution'], ENT_QUOTES);
					$ticket_status_q = $ticket_status_array[$rep_ticket['ticket_status']];
					$city_name = $object['city_name'];
					$hours = floatval($rep_ticket['hours']);
					$sla = intval($rep_ticket['ticket_sla']);
					$cost_hour = $hours * floatval($projects['cost_hour']);
					$cost_smeta = floatval($rep_ticket['cost_smeta']);
					$cost_material = floatval($rep_ticket['cost_material']);
					$cost_transport = floatval($rep_ticket['cost_transport']);
					$ticket_comment = html_entity_decode($rep_ticket['comment'], ENT_QUOTES);
					$summ = ($cost_incident + $cost_hour + $cost_smeta + $cost_material + $cost_transport);
					$ticketdate = strtotime($rep_ticket['ticket_date']);
					$ticketdate = date( 'd-m-Y', $ticketdate );
					//Вывод на страницу в Excel
					$sheet->setCellValue('A'.$row_next, $projects['projectname']);
					$sheet->setCellValue('B'.$row_next, $ticket_number);
					$sheet->setCellValue('C'.$row_next, $ticket_status_q);
					$sheet->setCellValue('D'.$row_next, $ticketdate);
					$sheet->setCellValue('E'.$row_next, $city_name);
					$sheet->setCellValue('F'.$row_next, $shop_number);
					$sheet->setCellValue('G'.$row_next, $address);
					$sheet->setCellValue('H'.$row_next, $ticket_task);
					$sheet->setCellValue('I'.$row_next, $ticket_solution);
					if(isset($ispolnitel) AND $ispolnitel=="yes"){
						if($implementer==1){
							$implementer_new = "Мега Трейд ООО (Нижний Новгород)";
						}
						elseif ($implementer==0){
							if($contr_info['org_name'] AND $contr_info['ownership'] AND $city_name['name']){
								//$implementer_new = ($contr_info['org_name'].' '.$contr_info['status'].' ('.$city_name['name'].')');
								$implementer_new = $contr_info['org_name'].' '.$contr_info['ownership'].' ('.$city_name_contr['name'].')';
							}
						}
						$sheet->setCellValue('J'.$row_next, $implementer_new);
					}
					$sheet->setCellValue($row[9 + $move].$row_next, $cost_incident);
					$sheet->setCellValue($row[10 + $move].$row_next, $cost_hour);
					$sheet->setCellValue($row[11 + $move].$row_next, $cost_smeta);
					$sheet->setCellValue($row[12 + $move].$row_next, $cost_material);
					$sheet->setCellValue($row[13 + $move].$row_next, $cost_transport);
					$sheet->setCellValue($row[14 + $move].$row_next, $summ);
					
					$rowplus++;
					$all_cost_in_project += $summ;
					if(isset($paystatus) AND $paystatus=="yes"){
						$sheet->setCellValue($row[15 + $move].$row_next, $rep_ticket['customer_account_number']);
						if($rep_ticket['customer_date_payment'] != 0){
							$convertticketdate = strtotime($rep_ticket['customer_date_payment']);
							$ticketdate = date( 'd-m-Y', $convertticketdate );
						}
						else{
							$ticketdate = '';
						}
						
						$sheet->setCellValue($row[16 + $move].$row_next, $ticketdate);
						$sheet->setCellValue($row[17 + $move].$row_next, $paymentstatus_array[$rep_ticket['customer_payment_status']]);
						
					}
					$sheet->setCellValue($row[15 + $move + $movepay].$row_next, $ticket_comment);
				
				}
			}
		}
	}
}
$sheet->setCellValue($row[13 + $move].($row_next + 1), "ИТОГО:");
$sheet->setCellValue($row[14 + $move].($row_next + 1), $all_cost_in_project);
$sheet->getStyle($row[14 + $move].($row_next + 1))->getNumberFormat()->setFormatCode('# ### ##0.00');
$sheet->getStyle('A6:'.$row[14 + $move + $movepay + 1].'7')->applyFromArray($style_header);
$sheet->getStyle('A7:'.$row[8 + $move].($row_next))->applyFromArray($style_left);
$sheet->getStyle('A6:'.$row[14 + $move + $movepay].'7')->applyFromArray($style_center);
$sheet->getStyle($row[9 + $move].'8:'.$row[14 + $move].($row_next))->applyFromArray($style_center);
$sheet->getStyle('A6:'.$row[14 + $move + $movepay +1].($row_next))->applyFromArray($style_wrap);
$sheet->getStyle('A8:E'.($row_next))->applyFromArray($style_center);
$sheet->getStyle('B1:B5')->applyFromArray($style_left);
$sheet->getStyle($row[13 + $move].($row_next + 1).':'.$row[14 + $move].($row_next + 1))->applyFromArray($style_header);
$sheet->getStyle($row[13 + $move].($row_next + 1).':'.$row[14 + $move].($row_next + 1))->applyFromArray($style_wrap);
$sheet->getStyle('A6:'.$row[14 + $move + $movepay].'7')->getAlignment()->setWrapText(true);
$sheet->getStyle($row[9 + $move].'8:'.$row[14 + $move].($row_next))->getNumberFormat()->setFormatCode('# ### ##0.00');

$sheet->getStyle($row[16 + $move].'8:'.$row[16 + $move].($row_next))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
?>
