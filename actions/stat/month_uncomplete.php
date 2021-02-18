<?php

$word_month = array ("НУЛЯБРЬ", "ЯНВАРЬ", "ФЕВРАЛЬ", "МАРТ", "АПРЕЛЬ", "МАЙ", "ИЮНЬ", "ИЮЛЬ", "АВГУСТ", "СЕНТЯБРЬ", "ОКТЯБРЬ", "НОЯБРЬ", "ДЕКАБРЬ");

$date = explode("/", $_GET['date']);
$mon = (int)$date[0];
$year = (int)$date[1];
if ($mon == '13') {$mon = '1'; $year++;}
if ($mon == '0') {$mon = '12'; $year--;}
//$month = "r.date BETWEEN '".$year."-".$mon."-01' and '".$year."-".$mon."-31'"; 
$month = " date BETWEEN '".$date[1]."-".$date[0]."-01' and '".$date[1]."-".$date[0]."-31'"; 

$id_worker = $_GET['worker'];
$header['sort'] = $id_worker;
// if (isset($id_worker)) $povtor_work = "&id_worker=".$id_worker; else $povtor_work = "";

$urls['lefturl'] = $_SERVER['PHP_SELF']."?r=stat/".$route[1]."&date=".($mon-1)."/".$year."&worker=".$id_worker;
$urls['month'] = $word_month[$mon]. " " . $year;
$urls['righturl'] = $_SERVER['PHP_SELF']."?r=stat/".$route[1]."&date=".($mon+1)."/".$year."&worker=".$id_worker;
$urls['self'] = $_SERVER['PHP_SELF']."?r=stat/".$route[1]."&date=".$mon."/".$year."&worker=";

if(isset($id_worker) && $id_worker != 'all'){
	$worker = " and id_worker=" . $id_worker;
} else $worker = '';

//======================================================
$query = "SELECT id_r FROM work AS w WHERE ".$month.$worker." and hidden='N' GROUP BY id_r ASC ;";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$work[] = $line['id_r'];
}

if(!empty($work)) {
	//======================================================
	$query = "SELECT r.id_r, DATE_FORMAT(r.date, '%d.%m.%Y') AS date, b.brand, m.model, r.id_worker, 
	c.client, r.client_fio, r.serial, r.string, r.complete, DATE_FORMAT(r.date_complete, '%d.%m.%Y') AS date_complete  
	FROM `remont` AS r 
	LEFT JOIN `client` AS c ON r.id_client = c.id_client 
	LEFT JOIN `model` AS m ON r.id_model = m.id_model 
	LEFT JOIN `brand` AS b ON m.id_brand = b.id_brand 
	LEFT JOIN `worker` AS w ON r.id_worker = w.id_worker 
	WHERE r.hidden='N' and r.id_r IN (" . join(", ", $work) . ") and r.date_complete IS NOT NULL
	ORDER BY r.date_complete ASC";

	$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
	
	$total_price = 0;
	$total_hard_price = 0;
	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$line['string'] = str_split($line['string']);

		$result_query="SELECT id, DATE_FORMAT(date, '%d.%m.%Y') AS date, text, hard, price, hard_price, id_worker 
						FROM `work`
						WHERE id_r = ".$line['id_r']. $worker ." and hidden='N'";
						
		$results = mysqli_query($S_CONFIG['link'], $result_query) or exit(mysql_error($S_CONFIG['link']));

		$details = array();
		while ($lines = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			$lines['hard'] = json_decode($lines['hard']);
			$details[] = $lines;
			$total_price = $total_price + $lines['price'];
			$total_hard_price = $total_hard_price + $lines['hard_price'];
		}
		$line['details'] = $details; 
		$data[] = $line;
	}
}
else {
	$data = [];
	$total_price = 0;
	$total_hard_price = 0;
}

// var_dump($data);
// var_dump($total_price);
$query = "SELECT * FROM `".$S_CONFIG['prefix']."worker` WHERE hidden='N'";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while($option = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$header['workers'][$option['id_worker']] = $option;
}

$header['string_text'] = array(
	'Просрочено',
	'Проблема',
	'Клиент согласен',
	'Требуется соглашение',
	'!!! ВНИМАНИЕ !!!',
	'Ремонт закончен',
	'Неудача',
	'Ждём поставки',
);

$header['urls'] = $urls;
render($data = array('main' => $main, 'header' => $header, 'works' => $data, 'price' => ['price' => $total_price, 'hard_price' => $total_hard_price]));
?>
