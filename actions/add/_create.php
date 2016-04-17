<?php
$args = array(
	'id_client'		=>	FILTER_VALIDATE_INT,
	'id_model'		=>	FILTER_VALIDATE_INT,
	'date'			=>	array(
							'filter'	=> FILTER_VALIDATE_REGEXP,
							'options'	=>	array(
								'regexp'	=>	"/^\d{4}-\d{2}-\d{2}$/",
							),
						),
	'fio_human'		=>	FILTER_SANITIZE_STRING,
	'tel_human'		=>	array(
							'filter'	=> FILTER_VALIDATE_REGEXP,
							'options'	=>	array(
								'regexp'	=>	"/^\(\d{3}\).\d{3}-\d{4}$/",
							),
						),
	'serial'		=>	FILTER_SANITIZE_STRING,
	'defect'		=>	array( 
							'filter'	=> FILTER_SANITIZE_STRING,
							'flags'		=> FILTER_FLAG_NO_ENCODE_QUOTES,
						),
	'complect'		=>	array( 
							'filter'	=> FILTER_SANITIZE_STRING,
							'flags'		=> FILTER_FLAG_NO_ENCODE_QUOTES,
						),
	'print'		=>	FILTER_SANITIZE_STRING,
	'prin'		=>	FILTER_VALIDATE_INT,
);

$inputs = filter_input_array(INPUT_POST, $args);
// var_dump($inputs);

$query = "INSERT INTO `".$S_CONFIG['prefix']."remont` 
			VALUE (NULL, ?, ?, 'NNNNNNNNNN', ?, ?, ?, ?, ?, ?, ?, 0, ?, 1, 'N', NULL, 'N', '', 'N')";
$prep = mysqli_prepare($S_CONFIG['link'], $query);

$pass = pass_generator();
mysqli_stmt_bind_param($prep, 'ssississsi', 
	$pass, 
	$inputs['date'], 
	$inputs['id_client'], 
	$inputs['fio_human'], 
	$inputs['tel_human'], 
	$inputs['id_model'], 
	$inputs['complect'], 
	$inputs['defect'], 
	$inputs['serial'], 
	$inputs['prin']);


$redirect['timer'] = 0;

if (!mysqli_execute($prep)) {
    $redirect['error_text'] = mysqli_error($S_CONFIG['link']);
    $redirect['url'] = $_SERVER['HTTP_REFERER'];
}
else {
	$insert_id = mysqli_insert_id($S_CONFIG['link']);
	$redirect['url'] = "?r=single/view&id=". $insert_id;
	if(isset($inputs['print'])){
		$redirect['text'] = '<script type="text/javascript">
			window.open("/index.php?r=print/add&id='.$insert_id.'", "_blank");
		</script>';
	}
}

render(array('redirect' => $redirect), "redirect");


function pass_generator() {
	$lowercase = "zyxwvutsrqponmlkjihgfedcba"; //символы в нижнем регистре 26
	$uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; //символы в верхнем регистре 26
	$speccase = "!-_+.,"; //специальные символы 6
	$digitcase = "0123456789"; //цифры 10
	$PassCase = $lowercase . $uppercase . $speccase . $digitcase; //68
	$PassCase = $digitcase; //10
	$pass = "";
	mt_srand(time()+(double)microtime()*1000000);
	for($i = 0; $i <= 3; $i++) {
		$pass .= $PassCase[mt_rand(0, 9)];
	};
	return $pass;
}
?>
