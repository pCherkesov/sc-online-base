<?php

$query = "SELECT * FROM `".$S_CONFIG['prefix']."prin` WHERE `hidden` = 'N';";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while($option = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	$header['prins'][$option['id_prin']] = $option;
}

render($data = array('main' => $main, 'header' => $header));

?>
