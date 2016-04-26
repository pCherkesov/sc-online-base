<?php
require("init_j.php");
$args = array(
	'id'		    => FILTER_VALIDATE_INT,
	'counter'	    => FILTER_VALIDATE_INT,
);

$inputs = filter_input_array(INPUT_POST, $args);

$query = "UPDATE `".$S_CONFIG['prefix']."remont` SET `counter`='".$inputs['counter']."' WHERE `id_r`='".$inputs['id']."' LIMIT 1";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

echo $inputs['counter'];
?>
