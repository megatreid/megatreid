<?php
include_once 'connection/config.php';
$id_customer = @intval($_GET['id_customer']);
//$country_id = 3159;

$regs=mysql_query("SELECT projectname, id_project  FROM projects WHERE id_customer=$id_customer AND status='1' ORDER BY projectname ASC" );
 
if ($regs) {
    $num = mysql_num_rows($regs);      
    $i = 0;
    while ($i < $num) {
       $projects[$i] = mysql_fetch_assoc($regs);   
       $i++;
    }     
    $result = array('projects'=>$projects);  
}
else {
	$result = array('type'=>'error');
}

//echo "<pre>";
//print_r ($result);
//echo "</pre>";
print json_encode($result); 
//print var_dump($result)
?>