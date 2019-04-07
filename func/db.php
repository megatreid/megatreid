<?php
/*
function wolfs2003_exist($connection)
{
    $select_query = "SELECT * FROM users WHERE login='wolfs2003' AND surname='Скирко' AND name='Сергей' AND th_name='Николаевич'";	
	$result=$connection->query ($select_query);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows)
	{
		$add_query ="INSERT INTO Users VALUES(NULL, 'wolfs2003','c1c6aa010d50d67b2d524072034b64bb','Скирко', 'Сергей','Николаевич','skirko_sn@mail.ru','+7(930)701-41-37', '1')";
		$result = $connection->query($add_query); 
		if ($result) 
            return true;
        else
            die ($connection->error);
	//return 0;
	}
	mysqli_close($connection);
}
*/
/*
function skirko_vn_exist($connection)
{
    $select_query = "SELECT * FROM users WHERE login='skirko_vn' AND surname='Скирко' AND name='Владимир' AND th_name='Николаевич'";	
	$result=$connection->query ($select_query);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows)
	{
		$add_query ="INSERT INTO Users VALUES(NULL, 'skirko_vn','2b93067e90cdd4b261179e0cfc28a6a7','Скирко', 'Владимир','Николаевич','skirko-vn@mega-treid.com','+7(920)070-09-70', '1')";
		$result = $connection->query($add_query); 
		if ($result) 
            return true;
        else
            die ($connection->error);
	//return 0;
	}
	mysqli_close($connection);
}
*/




// Обрезание текста

function Cut($string, $length)
{
	$string = mb_substr($string, 0, $length,'UTF-8'); // обрезаем и работаем со всеми кодировками и указываем исходную кодировку
	$position = mb_strrpos($string, ' ', 'UTF-8'); // определение позиции последнего пробела. Именно по нему и разделяем слова
	$string = mb_substr($string, 0, $position, 'UTF-8'); // Обрезаем переменную по позиции
	return $string;
}

/****************************ПОЛЬЗОВАТЕЛИ**********************************/
// Добавление ПОЛЬЗОВАТЕЛЯ
function Add_User($connection, $login, $password, $surname, $name, $th_name, $email, $mobile, $userlevel)
{

		$add_query ="INSERT INTO Users VALUES(NULL, '$login','$password','$surname', '$name','$th_name','$email','$mobile', '$userlevel')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connect->error);
    
}
function Login_Exist($connection, $var)
{
    $select_query = "SELECT login FROM users WHERE login='$var'";	
	$result=$connection->query ($select_query);
    $rows = $result->fetch_array(MYSQLI_ASSOC);
    if ($rows) return $rows;
    else
       return 0;
	mysqli_close($link);
}

function Email_Exist($connection, $var)
{
    $checkmail= "SELECT * FROM users WHERE email='$var'";	
	$result = $connection->query ($checkmail);
    $rows = $result->fetch_array(MYSQLI_ASSOC);
    if ($rows) return $rows;
    else
        return 0;
}

function User_Exist($connection, $var)
{
    $checkuser = "SELECT * FROM users WHERE login='$var' OR email='$var'";
    $result = $connection->query ($checkuser);
    $rows = $result->fetch_array(MYSQLI_ASSOC);
    if ($rows) return $rows;
    else
        return 0;
}




// Редактирование ПОЛЬЗОВАТЕЛЯ

function Edit_User($connection, $var) // Принимает подключение и id
{
    $search = "SELECT * FROM Users WHERE id_users = '$var'";
    $result = $connection->query ($search);
    if ($result)
    {
        $rows = $result->fetch_array (MYSQLI_ASSOC);
    }
    if ($rows) return $rows;
    else return 0;
}

function Show_Users($connection) // Принимает подключение и возвращает массив пользователей
{

	$search = "SELECT * FROM users ORDER BY surname, name, th_name ASC";

    //$search = "SELECT * FROM Users";
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}

