<?php
$move = 0;

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
$sheet->getColumnDimension('D')->setWidth(12);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setWidth(35);
$sheet->getColumnDimension('G')->setWidth(35);

//$ispolnitel
if(isset($ispolnitel) AND $ispolnitel=="yes"){
	$move = 1;
	$sheet->getColumnDimension($row[7])->setAutoSize(true);
}
$sheet->getColumnDimension($row[7+$move])->setWidth(15);
$sheet->getColumnDimension($row[8+$move])->setWidth(15);
$sheet->getColumnDimension($row[9+$move])->setWidth(15);
$sheet->getColumnDimension($row[10+$move])->setWidth(15);
$sheet->getColumnDimension($row[11+$move])->setWidth(15);
$sheet->getColumnDimension($row[12+$move])->setWidth(12);

//$sheet->mergeCells('A2:C2');
$sheet->setCellValue('A2','Заказчик:');
//$sheet->mergeCells('A3:C3');
$sheet->setCellValue('A3','Отчетный год:');
//$sheet->mergeCells('A4:C4');
$sheet->setCellValue('A4','Отчетный период:');
$sheet->setCellValue('A5','Кол-во месяцев:');
//$sheet->mergeCells('A6:C6');
$date = date('d-m-Y');
$sheet->setCellValue('A1', 'Дата: ');
$sheet->setCellValue('B1', $date);
$sheet->setCellValue('B2',$customer_sel['customer_name']);
$sheet->setCellValue('B3',$year);
$sheet->setCellValue('B4',($month_start_name." - ".$month_end_name));
$sheet->setCellValue('B5',($month_period));
$sheet->mergeCells('A6:A7');
$sheet->setCellValue('A6', 'Проект');
$sheet->mergeCells('B6:B7');
$sheet->setCellValue('B6', '№ заявки');
$sheet->mergeCells('C6:C7');
$sheet->setCellValue('C6', 'Город');
$sheet->mergeCells('D6:D7');
$sheet->setCellValue('D6', 'Объект');
$sheet->mergeCells('E6:E7');
$sheet->setCellValue('E6', 'Адрес');
$sheet->mergeCells('F6:F7');
$sheet->setCellValue('F6', 'Задача');
$sheet->mergeCells('G6:G7');
$sheet->setCellValue('G6', 'Решение');
if(isset($ispolnitel) AND $ispolnitel=="yes"){
	$sheet->mergeCells('H6:H7');
	$sheet->setCellValue('H6', 'Исполнитель');
}
$sheet->mergeCells($row[7 + $move].'6:'.$row[11 + $move].'6');
$sheet->setCellValue($row[7+$move].'6', 'Расходы по заявке');
$sheet->setCellValue($row[7+$move].'7', 'Инцидентная');
$sheet->setCellValue($row[8+$move].'7', 'Почасовая');
$sheet->setCellValue($row[9+$move].'7', 'Смета');
$sheet->setCellValue($row[10+$move].'7', 'Материалы');
$sheet->setCellValue($row[11+$move].'7', 'Транспорт');
$sheet->mergeCells($row[12 + $move].'6:'.$row[12 + $move].'7');
$sheet->setCellValue($row[12+$move].'6', 'Сумма');

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
	$objects = Show_Objects ($link, $id_project);

	if($objects)
	{
		$cash_abplata_month = 0; //Месячная абонплата
		
		
		foreach($objects as $object)
		{
			$id_odject = $object['id_object'];
			$shop_number = $object['shop_number'];
			$address = $object['address'];
			$abon_plata = $object['abon_plata'];
			$cash_abplata_month = $abon_plata * $month_period;
			$cash_abplata_month_summ += intval($cash_abplata_month); //Сумма месячных абонплат со всех объектов одного проекта
			$rep_tickets = Show_Rep_Tickets ($link, $id_odject, $year, $ticket_status, $month_start, $month_end);
			if($rep_tickets)
			{
				foreach($rep_tickets as $rep_ticket)
				{
					
					$implementer_new = "";					
					$implementer = $rep_ticket['implementer'];
					$contr_info = edit_contr($link, $rep_ticket['id_contractor']);
					$city_name_contr = get_geo($link, $contr_info['city_id'], 'city', 'city_id');
					$row_next = $row_start + $rowplus;
					if($rep_ticket['work_type'] == 1) //если выбран вид работы "Инцидентное обслуживание"
					{	
						switch ($rep_ticket['ticket_sla'])
						{
							case 0:
								$cost_incident = intval($projects['cost_incident_critical']);
								break;
							case 1:
								$cost_incident = intval($projects['cost_incident_high']);
								break;
							case 2:
								$cost_incident = intval($projects['cost_incident_medium']);
								break;
							case 3:
								$cost_incident = intval($projects['cost_incident_low']);
								break;
						}
					}
					else {
						$cost_incident = 0;
					}
					$ticket_number = $rep_ticket['ticket_number'];
					$ticket_task = $rep_ticket['ticket_task'];
					$ticket_solution = $rep_ticket['ticket_solution'];
					$city_name = $object['city_name'];
					$hours = intval($rep_ticket['hours']);
					$sla = intval($rep_ticket['ticket_sla']);
					$cost_hour = $hours * intval($projects['cost_hour']);
					$cost_smeta = intval($rep_ticket['cost_smeta']);
					$cost_material = intval($rep_ticket['cost_material']);
					$cost_transport = intval($rep_ticket['cost_transport']);
					$summ = ($cost_incident + $cost_hour + $cost_smeta + $cost_material + $cost_transport);
					
					//Вывод на страницу в Excel
					$sheet->setCellValue('A'.$row_next, $projects['projectname']);
					$sheet->setCellValue('B'.$row_next, $ticket_number);
					$sheet->setCellValue('C'.$row_next, $city_name);
					$sheet->setCellValue('D'.$row_next, $shop_number);
					$sheet->setCellValue('E'.$row_next, $address);
					$sheet->setCellValue('F'.$row_next, $ticket_task);
					$sheet->setCellValue('G'.$row_next, $ticket_solution);
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
						$sheet->setCellValue('H'.$row_next, $implementer_new);
					}
					$sheet->setCellValue($row[7 + $move].$row_next, $cost_incident);
					$sheet->setCellValue($row[8 + $move].$row_next, $cost_hour);
					$sheet->setCellValue($row[9 + $move].$row_next, $cost_smeta);
					$sheet->setCellValue($row[10 + $move].$row_next, $cost_material);
					$sheet->setCellValue($row[11 + $move].$row_next, $cost_transport);
					$sheet->setCellValue($row[12 + $move].$row_next, $summ);
					$rowplus++;
					$all_cost_in_project += $summ;
					
				
				}
			}
		}
	}
}
$sheet->setCellValue($row[11 + $move].($row_next + 1), "ИТОГО:");
$sheet->setCellValue($row[12 + $move].($row_next + 1), $all_cost_in_project);
$sheet->getStyle($row[12 + $move].($row_next + 1))->getNumberFormat()->setFormatCode('# ### ##0.00');
$sheet->getStyle('A6:'.$row[12 + $move].'7')->applyFromArray($style_header);
$sheet->getStyle('A7:'.$row[6 + $move].($row_next))->applyFromArray($style_left);
$sheet->getStyle('A6:'.$row[12 + $move].'7')->applyFromArray($style_center);
$sheet->getStyle($row[7 + $move].'8:'.$row[12 + $move].($row_next))->applyFromArray($style_center);
$sheet->getStyle('A6:'.$row[12 + $move].($row_next))->applyFromArray($style_wrap);
$sheet->getStyle('B1:B5')->applyFromArray($style_left);
$sheet->getStyle($row[11 + $move].($row_next + 1).':'.$row[12 + $move].($row_next + 1))->applyFromArray($style_header);
$sheet->getStyle($row[11 + $move].($row_next + 1).':'.$row[12 + $move].($row_next + 1))->applyFromArray($style_wrap);
$sheet->getStyle($row[7 + $move].'8:'.$row[12 + $move].($row_next))->getNumberFormat()->setFormatCode('# ### ##0.00');
?>