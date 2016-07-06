<?php
$args = array(
	'search'	=> FILTER_SANITIZE_STRING,
);

$inputs = filter_input_array(INPUT_GET, $args);

$header = array(
	'search'	=>	$inputs['search'],
);
$works = array();

$query = "SELECT r.complete, r.id_r, DATE_FORMAT(r.date, '%d.%m.%Y') as date, CONCAT(b.brand, ' ', m.model) as model, 
			r.string, r.id_client, c.client, c.client_tel_0, r.client_fio, r.client_tel, r.serial, r.id_worker
		FROM `remont` AS r 
		LEFT JOIN `model` AS m ON r.id_model = m.id_model 
		LEFT JOIN `client` AS c ON r.id_client = c.id_client 
		LEFT JOIN `brand` AS b ON m.id_brand = b.id_brand 
		LEFT JOIN `worker` AS w ON r.id_worker = w.id_worker 
		WHERE r.hidden = 'N' 
		AND (
			r.id_r LIKE '%". $inputs['search'] ."%'
			OR CONCAT(b.brand, ' ', m.model) LIKE '%". $inputs['search'] ."%'
			OR c.client LIKE '%". $inputs['search'] ."%'
			OR r.client_fio LIKE '%". $inputs['search'] ."%'
			OR r.client_tel LIKE '%". $inputs['search'] ."%'
			OR c.client_tel_0 LIKE '%". $inputs['search'] ."%'
			OR r.serial LIKE '%". $inputs['search'] ."%'
		)
		ORDER BY r.date DESC";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error());

while($option_query = mysqli_fetch_assoc($result)){
	$option_query['string'] = str_split($option_query['string']);
	$works[] = $option_query;
}

$query = "SELECT * FROM `worker`";
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