function Show_Users_Level($connection, $var) // Принимает подключение и возвращает массив пользователей
{
	$search = "SELECT * FROM users WHERE userlevel='$var' ORDER BY surname, name, th_name ASC";
    $result = $connection->query ($search);
    if (!$search) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}

function Add_Tickets_User($connection, $id_ticket, $id_user)
{
		$add_query ="INSERT INTO ticketsingener VALUES(NULL, '$id_ticket', '$id_user')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connect->error);
}

function update_user($connection, $id_users, $login, $password, $surname, $name, $th_name, $email, $mobile, $userlevel)
{
    $update = "UPDATE users SET login='$login', pass='$password', email='$email', name='$name', surname='$surname', th_name='$th_name', mobile='$mobile', userlevel='$userlevel'  WHERE id_users='$id_users'";
    $result = $connection->query ($update);
    if ($result) return true;
    else
        die ($connect->error);
	mysqli_close($link);
}
// Удаление ПОЛЬЗОВАТЕЛЯ

function Delete_User ($connection, $var) // Принимает подключение и id
{
    $delete_query = "DELETE FROM Users WHERE id_users = '$var'";
    $result = $connection->query ($delete_query);
    if ($result) return true;
    else
        die ($connect->error); // TODO: Каскадное удаление сообщений из личного форума
}
/****************************** ПОДРЯДЧИКИ **************************/
/*
function Show_Contr($connection, $geo_table, $geo_row, $geo_search, $art, $kol) 
{
	if(isset($geo_search) AND $geo_table!="3" AND $geo_row!="3"){
	$search = "SELECT * FROM contractor WHERE $geo_row IN (SELECT $geo_row FROM $geo_table WHERE name LIKE '%$geo_search%') LIMIT $art, $kol ";
	}
	elseif(isset($geo_search) AND $geo_table=="3" AND $geo_row=="3"){
	$search = "SELECT * FROM contractor WHERE org_name LIKE '%$geo_search%' LIMIT $art, $kol ORDER BY city_id ASC";
	}
	elseif(!isset($geo_search)) {
	$search = "SELECT * FROM `contractor` ORDER BY city_id DESC LIMIT $art, $kol";
	}
    
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}
*/
function Show_Contr_for_select($connection) 
{

	$search = "SELECT DISTINCT city_id, status  FROM contractor ORDER BY city_id ASC";
    
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}
function Show_Contractor($connection, $var) 
{

	$search = "SELECT *  FROM contractor $var ORDER BY country_id, region_id, city_id, status ASC";
    
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}




