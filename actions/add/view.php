<?php
$args = array(
	'id'	=> FILTER_VALIDATE_INT,
);

$inputs = filter_input_array(INPUT_GET, $args);

$header = array(
	'id'	=>	$inputs['id'],
);
$works = array();

$query = "SELECT r.id_model, t.type, b.brand, m.model, r.id_client, c.client, c.client_tel_0, r.client_fio, r.client_tel, r.serial,
			CONCAT(t.type, ' ', b.brand, ' ', m.model) as device_name, r.complect, r.defect
		FROM `remont` AS r 
		LEFT JOIN `model` AS m ON r.id_model = m.id_model 
		LEFT JOIN `type` AS t ON m.id_type = t.id_type		
		LEFT JOIN `brand` AS b ON m.id_brand = b.id_brand 
		LEFT JOIN `client` AS c ON r.id_client = c.id_client 
		WHERE r.id_r = '". $inputs['id'] ."'
		;";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while($option = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$works[] = $option;
}


$query = "SELECT * FROM `".$S_CONFIG['prefix']."prin` WHERE `hidden` = 'N';";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while($option = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$header['prins'][$option['id_prin']] = $option;
}

@render($data = array('main' => $main, 'header' => $header, 'data' => $works[0]));

?>
