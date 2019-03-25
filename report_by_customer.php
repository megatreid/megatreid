<?php
require '/connection/config.php';
ob_start();
require_once 'blocks/header.php'; 
require '/func/arrays.php';

// Подключаем класс для работы с excel
require_once('/Classes/PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
require_once('/Classes/PHPExcel/Writer/Excel5.php');
// Создаем объект класса PHPExcel
$xls = new PHPExcel();
$xls->createSheet();
$xls->createSheet();
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
//$sheet->mergeCells('A2:C2');
$sheet->setCellValue('A2','Заказчик:');
//$sheet->mergeCells('A3:C3');
$sheet->setCellValue('A3','Отчетный год:');
//$sheet->mergeCells('A4:C4');
$sheet->setCellValue('A4','Отчетный период:');
$sheet->setCellValue('A5','Кол-во месяцев:');
//$sheet->mergeCells('A6:C6');
$sheet->setCellValue('A6','1.Абонентская плата:');
$date = date('d-m-Y');
$sheet->setCellValue('A1', 'Дата: ');
$sheet->setCellValue('B1', $date);
$customers = Show_Customer ($link);
$err = FALSE;
if(isset($_POST['id_customer']))
{
	$id_customer_selected = trim(filter_input(INPUT_POST, 'id_customer'));
	$customer_sel = Edit_Customer($link, $id_customer_selected);
}
if(isset($_POST['customer_report']))
{
	$ticket_status = trim(filter_input(INPUT_POST, 'ticket_status'));
	$year = trim(filter_input(INPUT_POST, 'year'));
	$month_start = trim(filter_input(INPUT_POST, 'month_start'));
	//echo $month_start;
	$month_start_name = $months[$month_start-1];
	$month_end = trim(filter_input(INPUT_POST, 'month_end'));
	//echo $month_end;
	$month_end_name = $months[$month_end-1];
	$month_period = ($month_end - $month_start) + 1;
	$ispolnitel = trim(filter_input(INPUT_POST, 'ispolnitel'));
	
	$errors=array();//массив сообшений ошибок
	if($month_start > $month_end)
		{
			$errors[] = 'Неправильно выбран отчетный период!';
		}
	if(empty($_POST['id_projects']))
	{
		$errors[] = 'Не выбран ни один проект!';
	}
	if(empty($errors))
	{
		if(isset($_POST['id_projects']))
		{
			$cash_summ = 0;
			$all_cost_in_project = 0;
			$row_start = 7;
			$rowplus = 0;
			$row_inc_plus = 0;
			$counter = 1;
			$cash_abplata_month_summ = 0;
			$all_cost_in_project_summ = 0;
			$row_incident = count($_POST['id_projects']) + 9;
			
			foreach($_POST['id_projects'] as $id_project)
			{
				$row_next = $row_start + $rowplus;
				
				
				$projects = Edit_Project ($link, $id_project);
				$objects = Show_Objects ($link, $id_project);

				//echo "Проект: ".$projects['projectname'];

				if($objects)
				{
					$cash_abplata_month = 0; //Месячная абонплата
					
					$all_cost_in_project = 0;
					foreach($objects as $object)
					{
						
						$odject_arr = $object['id_object'];
						$abon_plata = $object['abon_plata'];

						//$abon_plata = (int)$abon_plata;
						$cash_abplata_month = $abon_plata * $month_period;
						$cash_abplata_month_summ += intval($cash_abplata_month); //Сумма месячных абонплат со всех объектов одного проекта
						$rep_tickets = Show_Rep_Tickets ($link, $odject_arr, $year, $ticket_status, $month_start, $month_end);
						
						$k=0;
						if($rep_tickets)
						{
							
							foreach($rep_tickets as $rep_ticket)
							{
								
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
								$hours = intval($rep_ticket['hours']);
								$sla = intval($rep_ticket['ticket_sla']);
								$cost_hour = $hours * intval($projects['cost_hour']);
								$cost_smeta = intval($rep_ticket['cost_smeta']);
								$cost_material = intval($rep_ticket['cost_material']);
								$cost_transport = intval($rep_ticket['cost_transport']);
								$all_cost_in_project += ($cost_incident + $cost_hour + $cost_smeta + $cost_material + $cost_transport);
								$k++;
								//echo $k.".".$cost_incident."+".$cost_hour."+".$cost_smeta."+".$cost_material."+".$cost_transport."=".$all_cost_in_project."<br>";
							
							}
							
						}
					}
				
					$all_cost_in_project_summ += $all_cost_in_project;
					//echo "ИТОГО: ".$all_cost_in_project_summ;
					$sheet->setCellValue('A'.($row_incident + $counter), '2. '.$counter.' '.$projects['projectname']);
					$sheet->setCellValue('B'.($row_incident + $counter), $all_cost_in_project);
					$counter++;	
					
					$cash_summ += $cash_abplata_month_summ;
				}
										
				$sheet->setCellValue('A'.$row_next, "1.".($rowplus + 1)." ".$projects['projectname']);
				$sheet->setCellValue('B'.$row_next, $cash_abplata_month_summ);
				$rowplus++;
				
				$cash_abplata_month_summ = 0;
				//echo ". Абонентская плата: ".$cash_abplata_month." руб. за ".$month_period." месяцев.";
				//echo "<br>";
			}
			//$row_incident = $row_itog + 6;
			$row_itog = $row_next + 1;
			$sheet->setCellValue('A'.($row_itog),'ИТОГО:');
			$sheet->setCellValue('B'.($row_itog), $cash_summ);

			$sheet->setCellValue('A'.($row_incident + $counter),'ИТОГО:');
			$sheet->setCellValue('B'.($row_incident + $counter), $all_cost_in_project_summ);
			
			$to_pay = $cash_summ + $all_cost_in_project_summ;
			$sheet->setCellValue('A'.($row_incident + $counter + 2),'К ОПЛАТЕ:');
			$sheet->setCellValue('B'.($row_incident + $counter + 2), $to_pay);
			
			//echo "ИТОГО: ". $cash_summ. " рублей.";
		}
		$sheet->setCellValue('B2',$customer_sel['customer_name']);
		$sheet->setCellValue('B3',$year);
		$sheet->setCellValue('B4',($month_start_name." - ".$month_end_name));
		$sheet->setCellValue('B5',($month_period));
		$sheet->setCellValue('A'.($row_incident), '2. Затраты по заявкам');
		//$sheet->setCellValueByColumnAndRow(0, 1, $customer_name['customer_name']);
		//массив стилей
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
//применяем массив стилей к ячейкам 
$sheet->getStyle('A6:B'.($row_incident + $counter))->applyFromArray($style_wrap);
$style_header = array(
 //Шрифт
 'font'=>array(
 'bold' => true,
 'name' => 'Times New Roman',
 'size' => 11
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
$style_number_00 = array(
	'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00,
);
$sheet->getStyle('A'.($row_itog).':B'.($row_itog))->applyFromArray($style_header);
$sheet->getStyle('A'.($row_incident + $counter).':B'.($row_incident + $counter))->applyFromArray($style_header);
$sheet->getStyle('A'.($row_itog).':A'.($row_itog))->applyFromArray($style_right);
$sheet->getStyle('A'.($row_incident + $counter).':A'.($row_incident + $counter))->applyFromArray($style_right);
$sheet->getStyle('A'.($row_incident + $counter + 2).':A'.($row_incident + $counter + 2))->applyFromArray($style_right);
$sheet->getStyle('B1:B'.($row_incident + $counter + 2))->applyFromArray($style_center);
$sheet->getStyle('A'.($row_incident + $counter + 2).':B'.($row_incident + $counter + 2))->applyFromArray($style_header);
$sheet->getStyle('A'.($row_incident + $counter + 2).':B'.($row_incident + $counter + 2))->applyFromArray($style_wrap);

$sheet->getStyle('B6:B'.($row_incident + $counter + 2))->getNumberFormat()->setFormatCode('# ### ##0.00');
require_once 'reports/report_abonent.php';
require_once 'reports/report_ticket.php';
$xls->setActiveSheetIndex(0);

		ob_end_clean();
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=Отчет_заказчик_".$customer_sel['customer_name']."_".$date.".xlsx");

		//Сохраняем файл с помощью PHPExcel_IOFactory и указываем тип Excel
		$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');

		//Отправляем файл
		$objWriter->save('php://output');
		exit;

	}
	else
	{
		$err=TRUE;
	}	
}

?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Отчет по заказчику</title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
		<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
		<link rel="stylesheet" href="css/index.css">
	</head>
	<body>
		<div class="showany">
		<p class="breadcrumbs">Отчет по заказчику</p>
		<div class="reg_sel_object">
			<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
			<?php }?>
			<br>
			<form action="" method="POST"  enctype="multipart/form-data">
			<table>
				<tr>
					<td class="rowt">Выберите заказчика:</td>
					<td colspan="2">
						<select name="id_customer" id="id_customer" class="StyleSelectBox"  onchange="this.form.submit()">
							<option value="0">Выберите заказчика</option>
							<?php foreach($customers as $i => $customer)  { ?>
								<?php if($id_customer_selected){
									$customer_sel = Edit_Customer($link, $id_customer_selected);
									$customer_name = Edit_Customer($link, $customer['id_customer']);
									?>
									<option  value="<?= $customer['id_customer']; ?>"<?= ($customer['id_customer'] == $customer_sel['id_customer']) ? 'selected' : ''?>>
										<?= $customer_name['customer_name']; ?>
									</option>
								<?php }?>
								<?php if(!$id_customer_selected){?>
									<option  value="<?= $customer['id_customer']; ?>"><?= $customer['customer_name']; ?></option>
								<?php }?>									
							<?php } ?>
						</select>
					</td>
				</tr>
				<?php if(isset($id_customer_selected) AND $id_customer_selected!=0){
					$projects = Show_Projects ($link, $id_customer_selected);
				?>
				<tr>
					<td class="rowt">Статус заявки:</td>
					<td colspan="2">
					<select class="reg_select" name="ticket_status" id="ticket_status">
						<option  value="0">В работе</option>
						<option selected value="1">Закрыта</option>
						<option value="2">На согласовании</option>
					</select>
					</td>
				</tr>

				<tr>
					<td class="rowt">Отчетный год:</td>
					<td colspan="2">
						<select class="reg_select" name="year" id="year" >
						<?php for($i = 2015; $i < 2050; $i++) { ?>
						<option  value="<?=$i;?>" <?= ($i == date('Y')) ? 'selected' : ''?>><?=$i;?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="rowt">Отчетный период:</td> <!-- **********************ВЫБОР МЕСЯЦА***********************-->
					<td> с
					<select name="month_start" id="month" >
						<?php for($i = 1; $i < 13; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
						<?php } ?>
					</select>
					</td>
					<td>по
					<select name="month_end" id="month">
						<?php for($i = 1; $i < 13; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1] ?></option>
						<?php } ?>
					</select>
					</td>
				</tr>


				<tr>
					<td class="rowt">Выберите проекты:</td>
					<td colspan="2">
					<?php if($projects) { 
						$project_count = count($projects);?>
						<select name="id_projects[]" id="id_projects"  multiple size="<?=$project_count?>">
							<?php foreach($projects as $i => $project)  { ?>
								<option  value="<?= $project['id_project']; ?>"><?= $project['projectname']; ?></option>
							<?php  } ?>
						</select>
					<?php } else { ?>
						<span class="rowt">У данного заказчика нет проектов</span>
						
					<?php }?>
					</td>

				</tr>
				
				<tr>
				<td><p><button name="customer_report" class="button-new">Создать отчет</button></p></td>
				<td colspan="2"><p class="rowt_left"><input type="checkbox" name="ispolnitel" value="yes">Добавить исполнителей в отчет по заявкам</p></td>
				</tr>
				<?php }?>
			</form>
			</table>
		</div>
		</div>
	</body>
</html>