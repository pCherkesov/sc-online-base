<?php
require("init_j.php");
include "../lib/smsc_api.php";

$idr = $_POST['id'];
$tel = "7".str_replace(array(" ", "-", "(", ")"), "", $_POST['tel']);
$msg = $_POST['msg']; // iconv("UTF-8", "cp1251", $_POST['msg']);

//exit($idr. " - " .$tel. " - " . $msg);

try {
    list($sms_id, $sms_cnt, $cost, $balance) = send_sms($tel, $msg, 0, 0, 0, 0, "s.cOnline");
    // $phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = false, $query = "", $files = array()
}
catch (Exception $e) {
    echo $e->getMessage();
}

$result_query = "INSERT INTO `".$S_CONFIG['prefix']."sms` VALUE(0, ".$idr.", CURRENT_TIMESTAMP, '".$msg."', 1, 0);";

$results = mysqli_query($S_CONFIG['link'], $result_query) or exit(mysql_error());

//====================================================

$query = "SELECT `string` FROM `".$S_CONFIG['prefix']."remont` WHERE `id_r` = ".$idr;

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$status = $line['string'];
}

$status[9] == "Y";

$query1 = "UPDATE `".$S_CONFIG['prefix']."remont` SET `string` = '".$status."' WHERE `id_r` = ".$idr." LIMIT 1";

$r1 = mysqli_query($S_CONFIG['link'], $query1) or exit(mysqli_error($S_CONFIG['link']));
?>
