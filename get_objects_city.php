<?php
include_once '/connection/config.php';
$id_project = @intval($_GET['id_project']);
//$id_project = 5;

/*
$regs=mysql_query("SELECT shop_number, city_id, address FROM object WHERE id_project = $id_project"); 

if ($regs) {
    $num = mysql_num_rows($regs);      
    $i = 0;
    while ($i < $num) {
       $objects[$i] = mysql_fetch_assoc($regs);  
		
       $i++;
    } 
    
    $result = array('objects'=>$objects);  
}
else {
	$result = array('type'=>'error');
}

//print_r $result['shop_number'];

*/
$regs = Show_Objects_for_search($link, $id_project);
if($regs){
	
	$result = array('objects'=>$regs);
}
else {
	
	//$result = array('type'=>'error');
	
}




/**
 * if ($regs) {
 * $num = mysql_num_rows($regs); 
 * $citys = array();

 * for ($i=0; $i<$num; $i++) 
 * $city[$i] = mysql_fetch_row($regs);

 * $i=0;
 * 	foreach ($city as $r) {
 * 		$citys[] = array('id'=>$i, 'title'=>$r);
 * 		$i++;
 * 	} 
 * $result = array('type'=>'success', 'citys'=>$citys);  
 * }
 * else {
 * 	$result = array('type'=>'error');
 * }
 */
//echo "<pre>";
//print_r ($result);
//echo "</pre>";
print json_encode($result); 
//print var_dump($result)
?>