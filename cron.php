<?php
require 'd:/openserver/domains/megatreid/connection/config_for_cron.php';
$ticket_status_array = array('В работе', 'Закрыта','На согласовании', 'Все заявки');
$days = 4;
$date = date("Y-m-d H-i-s");
$filename = 'data'.$date.'.txt';
$body = "Эти заявки заведены более ".$days." дней назад: \r\n";
$mail_subject = "Старые заявки";
//$to = "skirko-vn@mega-treid.com";
$to = "wolf_s2003@mail.ru";
//$mail_headers="content-type:text/html; charset=windows-1251";
$tickets_to_mail = ToMail_Tickets($link, $days);
if($tickets_to_mail)
{
	foreach($tickets_to_mail as $k => $ticket_to_mail) {
		$objects = Edit_Object ($link, $ticket_to_mail['id_object']);
		$city = Get_Geo ($link, $objects['city_id'], "city", "city_id");
		$projects = Edit_Project ($link, $objects['id_project']);
		$customers = Edit_Customer ($link, $objects['id_customer']);	
		$body .= "Номер заявки: ".$ticket_to_mail['ticket_number'].". Заказчик: ".html_entity_decode($customers['customer_name'], ENT_QUOTES).". Проект: ".
		html_entity_decode($projects['projectname'], ENT_QUOTES).". Город: ".html_entity_decode($city['name'], ENT_QUOTES).". Объект: ".
		html_entity_decode($objects['shop_number'], ENT_QUOTES).". Адрес: ".html_entity_decode($objects['address'], ENT_QUOTES).". Статус: ".$ticket_status_array[$ticket_to_mail['ticket_status']]."\r\n";
	}
	$f_hdl = fopen($filename, 'w');
	fwrite($f_hdl, $body);
	fclose($f_hdl);
	mail ($to, $mail_subject, $body);
}
exit;
?>