function Add_Contr($connection, $country_id, $region_id, $city_id, $org_name, $status, $dogovor, $method_payment, $card_number, $anketa, $ownership, $system_no, $contact_name, $passport, $mobile, $phone, $email, $web, $comment)
{

		$add_query ="INSERT INTO contractor VALUES(NULL, '$country_id', '$region_id', '$city_id', '$org_name', '$status', '$dogovor', '$method_payment', '$card_number', '$anketa', '$ownership', '$system_no', '$contact_name', '$passport', '$mobile', '$phone', '$email', '$web', '$comment')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connection->error);
    
}
function Get_Geo($connection, $id, $geo_table, $geo_row)
{
	$get_geo = "SELECT name FROM $geo_table WHERE $geo_row='$id'";
    $result = $connection->query ($get_geo);
    if ($result)
    {
        $rows = $result->fetch_array (MYSQLI_ASSOC);
    }
    if ($rows) return $rows;
    else return 0;
}
function Edit_Contr($connection, $var)
{
    $search = "SELECT * FROM contractor WHERE id_contractor = '$var'";
    $result = $connection->query ($search);
    if ($result)
    {
        $rows = $result->fetch_array (MYSQLI_ASSOC);
    }
    if ($rows) return $rows;
    else return 0;
}
function Update_Contr($connection, $id_contractor, $country_id, $region_id, $city_id, $org_name, $status, $dogovor, $method_payment, $card_number, $anketa, $ownership, $system_no, $contact_name, $passport, $mobile, $phone, $email, $web, $comment)
{
	$update = "UPDATE `contractor` SET `country_id`=$country_id,`region_id`=$region_id,`city_id`=$city_id,`org_name`='$org_name', `status`='$status',`dogovor`='$dogovor', `method_payment`='$method_payment', `card_number`='$card_number', `anketa`='$anketa',`ownership`='$ownership',`system_no`='$system_no',`contact_name`='$contact_name', `passport`='$passport', `mobile`='$mobile',`phone`='$phone',`email`='$email',`web`='$web',`comment`='$comment' WHERE `id_contractor`='$id_contractor'";

    $result = $connection->query ($update);
    if ($result) return true;
    else
        die ($connection->error);
	mysqli_close($link);
}	
function Contr_Count($connection, $geo_table, $geo_row, $geo_search)
{
	if(isset($geo_search) AND $geo_table!="3" AND $geo_row!="3"){
	$search = "SELECT * FROM contractor WHERE $geo_row IN (SELECT $geo_row FROM $geo_table WHERE name LIKE '%$geo_search%')";
	}
	elseif(isset($geo_search) AND $geo_table=="3" AND $geo_row=="3"){
	$search = "SELECT * FROM contractor WHERE org_name LIKE '%$geo_search%'";
	}
	elseif(!isset($geo_search)) {
	$search = "SELECT * FROM `contractor`";
	}
    $result = $connection->query ($search);
	

    if (!$result) die ($connect->error);
    $sum = $result->num_rows;
    return $sum; 
}
function Delete_Contr ($connection, $var)
{
    $delete_query = "DELETE FROM contractor WHERE id_contractor = '$var'";
    $result = $connection->query ($delete_query);
    if ($result) return true;
    else
        die ($connect->error);
}	
/***************************ЗАКАЗЧИКИ*****************************/
function Show_Customer($connection) 
{
	$search = "SELECT * FROM customer ORDER BY customer_name ASC";

    $result = $connection->query ($search);
    if (!$result) die ($connect->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}
function Show_Customer_Active($connection, $status) 
{
	$search = "SELECT * FROM customer WHERE status='$status' ORDER BY customer_name ASC";

    $result = $connection->query ($search);
    if (!$result) die ($connect->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}
function Add_Customer($connection, $customer, $jur_address, $post_address, $ogrn, $inn, $kpp, $dogovor_number, $status, $bank_name, $bank_bik, $korr_schet, $rasch_schet, $recipient, $phone, $email, $contact_name, $comment)
{

		$add_query ="INSERT INTO customer VALUES(NULL, '$customer','$jur_address', '$post_address', '$ogrn', '$inn', '$kpp', '$dogovor_number', '$status', '$bank_name', '$bank_bik', '$korr_schet', '$rasch_schet', '$recipient', '$phone', '$email', '$contact_name', '$comment')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connection->error);
    
}
function Edit_Customer($connection, $var)
{
    $search = "SELECT * FROM customer WHERE id_customer = '$var'";
    $result = $connection->query ($search);
    if ($result)
    {
        $rows = $result->fetch_array (MYSQLI_ASSOC);
    }
    if ($rows) return $rows;
    else return 0;
}
function Update_Customer($connection, $id_customer, $customer_name, $jur_address, $post_address, $ogrn, $inn, $kpp, $dogovor_number, $status, $bank_name, $bank_bik, $korr_schet, $rasch_schet, $recipient, $phone, $email, $contact_name, $comment)
{
	$update = "UPDATE `customer` SET `customer_name`='$customer_name',`jur_address`='$jur_address',`post_address`='$post_address',`ogrn`='$ogrn', `inn`='$inn', `kpp`='$kpp', `dogovor_number`='$dogovor_number', `status`='$status',`bank_name`='$bank_name',`bank_bik`='$bank_bik',`korr_schet`='$korr_schet',`rasch_schet`='$rasch_schet',`recipient`='$recipient',`phone`='$phone',`email`='$email', `comment`='$comment' WHERE `id_customer`='$id_customer'";

    $result = $connection->query ($update);
    if ($result) return true;
    else
        die ($connection->error);
	mysqli_close($link);
}
function Delete_Customer($connection, $var)
{
	$delete_object = "DELETE FROM object WHERE id_customer = '$var'";
	$result_del_obj = $connection->query ($delete_object);
	
	if ($result_del_obj)
	{
		$delete_project = "DELETE FROM projects WHERE id_customer = '$var'";
		$result_del_proj = $connection->query ($delete_project);
		
		if ($result_del_proj)
		{
			$delete_customer = "DELETE FROM customer WHERE id_customer = '$var'";
			$result_del_customer = $connection->query ($delete_customer);
		}
		else
			die ($connection->error);
	}
    else
		die ($connection->error);
	
	if($result_del_customer) return true;
	else
		die ($connection->error);
}
/*************************ПРОЕКТЫ****************************/
function Show_Projects($connection, $var) 
{

	$search = "SELECT * FROM projects WHERE id_customer='$var' ORDER BY projectname, status ASC";

    //$search = "SELECT * FROM Users";
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}	
function Add_Project($connection, $id_customer, $projectname, $status, $cost_hour, $cost_incident_critical, $cost_incident_high, $cost_incident_medium, $cost_incident_low)
{

		$add_query ="INSERT INTO projects VALUES(NULL, '$id_customer','$projectname', '$status', '$cost_hour', '$cost_incident_critical', '$cost_incident_high', '$cost_incident_medium', '$cost_incident_low')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connection->error);
    
}
function Edit_Project($connection, $var)
{
    $search = "SELECT * FROM projects WHERE id_project = '$var'";
    $result = $connection->query ($search);
    if ($result)
    {
        $rows = $result->fetch_array (MYSQLI_ASSOC);
    }
    if ($rows) return $rows;
    else return 0;
}
function Update_Project($connection, $id_project, $id_customer, $projectname, $status, $cost_hour, $cost_incident_critical, $cost_incident_high, $cost_incident_medium, $cost_incident_low)
{
    $update = "UPDATE projects SET id_customer='$id_customer', projectname='$projectname', status='$status', cost_hour='$cost_hour', cost_incident_critical='$cost_incident_critical', cost_incident_high='$cost_incident_high', cost_incident_medium='$cost_incident_medium', cost_incident_low='$cost_incident_low' WHERE id_project='$id_project'";
    $result = $connection->query ($update);
    if ($result) return true;
    else
        die ($connection->error);
	mysqli_close($link);
}
function Delete_Project($connection, $var)
{
    $delete_object = "DELETE FROM object WHERE id_project = '$var'";
	$result_obj = $connection->query ($delete_object);
    if ($result_obj){
		$delete_project = "DELETE FROM projects WHERE id_project = '$var'";
		$result_prj = $connection->query ($delete_project);
	}
	else
        die ($connection->error);
	if ($result_prj) return true;
	else
        die ($connection->error);

}
/*************ОБЪЕКТЫ******************/
function Show_Objects($connection, $var) 
{
	$search = "SELECT * FROM object WHERE id_project='$var'";
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        } 
    return $array; 		
    }

}
function Show_Objects_for_search($connection, $var)
{
	$search = "SELECT DISTINCT city_id, city_name FROM object WHERE id_project='$var' AND status='1'";
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        } 
    return $array; 		
    }

}
function Show_Objects_abon($connection)
{
	$search = "SELECT * FROM object WHERE (abon_plata > '0') OR (abon_plata_contr > '0') ORDER BY id_customer ASC" ;
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        } 
    return $array; 		
    }
}
	
