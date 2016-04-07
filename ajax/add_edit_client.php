<?php
require("init_j.php");

$args = array(
	'id_client'		=> FILTER_VALIDATE_INT,
	'client_name'	=> array( 
								'filter'	=> FILTER_SANITIZE_STRING,
								'flags'	=> FILTER_FLAG_NO_ENCODE_QUOTES,
							),
	'client_tel'	=> FILTER_SANITIZE_STRING,
);

$inputs = filter_input_array(INPUT_POST, $args);

if($inputs['id_client'] == 0) {
	$query = "INSERT INTO `".$S_CONFIG['prefix']."client` 
	VALUE (NULL, '". $inputs['client_name'] ."', '', '". $inputs['client_tel'] ."');";
}
else {
	$query = "UPDATE `".$S_CONFIG['prefix']."client` 
	SET `client`='". $inputs['client_name'] ."', `client_tel_0`='". $inputs['client_tel'] ."' 
	WHERE `id_client`='". $inputs['id_client'] ."'";
}
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

?>
