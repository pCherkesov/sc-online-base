<?php
require("init_j.php");

$result_query = "SELECT date, text, price, hard, hard_price, id_worker
	FROM `".$S_CONFIG['prefix']."work`
	WHERE id=".$_REQUEST['id'];

$results = mysqli_query($S_CONFIG['link'], $result_query) or exit(mysqli_error($S_CONFIG['link']));

$work = array();
while ($data = mysqli_fetch_array($results, MYSQL_ASSOC)) {
	$work = $data;
	$work['text'] = str_replace("<br />", ", ", $data['text']);
	// print_r($work['hard']);
	if(isJson($work['hard'])) {
		$work['hardJson'] = json_decode($work['hard'], true);
	}
	else {
		$work['hardJson'] = array(array("edit_hard" => str_replace("<br />", "\r\n", $data['hard']), "edit_hardprice" => $data['hard_price']));
	}
	// var_dump($work['hardJson']);

	unset($work['hard']);
}

echo json_encode($work);


// header('HTTP/1.1 500 Internal Server Booboo');
// header('Content-Type: application/json; charset=UTF-8');
// die(json_encode(array('message' => 'ERROR', 'code' => 1)));

?>
