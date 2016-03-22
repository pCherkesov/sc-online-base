<?php

$word_month = array ("НУЛЯБРЬ", "ЯНВАРЬ", "ФЕВРАЛЬ", "МАРТ", "АПРЕЛЬ", "МАЙ", "ИЮНЬ", "ИЮЛЬ", "АВГУСТ", "СЕНТЯБРЬ", "ОКТЯБРЬ", "НОЯБРЬ", "ДЕКАБРЬ");

$date = explode("/", $_GET['date']);
$mon = (int)$date[0];
$year = (int)$date[1];
if ($mon == '13') {$mon = '1'; $year++;}
if ($mon == '0') {$mon = '12'; $year--;}
//$month = "r.date BETWEEN '".$year."-".$mon."-01' and '".$year."-".$mon."-31'"; 
$month = "w.date BETWEEN '".$date[1]."-".$date[0]."-01' and '".$date[1]."-".$date[0]."-31'"; 

if (isset($_GET['id_worker'])) $povtor_work = "&id_worker=".$_GET['id_worker']; else $povtor_work = "";

$urls['lefturl'] = $_SERVER['PHP_SELF']."?r=stat/".$route[1]."&date=".($mon-1)."/".$year.$povtor_work;
$urls['month'] = $word_month[$mon]. " " . $year;
$urls['righturl'] = $_SERVER['PHP_SELF']."?r=stat/".$route[1]."&date=".($mon+1)."/".$year.$povtor_work;

if(isset($_REQUEST['id_worker'])&&$_REQUEST['id_worker']!='*'){
	$worker=" and w.id_worker=".$_REQUEST['id_worker']." and r.id_worker=w.id_worker";
} else $worker='';

//======================================================
$query = "SELECT w.id_r FROM work AS w, remont AS r WHERE ".$month.$worker." and w.hidden='N' GROUP BY w.id_r ASC ;";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error($S_CONFIG['link']));
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$work[] = $line['id_r'];
}
//======================================================
$query = "SELECT r.id_r, DATE_FORMAT(r.date, '%d.%m.%Y') AS date, b.brand, m.model, 
c.client, r.client_fio, r.serial, r.string, r.complete, DATE_FORMAT(r.date_complete, '%d.%m.%Y') AS date_complete  
FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."model` AS m,
 `".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b
WHERE m.id_brand=b.id_brand and r.id_client=c.id_client and r.id_model=m.id_model and r.hidden='N' and r.id_r IN (".join(", ", $work).") ORDER BY r.date_complete ASC";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error($S_CONFIG['link']));
$total_price = 0;
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
//=======================================================================
	$result_query="SELECT id, date, text, price, id_worker 
					FROM `".$S_CONFIG['prefix']."work`
					WHERE id_r=".$line['id_r']." and hidden='N'";
					
	$results = mysqli_query($S_CONFIG['link'], $result_query) or exit(mysql_error($S_CONFIG['link']));
	// Печать результатов в HTML
	while ($lines = mysqli_fetch_array($results, MYSQL_ASSOC)) {
		$line['details'] = $lines; 
		$total_price = $total_price + $lines['price'];
	}
	$data[] = $line;
}

var_dump($data);
var_dump($total_price);
?>