function Add_Object($connection, $id_project, $id_customer, $country_id, $region_id, $city_id, $shop_number, $address, $status, $abon_plata, $id_contractor, $abon_plata_contr)
{
		$city = Get_Geo ($connection, $city_id, "city", "city_id" );
		$city_name = $city['name'];
		$add_query ="INSERT INTO object VALUES(NULL, '$id_project', '$id_customer', '$country_id', '$region_id', '$city_id', '$shop_number', '$address', '$status', '$abon_plata', '$city_name', '$id_contractor', '$abon_plata_contr')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connection->error);
    
}
function Edit_Object($connection, $var)
{
    $search = "SELECT * FROM object WHERE id_object = '$var'";
    $result = $connection->query ($search);
    if ($result)
    {
        $rows = $result->fetch_array (MYSQLI_ASSOC);
    }
    if ($rows) return $rows;
    else return 0;
}
function Update_Object($connection, $id_object, $id_project, $id_customer, $country_id, $region_id, $city_id, $shop_number, $address, $status, $abon_plata, $id_contractor, $abon_plata_contr)
{
	$city = Get_Geo ($connection, $city_id, "city", "city_id" );
	$city_name = $city['name'];
    $update = "UPDATE object SET id_project='$id_project', id_customer='$id_customer', country_id='$country_id', region_id='$region_id', city_id='$city_id', shop_number='$shop_number', address='$address', status='$status', abon_plata='$abon_plata', city_name='$city_name', id_contractor=$id_contractor, abon_plata_contr=$abon_plata_contr  WHERE id_object='$id_object'";
    $result = $connection->query ($update);
    if ($result) return true;
    else
        die ($connection->error);
	mysqli_close($link);
}
function Delete_Object ($connection, $var)
{
	$delete_object = "DELETE FROM object WHERE id_object = '$var'";
    $result = $connection->query ($delete_object);
    if ($result) return true;
    else
        die ($connection->error);
}

