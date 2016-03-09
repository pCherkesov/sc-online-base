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
// 'product_id'   => FILTER_SANITIZE_ENCODED,
// 'component'    => array('filter'    => FILTER_VALIDATE_INT,
//                         'flags'     => FILTER_REQUIRE_ARRAY, 
//                         'options'   => array('min_range' => 1, 'max_range' => 10)
//                        ),
// 'versions'     => FILTER_SANITIZE_ENCODED,
// 'doesnotexist' => FILTER_VALIDATE_INT,
// 'testscalar'   => array(
//                         'filter' => FILTER_VALIDATE_INT,
//                         'flags'  => FILTER_REQUIRE_SCALAR,
//                        ),
// 'testarray'    => array(
//                         'filter' => FILTER_VALIDATE_INT,
//                         'flags'  => FILTER_REQUIRE_ARRAY,
//                        )

$inputs = filter_input_array(INPUT_POST, $args);
// var_dump($inputs);

if($inputs['edit_id_work'] != 0) {
	$redirect['error_text'] = "Неправильный ID";
}
else {
// `id`, `id_r`, `date`, `text`, `price`, `hard`, `hard_price`, `id_worker`, `hidden`
	$query = "INSERT INTO `".$S_CONFIG['prefix']."work` VALUE
	(0, 
	'".$inputs['edit_id']."', '". $inputs['edit_date']."', 
	'".$inputs['edit_text']."', '".$inputs['edit_price']."', 
	'".$inputs['edit_parts']."', '".$inputs['edit_parts_price']."',
	'".$inputs['edit_worker']."', 'N')";

	$result = mysqli_query($S_CONFIG['link'], $query) or $redirect['error_text'] = mysqli_error($S_CONFIG['link']);
}

$redirect['timer'] = 0;
$redirect['url'] = $_SERVER['HTTP_REFERER'];

render(array('redirect' => $redirect), "redirect");
?>
