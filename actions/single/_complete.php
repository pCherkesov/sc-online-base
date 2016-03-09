<?php

$args = array(
	'edit_id'		    => FILTER_VALIDATE_INT,
);

$inputs = filter_input_array(INPUT_POST, $args);

if(!isset($inputs['edit_id'])) {
	$redirect['error_text'] = "Неправильный ID";
}
else {
	$query = "UPDATE `".$S_CONFIG['prefix']."remont` SET 
				`complete`='Y', `date_complete`=NOW() 
				WHERE `id_r`=".$inputs['edit_id']." LIMIT 1";

	$result = mysqli_query($S_CONFIG['link'], $query) or $redirect['error_text'] = mysqli_error($S_CONFIG['link']);
}

if(isset($_GET['print_check'])) include 'check_xls.php';

$redirect['timer'] = 0;

if(isset($redirect['error_text'])) $redirect['url'] = $_SERVER['HTTP_REFERER'];
else $redirect['url'] = "/index.php?r=many/incomplete";

render(array('redirect' => $redirect), "redirect");
?>