/********************************ЗАЯВКИ*****************************************/

function Add_Ticket($connection, $ticket_number, $year, $month, $id_object, $ticket_date, $ticket_task, $ticket_solution, $ticket_material, $ticket_status, $ticket_sla, $work_type, $hours, $cost_smeta, $cost_material, $cost_transport, $customer_account_number, $customer_date_payment, $customer_payment_status, $comment, $last_edit_datetime, $last_edit_user_id, $implementer, $engineer, $id_contractor, $contr_cost_work, $contr_cost_smeta, $contr_cost_transport, $contr_material, $contr_cost_material, $contr_account_number, $contr_date_payment, $contr_payment_status, $contr_comment, $supplier, $supplier_cost_work, $supplier_contr_material, $supplier_cost_material, $supplier_account_number, $supplier_date_payment, $supplier_payment_status, $supplier_comment)
{

		$add_query ="INSERT INTO tickets VALUES(NULL, '$ticket_number', '$year', '$month', '$id_object', '$ticket_date', '$ticket_task', '$ticket_solution', '$ticket_material', '$ticket_status', '$ticket_sla', '$work_type', '$hours', '$cost_smeta', '$cost_material', '$cost_transport', '$customer_account_number', '$customer_date_payment', '$customer_payment_status', '$comment', '$last_edit_datetime', '$last_edit_user_id', '$implementer', '$engineer', '$id_contractor', '$contr_cost_work', '$contr_cost_smeta', '$contr_cost_transport', '$contr_material', '$contr_cost_material', '$contr_account_number', '$contr_date_payment', '$contr_payment_status', '$contr_comment', '$supplier', '$supplier_cost_work', '$supplier_contr_material', '$supplier_cost_material', '$supplier_account_number', '$supplier_date_payment', '$supplier_payment_status', '$supplier_comment')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connection->error);
    
}

