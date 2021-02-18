<?php
$args = array(
	'id'	=> FILTER_VALIDATE_INT,	
);
$inputs = filter_input_array(INPUT_GET, $args);

// WORK HEADER
$query = "SELECT 
	r.id_r, r.complete, DATE_FORMAT(r.date, '%d.%m.%Y') as date_start, DATE_FORMAT(r.date_complete, '%d.%m.%Y') as date_complete, 
	r.string, r.warranty_time, t.type, b.brand, m.id_model, m.model, 
	c.id_client, c.client, c.client_face, c.client_tel_0, r.client_fio, r.client_tel, 
	r.counter, r.serial, r.defect, r.complect, p.prin, r.id_worker
	FROM `remont` AS r 
	LEFT JOIN `client` AS c ON r.id_client = c.id_client 
	LEFT JOIN `model` AS m ON r.id_model = m.id_model 
	LEFT JOIN `type` AS t ON m.id_type = t.id_type
	LEFT JOIN `brand` AS b ON m.id_brand = b.id_brand 
	LEFT JOIN `worker` AS w ON r.id_worker = w.id_worker 
  	LEFT JOIN `prin` AS p ON r.id_prin = p.id_prin
WHERE r.id_r=". $inputs['id'] ."
ORDER BY r.id_r ASC";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$header = $line;
	$header['string'] = str_split($header['string']);
	if ($line['client']=="ч/л"){
		$header['client'] = 0;
	}
	else {
		$header['client'] = 1;
		$header['client_tel'] = $line['client_tel_0'];
		$header['client_fio'] = $line['client'];
	}
	$header['client_face'] = $line['client_fio'];
}
// END WORK HEADER

// WORK DETAILS
$result_query = "SELECT id, DATE_FORMAT(date, '%d.%m.%Y') as date, text, price, hard, hard_price, id_worker 
				FROM `".$S_CONFIG['prefix']."work`
				WHERE id_r=".$inputs['id']." and hidden='N'";
				
$results = mysqli_query($S_CONFIG['link'], $result_query) or exit(mysqli_error($S_CONFIG['link']));

$total_price = $total_hard_price = 0;

$works = array();
while ($lines = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
	if(isJson($lines['hard'])) $lines['hard'] = json_decode($lines['hard'], true);
	else $lines['hard'] = str_replace("\r\n", "<br />", $lines['hard']);

	$total_price += $lines['price'];
	$total_hard_price += $lines['hard_price'];

	$works[] = $lines;
}
$header['total_price'] = $total_price;
$header['total_hard_price'] = $total_hard_price;
// END WORK DETAILS

$query = "SELECT * FROM `".$S_CONFIG['prefix']."worker`";
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

render($data = array('main' => $main, 'header' => $header, 'works' => $works));

?>
