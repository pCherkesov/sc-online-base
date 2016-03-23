<?php
require("init_j.php");
include "../lib/smsc_api.php";
$args = array(
	'id'	=> FILTER_VALIDATE_INT,
	'tel'	=> FILTER_SANITIZE_STRING,
	'msg'	=> array( 
				'filter'	=> FILTER_SANITIZE_STRING,
				'flags'	=> FILTER_FLAG_NO_ENCODE_QUOTES,
			),
);

$inputs = filter_input_array(INPUT_POST, $args);

$idr = $inputs['id'];
$tel = "7".str_replace(array(" ", "-", "(", ")"), "", $inputs['tel']);
$msg = $inputs['msg'];


//====== Отправка СМС сообщения ======
try {
    list($sms_id, $sms_cnt, $cost, $balance) = send_sms($tel, $msg, 0, 0, 0, 0, "s.cOnline");
    // $phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = "", $files = array()
    echo "Сообщение отправлено успешно. ID: $sms_id, стоимость: $cost, баланс: $balance.\n";
}
catch (Exception $e) {
    echo $e->getMessage();
}

$result_query = "INSERT INTO `".$S_CONFIG['prefix']."sms` VALUE(0, ".$idr.", '". $sms_id ."', CURRENT_TIMESTAMP, '".$msg."', 1, 0);";

mysqli_query($S_CONFIG['link'], $result_query) or exit(mysql_error($S_CONFIG['link']));


//====== Запись статуса в работу ======
$query = "SELECT `string` FROM `".$S_CONFIG['prefix']."remont` WHERE `id_r` = ".$idr;

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$status = $line['string'];
}

$status[9] = "Y";

$query = "UPDATE `".$S_CONFIG['prefix']."remont` SET `string` = '".$status."' WHERE `id_r` = ".$idr." LIMIT 1";

mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
?>