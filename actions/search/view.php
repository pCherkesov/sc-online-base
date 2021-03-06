<?php
$args = array(
	'search'	=> FILTER_SANITIZE_STRING,
	'page'	=> FILTER_VALIDATE_INT,	
);
$inputs = filter_input_array(INPUT_GET, $args);
if($inputs['page'] == "") $inputs['page'] = 1;

$header = array(
	'search'	=>	$inputs['search'],
);
$urls = array(
	'lefturl' => "index.php?r=search/view&search=". $inputs['search'] ."&page=". ($inputs['page']-1), 
	'month' => "", 
	'righturl' => "index.php?r=search/view&search=". $inputs['search'] ."&page=". ($inputs['page']+1),
);
$works = array();

$inputs['search'] = str2phone($inputs['search']);

$query = "SELECT SQL_CALC_FOUND_ROWS r.complete, r.id_r, DATE_FORMAT(r.date, '%d.%m.%Y') as date, CONCAT(b.brand, ' ', m.model) as model, 
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
		ORDER BY r.date DESC, r.complete ASC
		LIMIT ". (($inputs['page'] - 1) * 50) .", 50";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error());

while($option_query = mysqli_fetch_assoc($result)){
	$option_query['string'] = str_split($option_query['string']);
	$works[] = $option_query;
}

$query = "SELECT FOUND_ROWS();";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
while($option = mysqli_fetch_array($result)){
	$row_count = $option[0];
}
$urls['month'] = "Страница ". $inputs['page'] ." из ". round($row_count / ($inputs['page'] * 50));

$query = "SELECT * FROM `worker`";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while($option = mysqli_fetch_assoc($result)){
	$header['workers'][$option['id_worker']] = $option;
}

$header['urls'] = $urls;
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

function str2phone ($str) {
	$str_phone = preg_replace("/[^0-9]/", '', $str);
	if(!preg_match('/9(\d){9}/i', $str_phone, $phone)) return $str;
	return sprintf("(%s) %s-%s", substr($phone[0], 0, 3), substr($phone[0], 3, 3), substr($phone[0], 6, 4));
}