<?php
require '/connection/config.php';
ob_start();
require_once '/blocks/header.php';
require '/func/arrays.php';
// Подключаем класс для работы с excel
require_once('/Classes/PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
require_once('/Classes/PHPExcel/Writer/Excel5.php');
$err = false;
if(isset($_POST['report_contr_net']))
{
		$error = array();
		$country_id = trim(filter_input(INPUT_POST, 'country_id'));
		
		$region_id = trim(filter_input(INPUT_POST, 'region_id'));
		$city_id = trim(filter_input(INPUT_POST, 'city_id'));
		$status = trim(filter_input(INPUT_POST, 'status'));
		$request ="";
		$select_country = false;
		$select_region = false;
		$select_city = false;
		if(isset($country_id) AND $country_id>0){
			$request = "WHERE country_id=".$country_id;
			$select_country = true;
		}
		elseif($status <2){
			$request = "WHERE ";
			
		}
		
		if(isset($region_id) AND $region_id>0){
			$request .= " AND region_id=".$region_id;
			$select_region = true;
		}
		elseif( $select_country == true AND $status <2) $request .= " AND ";
		
		if(isset($city_id) AND $city_id>0){
			$request .= " AND city_id=".$city_id;
			$select_city = true;
			
		}
		elseif( $select_region == true AND $status <2) $request .= " AND ";
		
		
		
		if(isset($status) AND $status < 2 AND $select_city == true){
			$request .= " AND status = ".$status;
		}
		elseif(isset($status) AND $status < 2)
		{
			$request .= "status = ".$status;
		}
		elseif($status == 2) {$request .= "";}
		
		

	$contractors = Show_Contractor($link, $request);
	if($contractors){
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
	require_once 'reports/report_partner_net.php';

	ob_end_clean();
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header("Content-Type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename='report_partner_net.xlsx'");

	//Сохраняем файл с помощью PHPExcel_IOFactory и указываем тип Excel
	$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');

	//Отправляем файл
	$objWriter->save('php://output');
	exit;
	}
	else {
		$err=true;
				
		$errors[] = 'В данном регионе нет подрядчиков!';
		
	}
	
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Отчет "Партнерская сеть"</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/ico">
	<link rel="stylesheet" href="css/index.css">
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/selects.js'></script>
</head>
<body>
	<div class="showany">
	<p class="breadcrumbs">Отчет "Партерская сеть"</p>
	<div class="reg_sel_object">
		<?php if($err==true){?>
			<div class="error-message"><?=array_shift($errors)?></div>
			<?php }?>
		<form action="" method="POST" enctype="multipart/form-data">
		<table>
			<tr>
				<td class="rowt">Страна:</td>
				<td>
				<select name="country_id" id="country_id" class="StyleSelectBox">
					<option value="0">- выберите страну -</option>
					<option value="3159">Россия</option>
					
					<!--<option value="9908">Украина</option>
					<option value="248">Беларусь</option> -->
				</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Регион:</td>
				<td>
					<select name="region_id" id="region_id"  class="StyleSelectBox" >
						<option value="0">- выберите регион -</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="rowt">Населенный пункт:</td>
				<td>
					<select name="city_id" id="city_id" class="StyleSelectBox" >
						<option value="0">- выберите город -</option>
					</select>
				</td>
			</tr>
			<td class="rowt">Выберите статус подрядчика:</td>
				<td colspan="2">
					<select name="status" class="StyleSelectBox">
					<?php
					$i=0;
					foreach($statusedit as $status)
					{?>
						<option  value="<?=$i;?>" <?=$i==1 ? 'selected':''?>><?=$status;?></option>
					<?php 
					$i++; 
					}?>	
					</select>
				</td>
			</tr>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			</table>
			<div>
				<input class="button-new" type="submit" name="report_contr_net" value="Получить отчет"/>
			</div>
		</form>
	</div>
	</div>
</body>
</html>						