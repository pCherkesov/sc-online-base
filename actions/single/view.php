<?php
// WORK HEADER
$query = "SELECT 
	r.id_r, r.complete, DATE_FORMAT(r.date, '%d.%m.%Y') as date_start, DATE_FORMAT(r.date_complete, '%d.%m.%Y') as date_complete, 
	r.string, t. type, b.brand, m.id_model, m.model, 
	c.id_client, c.client, c.client_face, c.client_tel_0, r.client_fio, r.client_tel, 
	r.counter, r.serial, r.defect, r.complect, p.prin, r.id_worker
FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."model` AS m,
 `".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b,
  `".$S_CONFIG['prefix']."prin` AS p, `".$S_CONFIG['prefix']."worker` AS w, `".$S_CONFIG['prefix']."type` AS t
WHERE r.id_prin=p.id_prin and m.id_brand=b.id_brand and r.id_client=c.id_client and r.id_model=m.id_model and m.id_type=t.id_type and r.id_r=".$_REQUEST['id']."
ORDER BY r.id_r ASC";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$header = $line;
	$header['string'] = str_split($header['string']);
	if ($line['client']=="ч/л"){
		$header['client'] = 0;
	}
	else {
		$header['client'] = 1;
		$header['client_g_face'] = $line['client_face'];
		$header['client_g_tel'] = $line['client_tel_0'];
		$header['client_fio'] = $line['client'];
	}
	$header['client_face'] = $line['client_fio'];
}
// END WORK HEADER

// WORK DETAILS
$result_query = "SELECT id, DATE_FORMAT(date, '%d.%m.%Y') as date, text, price, hard, hard_price, id_worker 
				FROM `".$S_CONFIG['prefix']."work`
				WHERE id_r=".$_REQUEST['id']." and hidden='N'";
				
$results = mysqli_query($S_CONFIG['link'], $result_query) or exit(mysqli_error($S_CONFIG['link']));

$total_price = $total_hard_price = 0;

$works = array();
while ($lines = mysqli_fetch_array($results, MYSQL_ASSOC)) {
	if(isJson($lines['hard'])) $lines['hard'] = json_decode($lines['hard'], true);
	else $lines['hard'] = str_replace("\r\n", "<br />", $lines['hard']);

	$total_price += $lines['price'];
	$total_hard_price += $lines['hard_price'];

	$works[] = $lines;
}
$header['total_price'] = $total_price;
$header['total_hard_price'] = $total_hard_price;
// END WORK DETAILS

$query = "SELECT * FROM `".$S_CONFIG['prefix']."worker` WHERE `hidden` = 'N'";
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

render($data = array('main' => $main, 'header' => $header, 'works' => $works));

?>
