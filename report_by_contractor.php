<?php
require '/connection/config.php';
ob_start();
require_once '/blocks/header.php';
require '/func/arrays.php';
// Подключаем класс для работы с excel
require_once('/Classes/PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
require_once('/Classes/PHPExcel/Writer/Excel5.php');
// Создаем объект класса PHPExcel
$xls = new PHPExcel();
$xls->createSheet();
$xls->createSheet();
$method_payment = "2"; //по-умолчанию, выбран способ платежа - безналичный
if(isset($_POST['method_payment']))
{
	$method_payment = trim(filter_input(INPUT_POST, 'method_payment'));
}
$contractors = Show_Contractor_Payment ($link, $method_payment);
$err = FALSE;
if(isset($_POST['contractor_report']))
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
	$errors=array();//массив сообшений ошибок
	if(empty($_POST['id_contractors']))
	{
		$errors[] = 'Не выбран ни один подрядчик!';
	}
	if($month_start > $month_end)
	{
		$errors[] = 'Неправильно выбран отчетный период!';
	}
if(empty($errors))
	{
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
		))));
		$style_header = array(
		//Шрифт
		'font'=>array(
		'bold' => true,
		'name' => 'Times New Roman',
		'size' => 11
		),
		//Выравнивание
		'alignment' => array(
		'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
		'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
		),
		//Заполнение цветом
		'fill' => array(
		'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
		'color'=>array(
		'rgb' => 'CFCFCF'
		)));
		$style_right = array(
		//Выравнивание
		'alignment' => array(
		'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_RIGHT,
		'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
		));
		$style_left = array(
		//Выравнивание
		'alignment' => array(
		'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
		'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
		));
		$style_center = array(
		//Выравнивание
		'alignment' => array(
		'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
		'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
		));
		require_once 'reports/report_contr_main.php';
		require_once 'reports/report_contr_abonent.php';
		require_once 'reports/report_contr_tickets.php';
		$xls->setActiveSheetIndex(0);
		
		ob_end_clean();
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=Отчет_по_подрядчикам_".$date.".xlsx");

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
		<title>Отчет по подрядчикам</title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
		<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
		<link rel="stylesheet" href="css/index.css">
	</head>
	<body>
		<div class="showany">
			<p class="breadcrumbs">Отчеты по подрядчикам</p>
			<div class="reg_sel_object">
			<?php if($err==TRUE){?>
			<div class="error-message"><?=array_shift($errors)?></div>
			<?php }?>			
			<table>
			<form action="" method="POST" enctype="multipart/form-data">
				<tr>		
				<td class="rowt">Выберите способ оплаты:</td>


				<td colspan="2">
						<select name="method_payment" id="method_payment" class="StyleSelectBox" onchange="this.form.submit()">
							<option  value="2" <?=($method_payment == 2 ? 'selected' :'')?>>Безналичный</option>
							<option  value="1"<?=($method_payment == 1 ? 'selected' :'')?>>Наличный</option>
						</select>
					</td>
					</tr>
				<tr>
					<td class="rowt">Выберите подрядчика:</td>

					<td colspan="2">
					<?php if($contractors) { 
						$contractors_count = count($contractors);?>
						<select class="reg_select" name="id_contractors[]" id="id_contractors"  multiple size="<?=$contractors_count?>">
							<?php foreach($contractors as $i => $contractor)  { 
							$city_name = get_geo($link, $contractor['city_id'], 'city', 'city_id');
							if($contractor['status']==0){
								$selected = 'disabled selected';
							}
							else $selected = '';
							?>
								<option  value="<?= $contractor['id_contractor']; ?>" <?=$selected;?>><?= $contractor['org_name'].' '.$contractor['ownership'].' ('.$city_name['name'].')'; ?></option>
							<?php  } ?>
						</select>
					<?php } else { ?>
						<span class="rowt">У вас не добавлено ни одного подрядчика!</span>
					<?php }?>
					</td>
				</tr>			
				<tr>
					<td class="rowt">Статус заявки:</td>
					<td colspan="2">
					<select class="reg_select" name="ticket_status" id="ticket_status">
						<option value="0">В работе</option>
						<option selected value="1">Закрыта</option>
						<option value="2">На согласовании</option>
					</select>
					</td>
				</tr>
				<tr>
					<td class="rowt">Отчетный год:</td>
					<td colspan="2">
						<select class="reg_select" name="year" id="year" >
						<?php for($i = 2015; $i < 2071; $i++) { ?>
						<option  value="<?=$i;?>" <?= ($i == date('Y')) ? 'selected' : ''?>><?=$i;?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="rowt">Отчетный период:</td>
					<td> с
					<select name="month_start" id="month" >
						<?php for($i = 1; $i < 13; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1]; ?></option>
						<?php } ?>
					</select>
					</td>
					<td>по
					<select name="month_end" id="month" >
						<?php for($i = 1; $i < 13; $i++) { ?>
							<option  value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : ''?>><?= $months[$i-1]; ?></option>
						<?php } ?>
					</select>
					</td>
				</tr>
				<tr>
				<td><button name="contractor_report" class="button-new">Создать отчет</button></td>
				</tr>
			
			
			
			</form>
			</table>
		</div>
	
	</body>
</html>