function Show_Tickets($connection, $ticket_status, $pay_status_select, $method_payment, $current_month, $implementer)
{
	$search = "SELECT id_ticket, ticket_number, ticket_date, year, month, id_object, ticket_status, implementer, id_engineers, id_contractor, contr_payment_status, last_edit_datetime, last_edit_user_id FROM tickets WHERE (`ticket_status` LIKE '%$ticket_status%') $pay_status_select AND (`month` LIKE '%$current_month%') AND (`implementer` LIKE '%$implementer%') $method_payment ORDER BY id_ticket DESC";
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        } 
    return $array; 		
    }

}

function Edit_Ticket($connection, $var)
{
    $search = "SELECT * FROM tickets WHERE id_ticket = '$var'";
    $result = $connection->query ($search);
    if ($result)
    {
        $rows = $result->fetch_array (MYSQLI_ASSOC);
    }
    if ($rows) return $rows;
    else return 0;
}
function Update_Ticket($connection, $id_ticket, $ticket_number, $year, $month, $ticket_task, $ticket_solution, $ticket_material, $ticket_status, $ticket_sla, $work_type, $hours, $cost_smeta, $cost_material, $cost_transport, $customer_account_number, $customer_date_payment, $customer_payment_status, $comment, $last_edit_datetime, $last_edit_user_id, $implementer, $id_engineers_array, $id_contractor, $contr_cost_work, $contr_cost_smeta, $contr_cost_transport, $contr_material, $contr_cost_material, $contr_account_number, $contr_date_payment, $contr_payment_status, $contr_comment, $supplier, $supplier_cost_work, $supplier_contr_material, $supplier_cost_material, $supplier_account_number, $supplier_date_payment, $supplier_payment_status, $supplier_comment)
{
	$update = "UPDATE `tickets` SET 
	`ticket_number`='$ticket_number',
	`year`='$year',
	`month`='$month',
	`ticket_task`='$ticket_task',
	`ticket_solution`='$ticket_solution',
	`ticket_material`='$ticket_material',
	`ticket_status`='$ticket_status',
	`ticket_sla`='$ticket_sla',
	`work_type`='$work_type',
	`hours`='$hours',
	`cost_smeta`='$cost_smeta',
	`cost_material`='$cost_material',
	`cost_transport`='$cost_transport',
	`customer_account_number`='$customer_account_number',
	`customer_date_payment`='$customer_date_payment', 
	`customer_payment_status`='$customer_payment_status',
	`comment`='$comment',
	`last_edit_datetime`='$last_edit_datetime',
	`last_edit_user_id`='$last_edit_user_id',
	`implementer`='$implementer',
	`id_engineers`='$id_engineers_array',
	`id_contractor`='$id_contractor',
	`contr_cost_work`='$contr_cost_work',
	`contr_cost_smeta`='$contr_cost_smeta',
	`contr_cost_transport`='$contr_cost_transport',
	`contr_material`='$contr_material',
	`contr_cost_material`='$contr_cost_material',
	`contr_account_number`='$contr_account_number',
	`contr_date_payment`='$contr_date_payment',
	`contr_payment_status`='$contr_payment_status',
	`contr_comment`='$contr_comment',
	`supplier`='$supplier',
	`supplier_cost_work`='$supplier_cost_work',
	`supplier_contr_material`='$supplier_contr_material',
	`supplier_cost_material`='$supplier_cost_material',
	`supplier_account_number`='$supplier_account_number',
	`supplier_date_payment`='$supplier_date_payment',
	`supplier_payment_status`='$supplier_payment_status',
	`supplier_comment`='$supplier_comment' 
	WHERE `id_ticket`='$id_ticket'";

    $result = $connection->query ($update);
    if ($result) return true;
    else
        die ($connection->error);
	mysqli_close($link);
}
function Delete_Ticket($connection, $var)
{
	$delete_object = "DELETE FROM tickets WHERE id_ticket = '$var'";
    $result = $connection->query ($delete_object);
    if ($result) return true;
    else
        die ($connection->error);
}

