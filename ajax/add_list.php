<?php
$twig = TRUE;
require("init_j.php");

$args = array(
	'action'	=> FILTER_SANITIZE_STRING,
	'client'	=> FILTER_VALIDATE_INT,
	'model'		=> FILTER_VALIDATE_INT,
);

$inputs = filter_input_array(INPUT_POST, $args);
// $inputs = filter_input_array(INPUT_GET, $args);
switch ($inputs['action']) {
	case 'client':
		$query = "SELECT `id_client`, `client`, `client_tel_0` as `client_tel` 
				  FROM `".$S_CONFIG['prefix']."client` WHERE `id_client` > '1';";
		$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
		while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data['clients'][] = $line;
		}
		break;
	
	case 'device':
		$query = "SELECT `id_model`, `model`, `brand`, `type` 
		FROM `".$S_CONFIG['prefix']."model` as `model` 
		LEFT JOIN `".$S_CONFIG['prefix']."brand` as `brand`
		ON `model`.`id_brand` = `brand`.`id_brand`
		LEFT JOIN `".$S_CONFIG['prefix']."type` as `type`
		ON `model`.`id_type` = `type`.`id_type`
		";
		$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
		while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data['models'][] = $line;
		}
		break;
	default:
		$query = "SELECT `serial` 
		FROM `".$S_CONFIG['prefix']."remont`
		WHERE `id_client` = '". $inputs['client'] ."' AND `id_model` = '". $inputs['model'] ."' 
		AND `serial` <> ''
		GROUP BY `serial` ASC";
		$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
		while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data['serials'][] = $line;
		}
		break;
}

	$tpl = $S_CONFIG['twig']->loadTemplate('add_list.html');
	echo $tpl->render(array('data' => $data));

?>
