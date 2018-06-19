<?php

$args = array(
	'edit_id'		    => FILTER_VALIDATE_INT,
);

$inputs = filter_input_array(INPUT_POST, $args);

if(!isset($inputs['edit_id'])) {
	$redirect['error_text'] = "Неправильный ID";
}
else {
// `id`, `id_r`, `date`, `text`, `price`, `hard`, `hard_price`, `id_worker`, `hidden`
	$query = "UPDATE `".$S_CONFIG['prefix']."remont` SET 
				`complete`='N', `date_complete`= NULL 
				WHERE `id_r`=".$inputs['edit_id']." LIMIT 1";

	$result = mysqli_query($S_CONFIG['link'], $query) or $redirect['error_text'] = mysqli_error($S_CONFIG['link']);
}

$redirect['timer'] = 0;

$redirect['url'] = $_SERVER['HTTP_REFERER'];

render(array('redirect' => $redirect), "redirect");
?>
