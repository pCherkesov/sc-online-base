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
?>
