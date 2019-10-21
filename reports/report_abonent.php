<?php
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(1);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$sheet->setTitle('Абонентская плата');
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
//$sheet->mergeCells('A2:C2');
$sheet->setCellValue('A2','Заказчик:');
//$sheet->mergeCells('A3:C3');
$sheet->setCellValue('A3','Отчетный год:');
//$sheet->mergeCells('A4:C4');
$sheet->setCellValue('A4','Отчетный период:');

//$sheet->mergeCells('A6:C6');
$date = date('d-m-Y');
$sheet->setCellValue('A1', 'Дата: ');
$sheet->setCellValue('B1', $date);
$sheet->setCellValue('B2',html_entity_decode($customer_sel['customer_name'], ENT_QUOTES));
$sheet->setCellValue('B3',$year);
$sheet->setCellValue('B4',($month_start_name." - ".$month_end_name.' ('.$month_period.'мес.)'));
$sheet->setCellValue('A6', 'Проект');
$sheet->setCellValue('B6', 'Город');
$sheet->setCellValue('C6', 'Объект');
$sheet->setCellValue('D6', 'Адрес');
$sheet->setCellValue('E6', 'Месяц');
$sheet->setCellValue('F6', 'Абонентская плата');
$sheet->setCellValue('G6', 'Статус платежа');

		if(isset($_POST['id_projects']))
		{
			$cash_summ = 0;
			//$all_cost_in_project = 0;
			$row_start = 7;
			$rowplus = 0;
			$row_inc_plus = 0;
			$counter = 1;
			$cash_abplata_month_1 = 0;
			$all_cost_in_project_summ = 0;
			$row_incident = count($_POST['id_projects']) + 9;
			
			foreach($_POST['id_projects'] as $id_project)
			{
				$row_next = $row_start + $rowplus;
				$projects = Edit_Project ($link, $id_project);
				$objects = Show_Objects_report ($link, $id_project);

				if($objects)
				{
					//$all_cost_in_project = 0;
					foreach($objects as $object)
					{
						
						$city_name = $object['city_name'];
						$shop_number = html_entity_decode($object['shop_number'], ENT_QUOTES);
						$address = html_entity_decode($object['address'], ENT_QUOTES);
						//$object_customer_abonent = Like_Object_customabont($link, $object['id_object']);
						$paystatusabon = "";
						$id_object_ab = $object['id_object'];
						$object_customer_abonents = Show_objects_customer($link, $id_object_ab, $year, $month_start, $month_end, $paystatusabon);
						if($object_customer_abonents)
						{
							foreach($object_customer_abonents as $object_customer_abonent)
							{
								$row_next = $row_start + $rowplus;
								$abon_plata_1 = intval($object_customer_abonent['summ']);
								//$abon_plata_period = $abon_plata_1 * $month_period;
								$sheet->setCellValue('A'.$row_next, html_entity_decode($projects['projectname'], ENT_QUOTES));
								$sheet->setCellValue('B'.$row_next, $city_name);
								$sheet->setCellValue('C'.$row_next, $shop_number);
								$sheet->setCellValue('D'.$row_next, $address);
								$sheet->setCellValue('E'.$row_next, $months[$object_customer_abonent['month']-1]);
								$sheet->setCellValue('F'.$row_next, $abon_plata_1);
								$sheet->setCellValue('G'.$row_next, $paymentstatus_array[$object_customer_abonent['paystatus']]);
								$rowplus++;
								$cash_abplata_month_1 += $abon_plata_1;
							}
						}
					}
				}
			}
			$sheet->setCellValue('E'.($row_next+1), "ИТОГО:");
			$sheet->setCellValue('F'.($row_next+1), $cash_abplata_month_1);
			$sheet->getStyle('F'.($row_next+1))->getNumberFormat()->setFormatCode('# ### ##0.00');
		}
/*		
$style_wrap = array(
	//рамки
	'borders'=>array(
	//внешняя рамка
	'outline' => array(
	'style'=>PHPExcel_Style_Border::BORDER_THIN
	),
	//внутренняя
	'allborders'=>array(
	'style'=>PHPExcel_Style_Border::BORDER_THIN,
	'color' => array(
	'rgb'=>'696969'
	)
	)
	)
);
*/
//применяем массив стилей к ячейкам 
$sheet->getStyle('A6:G'.($row_next))->applyFromArray($style_wrap);

$style_header = array(
 //Шрифт
 'font'=>array(
 'bold' => true,
 'name' => 'Times New Roman',
 'size' => 11
 ),
//Выравнивание
 'alignment' => array(
 'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
 'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
 ),
//Заполнение цветом
 'fill' => array(
 'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
 'color'=>array(
 'rgb' => 'CFCFCF'
 )
 )
);
$style_right = array(
//Выравнивание
 'alignment' => array(
 'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
 'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
)
);
$style_left = array(
//Выравнивание
 'alignment' => array(
 'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
 'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
)
);
$style_center = array(
//Выравнивание
 'alignment' => array(
 'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
 'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
)
);



$sheet->getStyle('A6:G6')->applyFromArray($style_header);
$sheet->getStyle('A7:D'.($row_next))->applyFromArray($style_left);
$sheet->getStyle('E7:G'.($row_next+1))->applyFromArray($style_center);
$sheet->getStyle('E7:F'.($row_next+1))->getNumberFormat()->setFormatCode('# ### ##0.00');
$sheet->getStyle('B1:B5')->applyFromArray($style_left);
$sheet->getStyle('E'.($row_next+1).':F'.($row_next+1))->applyFromArray($style_header);
$sheet->getStyle('E'.($row_next+1).':F'.($row_next+1))->applyFromArray($style_wrap);
?>