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






?>