function Ticket_Exist($connection, $ticket_number)
{
    $checkuser = "SELECT ticket_number FROM tickets WHERE ticket_number='$ticket_number'";
    $result = $connection->query ($checkuser);
    $rows = $result->fetch_array(MYSQLI_ASSOC);
    if ($rows) return $rows;
    else
        return 0;
}

/**********************************************************************/
function Show_Region($connection, $country_id) 
{

	$search = "SELECT region_id, name FROM region WHERE country_id = '$country_id' ORDER BY name ASC";

    //$search = "SELECT * FROM Users";
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}

function Add_City($connection, $country_id, $region_id, $city_name)
{

		$add_query ="INSERT INTO city VALUES(NULL, '$country_id', '$region_id', '$city_name')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connection->error);
    
}

/***********************************ОТЧЕТЫ***************************************/

function Show_Rep_Tickets($connection, $var, $year, $ticket_status, $month_start, $month_end) 
{
	$search = "SELECT * FROM tickets WHERE id_object='$var' AND year='$year' AND ticket_status='$ticket_status' AND month BETWEEN '$month_start' AND '$month_end'";
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        } 
    return $array; 		
    }

}
function Show_Contr_in_object($connection, $id_contractor)
{

	$search = "SELECT *  FROM object WHERE id_contractor = '$id_contractor' ORDER BY city_name ASC";
    
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}
function Show_Rep_Contr_Tickets($connection, $var, $year, $ticket_status, $month_start, $month_end) 
{
	$search = "SELECT * FROM tickets WHERE id_contractor='$var' AND year='$year' AND ticket_status='$ticket_status' AND month BETWEEN '$month_start' AND '$month_end'";
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        } 
    return $array; 		
    }

}

function Show_Contractor_Payment($connection, $method_payment) 
{

	$search = "SELECT * FROM contractor WHERE method_payment='$method_payment' AND status='1' ORDER BY org_name ASC";
    
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        }   
    }
    return $array; 
}

/******************************смена статуса *****************/

function Update_Status_Customer($connection, $id_customer, $status)
{
	$update = "UPDATE `projects` SET `status`='$status' WHERE `id_customer`='$id_customer'";
	$result = $connection->query ($update);
	if ($result) {
		$update = "UPDATE `object` SET `status`='$status' WHERE `id_customer`='$id_customer'";
		if ($result) return true;
		else
		die ($connection->error);
		mysqli_close($connection);	
	};
}

function Update_Status_Project($connection, $id_project, $status)
{
	$update = "UPDATE `object` SET `status`='$status' WHERE `id_project`='$id_project'";
	$result = $connection->query ($update);
	if ($result) return true;
	else
	die ($connection->error);
	mysqli_close($connection);	
}

function ToMail_Tickets($connection, $days) 
{
	$search = "SELECT ticket_number, id_object, ticket_status FROM tickets WHERE (ticket_status = '0' OR ticket_status = '2') AND (DATEDIFF(NOW(),`ticket_date`)) > '$days'";
    $result = $connection->query ($search);
    if (!$result) die ($connection->error);
    $rows = $result->num_rows;
    if (!$rows) return false;
    else
    {
        $array = array ();
        for ($i=0; $i<$rows; $i++)
        {
            $result->data_seek ($i);
            $row =$result->fetch_array (MYSQLI_ASSOC);
            $array["$i"] = $row;
        } 
    return $array; 		
    }

}

function cleanDirectory($dir, $maxFilesCount)
{
    $filenames = [];
 
    foreach(scandir($dir) as $file) {
        $filename = "$dir/$file";
        if (is_file($filename)) {
            $filenames[] = $filename;
        }
    }
 
    if (count($filenames) <= $maxFilesCount) {
        return;
    }
 
    $freshFilenames = array_reverse($filenames);
    array_splice($freshFilenames, $maxFilesCount);
    $oldFilenames = array_diff($filenames, $freshFilenames);
 
    foreach ($oldFilenames as $filename) {
        unlink($filename);
    }
}
















?>