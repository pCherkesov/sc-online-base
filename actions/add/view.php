<?php

$query = "SELECT `id_client`, `client`, `client_tel_0` as `client_tel` FROM `".$S_CONFIG['prefix']."client` WHERE `id_client` > '1';";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$clients[] = $line;
}

$query = "SELECT `id_model`, `model` FROM `".$S_CONFIG['prefix']."model`";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$models[] = $line;
}
$header = array();
// var_dump($data);
render($data = array('main' => $main, 'header' => $header, 'clients' => $clients, 'models' => $models));

?>
