<?php




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

function Edit_User ($connection, $var) // Принимает подключение и id
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

	$search = "SELECT * FROM users ORDER BY id_users ASC";

    //$search = "SELECT * FROM Users";
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
function Show_Contr_for_select($connection) 
{

	$search = "SELECT DISTINCT city_id  FROM contractor ORDER BY city_id ASC";
    
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
function Show_Contractor($connection) 
{

	$search = "SELECT *  FROM contractor ORDER BY org_name ASC";
    
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

    //$search = "SELECT * FROM Users";
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
function Add_Customer($connection, $customer, $jur_address, $post_address, $ogrn, $inn, $kpp, $dogovor_number, $bank_name, $bank_bik, $korr_schet, $rasch_schet, $recipient, $phone, $email, $contact_name, $comment)
{

		$add_query ="INSERT INTO customer VALUES(NULL, '$customer','$jur_address', '$post_address', '$ogrn', '$inn', '$kpp', '$dogovor_number', '$bank_name', '$bank_bik', '$korr_schet', '$rasch_schet', '$recipient', '$phone', '$email', '$contact_name', '$comment')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connect->error);
    
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
function Update_Customer ($connection, $id_customer, $customer_name, $jur_address, $post_address, $ogrn, $inn, $kpp, $dogovor_number, $bank_name, $bank_bik, $korr_schet, $rasch_schet, $recipient, $phone, $email, $contact_name, $comment)
{
	$update = "UPDATE `customer` SET `customer_name`='$customer_name',`jur_address`='$jur_address',`post_address`='$post_address',`ogrn`='$ogrn', `inn`='$inn', `kpp`='$kpp', `dogovor_number`='$dogovor_number',`bank_name`='$bank_name',`bank_bik`='$bank_bik',`korr_schet`='$korr_schet',`rasch_schet`='$rasch_schet',`recipient`='$recipient',`phone`='$phone',`email`='$email', `comment`='$comment' WHERE `id_customer`='$id_customer'";

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

	$search = "SELECT * FROM projects WHERE id_customer='$var'";

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
function Add_Project($connection, $id_customer, $projectname, $cost_hour, $cost_incident_critical, $cost_incident_high, $cost_incident_medium, $cost_incident_low)
{

		$add_query ="INSERT INTO projects VALUES(NULL, '$id_customer','$projectname', '$cost_hour', '$cost_incident_critical', '$cost_incident_high', '$cost_incident_medium', '$cost_incident_low')";
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
function Update_Project($connection, $id_project, $id_customer, $projectname, $cost_hour, $cost_incident_critical, $cost_incident_high, $cost_incident_medium, $cost_incident_low)
{
    $update = "UPDATE projects SET id_customer='$id_customer', projectname='$projectname', cost_hour='$cost_hour', cost_incident_critical='$cost_incident_critical', cost_incident_high='$cost_incident_high', cost_incident_medium='$cost_incident_medium', cost_incident_low='$cost_incident_low' WHERE id_project='$id_project'";
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
	$search = "SELECT DISTINCT city_id, city_name FROM object WHERE id_project='$var'";
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
	
function Add_Object($connection, $id_project, $id_customer, $country_id, $region_id, $city_id, $shop_number, $address, $abon_plata, $id_contractor, $abon_plata_contr)
{
		$city = Get_Geo ($connection, $city_id, "city", "city_id" );
		$city_name = $city['name'];
		$add_query ="INSERT INTO object VALUES(NULL, '$id_project', '$id_customer', '$country_id', '$region_id', '$city_id', '$shop_number', '$address', '$abon_plata', '$city_name', '$id_contractor', '$abon_plata_contr')";
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
function Update_Object($connection, $id_object, $id_project, $id_customer, $country_id, $region_id, $city_id, $shop_number, $address, $abon_plata, $id_contractor, $abon_plata_contr)
{
	$city = Get_Geo ($connection, $city_id, "city", "city_id" );
	$city_name = $city['name'];
    $update = "UPDATE object SET id_project='$id_project', id_customer='$id_customer', country_id='$country_id', region_id='$region_id', city_id='$city_id', shop_number='$shop_number', address='$address', abon_plata='$abon_plata', city_name='$city_name', id_contractor=$id_contractor, abon_plata_contr=$abon_plata_contr  WHERE id_object='$id_object'";
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

function Add_Ticket($connection, $ticket_number, $year, $month, $id_object, $ticket_date, $ticket_task, $ticket_solution, $ticket_material, $ticket_status, $ticket_sla, $work_type, $hours, $cost_smeta, $cost_material, $cost_transport, $comment, $last_edit_datetime, $last_edit_user_id, $implementer, $id_contractor, $contr_cost_work, $contr_cost_smeta, $contr_cost_transport, $contr_material, $contr_cost_material, $contr_account_number, $contr_date_payment, $contr_payment_status, $contr_comment, $supplier, $supplier_cost_work, $supplier_contr_material, $supplier_cost_material, $supplier_account_number, $supplier_date_payment, $supplier_payment_status, $supplier_comment)
{

		$add_query ="INSERT INTO tickets VALUES(NULL, '$ticket_number', '$year', '$month', '$id_object', '$ticket_date', '$ticket_task', '$ticket_solution', '$ticket_material', '$ticket_status', '$ticket_sla', '$work_type', '$hours', '$cost_smeta', '$cost_material', '$cost_transport', '$comment', '$last_edit_datetime', '$last_edit_user_id', '$implementer', '$id_contractor', '$contr_cost_work', '$contr_cost_smeta', '$contr_cost_transport', '$contr_material', '$contr_cost_material', '$contr_account_number', '$contr_date_payment', '$contr_payment_status', '$contr_comment', '$supplier', '$supplier_cost_work', '$supplier_contr_material', '$supplier_cost_material', '$supplier_account_number', '$supplier_date_payment', '$supplier_payment_status', '$supplier_comment')";
		$result = $connection->query($add_query); 
        if ($result) 
            return true;
        else
            die ($connection->error);
    
}

function Show_Tickets($connection, $ticket_status, $current_month, $implementer)
{
	$search = "SELECT id_ticket, ticket_number, ticket_date, year, month, id_object, ticket_status, implementer, id_contractor, contr_payment_status, last_edit_datetime, last_edit_user_id FROM tickets WHERE (`ticket_status` LIKE '%$ticket_status%') AND (`month` LIKE '%$current_month%') AND (`implementer` LIKE '%$implementer%') ORDER BY id_ticket DESC";
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
function Update_Ticket($connection, $id_ticket, $ticket_number, $year, $month, $ticket_task, $ticket_solution, $ticket_material, $ticket_status, $ticket_sla, $work_type, $hours, $cost_smeta, $cost_material, $cost_transport, $comment, $last_edit_datetime, $last_edit_user_id, $implementer, $id_contractor, $contr_cost_work, $contr_cost_smeta, $contr_cost_transport, $contr_material, $contr_cost_material, $contr_account_number, $contr_date_payment, $contr_payment_status, $contr_comment, $supplier, $supplier_cost_work, $supplier_contr_material, $supplier_cost_material, $supplier_account_number, $supplier_date_payment, $supplier_payment_status, $supplier_comment)
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
	`comment`='$comment',
	`last_edit_datetime`='$last_edit_datetime',
	`last_edit_user_id`='$last_edit_user_id',
	`implementer`='$implementer',
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

	$search = "SELECT * FROM contractor WHERE method_payment='$method_payment' ORDER BY org_name ASC";
    
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
























?>