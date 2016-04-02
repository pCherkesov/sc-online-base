<?php


$data = array();
$header = array();
$works = array();
$urls = array('lefturl' => "", 'month' => "", 'righturl' => "");

switch($route[1]){
	// Выбрать все работы с разбивкой по месяцам
	case "all":
		$add_query = " and r.hidden='N'";
		if (!isset($_GET['year'])) $year = date("Y"); else $year = $_GET['year'];
						$month = "r.date BETWEEN '".$year."-01-01' and '".$year."-12-31'"; 
						$urls['month'] = $year;
						$urls['lefturl'] = $_SERVER['PHP_SELF']."?r=many/".$route[1]."&year=".($year-1);
						$urls['righturl'] = $_SERVER['PHP_SELF']."?r=many/".$route[1]."&year=".($year+1);

		break;

	// Выбрать незавершённые
	case "incomplete":
		$add_query = " and r.complete='N' and r.hidden='N'"; 
		$month = "r.date BETWEEN '2006-00-00' and '2020-12-31'";
		break;

	// Выбрать завешённые с разбивкой по месяцам
	case "complete":
		$add_query= " and r.complete='Y' and r.hidden='N'";
		
		$word_month = array ("НУЛЯБРЬ", "ЯНВАРЬ", "ФЕВРАЛЬ", "МАРТ", "АПРЕЛЬ", "МАЙ", "ИЮНЬ", "ИЮЛЬ", "АВГУСТ", "СЕНТЯБРЬ", "ОКТЯБРЬ", "НОЯБРЬ", "ДЕКАБРЬ");
		
		$date = explode("/", $_GET['date']);
		$mon = (int)$date[0];
		$year = (int)$date[1];
		if ($mon == '13') {$mon = '1'; $year++;}
		if ($mon == '0') {$mon = '12'; $year--;}
		//$month = "r.date BETWEEN '".$year."-".$mon."-01' and '".$year."-".$mon."-31'"; 
		$month = "r.date_complete BETWEEN '".$date[1]."-".$date[0]."-01' and '".$date[1]."-".$date[0]."-31'"; 
		
		if (isset($_GET['id_worker'])) $povtor_work = "&id_worker=".$_GET['id_worker']; else $povtor_work = "";

		$urls['lefturl'] = $_SERVER['PHP_SELF']."?r=many/".$route[1]."&date=".($mon-1)."/".$year.$povtor_work;
		$urls['month'] = $word_month[$mon]. " " . $year;
		$urls['righturl'] = $_SERVER['PHP_SELF']."?r=many/".$route[1]."&date=".($mon+1)."/".$year.$povtor_work;

	break;

	default: 
		echo "Script error"; 
		exit(); 
		break;
}

$header['urls'] = $urls;
// Дополнительная выборка по работнику
if(isset($_REQUEST['sort'])){
	$worker = " and w.id_worker=r.id_worker and w.id_worker=".$_REQUEST['sort'];
	$header['sort'] = $_REQUEST['sort'];
} else $worker = '';


$query = "SELECT r.complete, r.id_r, DATE_FORMAT(r.date, '%d.%m.%Y') as date, r.string, b.brand, m.model, 
			r.id_client, c.client, c.client_tel_0, r.client_fio, r.client_tel, r.serial, r.id_worker
		FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."model` AS m,
		`".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b, 
		`".$S_CONFIG['prefix']."worker` AS w
		WHERE 
		".$month." 
		 and m.id_brand=b.id_brand
		 and r.id_model=m.id_model
		and r.id_client=c.id_client
		".$worker.
		$add_query." 
		GROUP BY  r.date, r.id_r ASC";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error());

while($option_query = mysqli_fetch_assoc($result)){
	$option_query['string'] = str_split($option_query['string']);
	$works[] = $option_query;
}

$query = "SELECT * FROM `".$S_CONFIG['prefix']."worker`";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while($option = mysqli_fetch_assoc($result)){
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

?>
