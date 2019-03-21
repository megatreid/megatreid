<?php
require '/connection/config.php';
require '/func/arrays.php';
// Подключаем класс для работы с excel
require_once('/Classes/PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
require_once('/Classes/PHPExcel/Writer/Excel5.php');
// Создаем объект класса PHPExcel
$xls = new PHPExcel();
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(1);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
// Подписываем лист
$sheet->setTitle('Главная страница отчета');

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




$customers = Show_Customer ($link);
$err = FALSE;
if(isset($_POST['id_customer']))
{
	$id_customer_selected = trim(filter_input(INPUT_POST, 'id_customer'));
	$customer_sel = Edit_Customer($link, $id_customer_selected);
}
if(isset($_POST['customer_report']))
{
	$month_start = trim(filter_input(INPUT_POST, 'month_start'));
	$month_end = trim(filter_input(INPUT_POST, 'month_end'));	
	$month_period = ($month_end - $month_start) + 1;
	
	
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
			foreach($_POST['id_projects'] as $id_project)
			{
				$projects = Edit_Project ($link, $id_project);
				$objects = Show_Objects ($link, $id_project);

				echo "Проект: ".$projects['projectname'];
				if($objects)
				{
					$cash_abplata_month = 0; //Месячная абонеплата
					
					foreach($objects as $object)
					{
						$odject_arr = $object['id_object'];
						$abon_plata = $object['abon_plata'];

						$abon_plata = (int)$abon_plata;

						$cash_abplata_month += intval($abon_plata); //Сумма месячных абонплат со всех объектов одного проекта
						$cash_abplata_month = $cash_abplata_month * $month_period;
						$rep_tickets = Show_Rep_Tickets ($link, $odject_arr);
						if($rep_tickets)
						{
							foreach($rep_tickets as $rep_ticket)
							{
								echo "Объект: ".$object['shop_number']." Заявка: ".$rep_ticket['ticket_number']."<br>";
							}	
						}
					}
					$cash_summ += $cash_abplata_month;
				}
				echo ". Абонентская плата: ".$cash_abplata_month." руб. за ".$month_period." месяцев.";
				echo "<br>";
			}
			echo "ИТОГО: ". $cash_summ. " рублей.";
		}
	header("Content-Type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename='report.xlsx'");

	//Сохраняем файл с помощью PHPExcel_IOFactory и указываем тип Excel
	$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');

	//Отправляем файл
	$objWriter->save('php://output');
	}
	else
	{
		$err=TRUE;
	}	
}	

?>