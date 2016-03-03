<?php

$args = array(
	'edit_id'		    => FILTER_VALIDATE_INT,
	'edit_id_work'      => FILTER_VALIDATE_INT,
	'edit_date'         => array('filter'   => FILTER_VALIDATE_REGEXP,
								 'options'  => array("regexp"=>"/^\d{4}-\d{2}-\d{2}$/")
								),
	'edit_text'         => FILTER_SANITIZE_STRING,
	'edit_price'        => FILTER_VALIDATE_INT,
	'edit_parts'		=> array( 
								 'filter'	=> FILTER_SANITIZE_STRING,
								 'flags'	=> FILTER_FLAG_NO_ENCODE_QUOTES,
								),
	'edit_parts_price'  => FILTER_VALIDATE_INT,
	'edit_worker'       => FILTER_VALIDATE_INT,
);

$inputs = filter_input_array(INPUT_POST, $args);
// var_dump($inputs);

if(!isset($inputs['edit_id'])) {
	$f_error = "Неправильный ID";
}
else {
// `id`, `id_r`, `date`, `text`, `price`, `hard`, `hard_price`, `id_worker`, `hidden`

	$query = "UPDATE `".$S_CONFIG['prefix']."work` SET
				date='". $inputs['edit_date']."', 
				text='".$inputs['edit_text']."',
				price='".$inputs['edit_price']."',
				hard='".$inputs['edit_parts']."',
				hard_price='".$inputs['edit_parts_price']."',
				id_worker='".$inputs['edit_worker']."'				
        	WHERE `id`='".$inputs['edit_id_work']."' LIMIT 1";

	$result = mysqli_query($S_CONFIG['link'], $query) or $f_error = mysqli_error($S_CONFIG['link']);
}

if(isset($f_error)) die($f_error);
$redirect = $_SERVER['HTTP_REFERER'];

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
HTML;

echo "	<meta http-equiv='refresh' content='0; URL=".$redirect."' />";

echo <<<HTML
</head>
<body>
</body>
</html>
HTML;

exit();
?>
