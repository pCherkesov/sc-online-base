<?php
require ("../config.php");

// Соединение, выбор БД
$link = mysqli_connect("127.0.0.1", $S_CONFIG['user'], $S_CONFIG['pass'], $S_CONFIG['db']);

if(!$link) exit("Соединение с mySQL-сервером недоступно: ".mysqli_errno($link)." - ".mysqli_error($link));
else {
	$S_CONFIG['link'] = $link;
	mysqli_query($S_CONFIG['link'], 'SET character_set_results="UTF8"');
	mysqli_query($S_CONFIG['link'], "SET NAMES UTF8"); 
}

function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

?>
