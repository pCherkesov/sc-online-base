<?php
require("init_j.php");

$args = array(
	'action'	=> FILTER_SANITIZE_STRING,
	'name'		=> array( 
							'filter'	=> FILTER_SANITIZE_STRING,
							'flags'	=> FILTER_FLAG_NO_ENCODE_QUOTES,
						),
);

$inputs = filter_input_array(INPUT_POST, $args);

switch ($inputs['action']) {
	case 'typeahead-type':
		$query = "SELECT `type` as `name` FROM `".$S_CONFIG['prefix']."type` WHERE `type` LIKE '%". $inputs['name'] ."%';";
		break;

	case 'typeahead-brand':
		$query = "SELECT `brand` as `name` FROM `".$S_CONFIG['prefix']."brand` WHERE `brand` LIKE '%". $inputs['name'] ."%';";
		break;

	case 'typeahead-model':
		$query = "SELECT `model` as `name` FROM `".$S_CONFIG['prefix']."model` WHERE `model` LIKE '%". $inputs['name'] ."%';";
		break;
	
	default:
		break;
}

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$data[] = $line['name'];
}
echo json_encode($data);
?>
