<?php
require("init_j.php");

$args = array(
	'action'	=> FILTER_SANITIZE_STRING,
	'name'		=> array( 
							'filter'	=> FILTER_SANITIZE_STRING,
							'flags'	=> FILTER_FLAG_NO_ENCODE_QUOTES,
						),
	'id'		=> FILTER_VALIDATE_INT,
	'type'		=> FILTER_SANITIZE_STRING,
	'brand'		=> FILTER_SANITIZE_STRING,
	'model'		=> FILTER_SANITIZE_STRING,
);

$inputs = filter_input_array(INPUT_POST, $args);

switch ($inputs['action']) {
	case 'typeahead-type':
		$query = "SELECT `type` as `name` FROM `".$S_CONFIG['prefix']."type` WHERE `type` LIKE '%". $inputs['name'] ."%' GROUP BY `name`;";
		break;

	case 'typeahead-brand':
		$query = "SELECT `brand` as `name` FROM `".$S_CONFIG['prefix']."brand` WHERE `brand` LIKE '%". $inputs['name'] ."%' GROUP BY `name`;";
		break;

	case 'typeahead-model':
		$query = "SELECT `model` as `name` FROM `".$S_CONFIG['prefix']."model` WHERE `model` LIKE '%". $inputs['name'] ."%' GROUP BY `name`;";
		break;
	
	case 'add':
		$typeId = checkID($S_CONFIG['link'], "type", $inputs['type']);
		$brandId = checkID($S_CONFIG['link'], "brand", $inputs['brand']);

		if(!$typeId) {
			$query = "INSERT INTO `".$S_CONFIG['prefix']."type` VALUE (0, '". $inputs['type'] ."');";
			mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
			$typeId = mysqli_insert_id($S_CONFIG['link']);
		}

		if(!$brandId) {
			$query = "INSERT INTO `".$S_CONFIG['prefix']."brand` VALUE (0, '". $typeId ."', '". $inputs['brand'] ."');";
			mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
			$brandId = mysqli_insert_id($S_CONFIG['link']);
		}		

		if ($inputs['id'] == 0) {
			$query = "INSERT INTO `".$S_CONFIG['prefix']."model` VALUE 
			(NULL, '". $typeId ."', '". $brandId ."', '". $inputs['model'] ."');";
			mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
			$modelId = mysqli_insert_id($S_CONFIG['link']);
		}
		else {
			$query = "UPDATE `".$S_CONFIG['prefix']."model` 
							SET	`id_type` = '". $typeId ."',
							`id_brand` = '". $brandId ."',
							`model` = '". $inputs['model'] ."'
							WHERE `id_model` = '". $inputs['id'] ."';";
			mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));							
			$modelId = $inputs['id'];
		}
		echo json_encode(array('id_model' => $modelId, 'device_name' => $inputs['type'] . "" . $inputs['brand'] . " " . $inputs['model']));
		exit();
		break;

	default:
		break;
}

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$data[] = $line['name'];
}
echo json_encode($data);


function checkID($link, $type, $name) {
	$query = "SELECT `id_". $type ."` as `id` FROM `".$S_CONFIG['prefix']."". $type ."` WHERE `". $type ."` = '". $name ."';"; 
	$result = mysqli_query($link, $query) or exit(mysqli_error($link));

	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		return $line['id'];
	}
	return False;
}
?>
